<?php

namespace App\Http\Controllers\backend\dashboard;

use App\Http\Controllers\Controller;
use App\Models\College as CollegeModel;
use App\Models\CollegePage as CollegePageModel;
use App\Models\Menu;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class CollegePage extends Controller
{
    public function getMenus()
    {
        $user = Auth::user();

        $menuIds = DB::table('menu_user_maps')
            ->where('user_id', $user->id)
            ->pluck('menu_id')
            ->toArray();

        if (empty($menuIds)) {
            return collect();
        }

        $childMenus = Menu::whereIn('id', $menuIds)
            ->where('menu_id', '!=', 0)
            ->get()
            ->groupBy('menu_id');

        return Menu::where('menu_id', 0)
            ->get()
            ->filter(fn($parent) => isset($childMenus[$parent->id]))
            ->map(function ($parent) use ($childMenus) {
                $parent->children = $childMenus[$parent->id];
                return $parent;
            });
    }

    /**
     * Compress + resize an uploaded image, then encrypt as "mime|base64".
     * Keeps images small so they never hit MySQL's max_allowed_packet limit.
     */
    private function encryptImage(Request $request, string $field, $oldValue = null, int $maxWidth = 800, int $quality = 70)
    {
        if (!$request->hasFile($field)) {
            return $oldValue;
        }

        $file = $request->file($field);
        $path = $file->getRealPath();

        [$width, $height, $type] = getimagesize($path);

        // Load image based on original type
        $source = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($path),
            IMAGETYPE_PNG  => imagecreatefrompng($path),
            IMAGETYPE_GIF  => imagecreatefromgif($path),
            default        => null,
        };

        if (!$source) {
            // Fallback: encrypt as-is if GD can't handle this type
            $mimeType = $file->getMimeType();
            $base64   = base64_encode(file_get_contents($path));
            return Crypt::encryptString($mimeType . '|' . $base64);
        }

        // Resize only if wider than $maxWidth (keeps aspect ratio)
        if ($width > $maxWidth) {
            $newWidth  = $maxWidth;
            $newHeight = intval($height * ($maxWidth / $width));

            $resized = imagecreatetruecolor($newWidth, $newHeight);

            // Preserve transparency for PNG/GIF
            if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_GIF) {
                imagecolortransparent($resized, imagecolorallocatealpha($resized, 0, 0, 0, 127));
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }

            imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($source);
            $source = $resized;
        }

        // Compress to JPEG in memory (smallest reliable format for photos)
        ob_start();
        imagejpeg($source, null, $quality);
        $compressedData = ob_get_clean();
        imagedestroy($source);

        $base64 = base64_encode($compressedData);

        return Crypt::encryptString('image/jpeg|' . $base64);
    }

    /**
     * Decrypt an encrypted "mime|base64" image into a data URI for <img src="">.
     */
    private function decryptImage($encrypted)
    {
        if (!$encrypted) {
            return null;
        }

        try {
            $decrypted = Crypt::decryptString($encrypted);
            $parts = explode('|', $decrypted, 2);

            if (count($parts) === 2) {
                [$mimeType, $imageData] = $parts;
                return "data:{$mimeType};base64,{$imageData}";
            }
        } catch (DecryptException $e) {
            return null;
        }

        return null;
    }

    // Get the logged-in operator's own college — the ONLY college they're allowed to manage
    private function getOperatorCollege()
    {
        $user = Auth::user();

        if (!$user->college_id) {
            abort(403, 'No college is assigned to your account. Contact the administrator.');
        }

        return CollegeModel::findOrFail($user->college_id);
    }

    // GET: /admin/dashboard/collegepage/index
    public function index()
    {
        $menus  = $this->getMenus();
        $college = $this->getOperatorCollege();

        // Only this operator's own college pages
        $pages = CollegePageModel::with('college')
            ->where('college_id', $college->id)
            ->get();

        foreach ($pages as $page) {
            $page->banner_url          = $this->decryptImage($page->banner);
            $page->principle_image_url = $this->decryptImage($page->principle_image);
        }

        return view('backend.admin.collegePage.viewCollegePage', compact('menus', 'pages'));
    }

    // GET: /admin/dashboard/collegepage/show/{id}
    public function show($id)
    {
        $menus   = $this->getMenus();
        $college = $this->getOperatorCollege();

        // Ensure the page belongs to this operator's college
        $page = CollegePageModel::with('college')
            ->where('college_id', $college->id)
            ->findOrFail($id);

        $bannerUrl         = $this->decryptImage($page->banner);
        $principleImageUrl = $this->decryptImage($page->principle_image);

        return view('backend.admin.collegePage.collegePageDetails', compact(
            'menus', 'page', 'bannerUrl', 'principleImageUrl'
        ));
    }

    // GET: /admin/dashboard/collegepage/create
    public function create()
    {
        $menus   = $this->getMenus();
        $college = $this->getOperatorCollege();

        return view('backend.admin.collegePage.addCollegePage', compact('menus', 'college'));
    }

    // POST: /admin/dashboard/collegepage/store
    public function store(Request $request)
    {
        $college = $this->getOperatorCollege();

        // Prevent duplicate page for the same college
        if (CollegePageModel::where('college_id', $college->id)->exists()) {
            return redirect()->back()
                ->with('error', 'A page already exists for your college. Please edit it instead.');
        }

        $request->validate([
            'page'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'banner'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'principle_image'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'principle_message'  => 'nullable|string',
        ]);

        $encryptedBanner         = $this->encryptImage($request, 'banner');
        $encryptedPrincipleImage = $this->encryptImage($request, 'principle_image');

        CollegePageModel::create([
            'college_id'         => $college->id, // always from logged-in operator, never from form
            'page'               => $request->page,
            'description'        => $request->description,
            'banner'             => $encryptedBanner,
            'principle_image'    => $encryptedPrincipleImage,
            'principle_message'  => $request->principle_message,
        ]);

        return redirect('admin/dashboard/collegepage/index')
            ->with('success', 'College page created successfully!');
    }

    // GET: /admin/dashboard/collegepage/edit/{id}
    public function edit($id)
    {
        $menus   = $this->getMenus();
        $college = $this->getOperatorCollege();

        $page = CollegePageModel::where('college_id', $college->id)->findOrFail($id);

        $bannerUrl         = $this->decryptImage($page->banner);
        $principleImageUrl = $this->decryptImage($page->principle_image);

        return view('backend.admin.collegePage.editCollegePage', compact(
            'menus', 'page', 'college', 'bannerUrl', 'principleImageUrl'
        ));
    }

    // POST: /admin/dashboard/collegepage/update/{id}
    public function update(Request $request, $id)
    {
        $college = $this->getOperatorCollege();

        $page = CollegePageModel::where('college_id', $college->id)->findOrFail($id);

        $request->validate([
            'page'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'banner'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'principle_image'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'principle_message'  => 'nullable|string',
        ]);

        $encryptedBanner         = $this->encryptImage($request, 'banner', $page->banner);
        $encryptedPrincipleImage = $this->encryptImage($request, 'principle_image', $page->principle_image);

        $page->update([
            'college_id'         => $college->id, // unchanged, always operator's own college
            'page'               => $request->page,
            'description'        => $request->description,
            'banner'             => $encryptedBanner,
            'principle_image'    => $encryptedPrincipleImage,
            'principle_message'  => $request->principle_message,
        ]);

        return redirect('admin/dashboard/collegepage/index')
            ->with('success', 'College page updated successfully!');
    }

    // DELETE: /admin/dashboard/collegepage/destroy/{id}
    public function destroy($id)
    {
        $college = $this->getOperatorCollege();

        CollegePageModel::where('college_id', $college->id)->findOrFail($id)->delete();

        return redirect('admin/dashboard/collegepage/index')
            ->with('success', 'College page deleted successfully!');
    }
}