<?php

namespace App\Http\Controllers\backend\dashboard;

use App\Http\Controllers\Controller;
use App\Models\College as CollegeModel;
use App\Models\CollegePage as CollegePageModel;
use App\Models\CollegePageLog;
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
        if (!$user->master_id) return collect();

        $menuIds = DB::table('menu_user_maps')->where('master_id', $user->master_id)->pluck('menu_id')->toArray();
        if (empty($menuIds)) return collect();

        $childMenus = Menu::whereIn('id', $menuIds)->where('menu_id', '!=', 0)->get()->groupBy('menu_id');

        return Menu::where('menu_id', 0)->get()
            ->filter(fn($parent) => isset($childMenus[$parent->id]))
            ->map(function ($parent) use ($childMenus) {
                $parent->children = $childMenus[$parent->id];
                return $parent;
            });
    }

    private function decryptImage($encrypted)
    {
        if (!$encrypted) return null;
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

    private function encryptImage(Request $request, string $field, $oldValue = null, int $maxWidth = 800, int $quality = 70)
    {
        if (!$request->hasFile($field)) {
            return $oldValue;
        }

        $file = $request->file($field);
        $path = $file->getRealPath();

        [$width, $height, $type] = getimagesize($path);

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

            if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_GIF) {
                imagecolortransparent($resized, imagecolorallocatealpha($resized, 0, 0, 0, 127));
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }

            imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($source);
            $source = $resized;
        }

        // Compress to JPEG in memory
        ob_start();
        imagejpeg($source, null, $quality);
        $compressedData = ob_get_clean();
        imagedestroy($source);

        $base64 = base64_encode($compressedData);

        return Crypt::encryptString('image/jpeg|' . $base64);
    }

    private function getOperatorCollege()
    {
        $user = Auth::user();
        if (!$user->college_id) abort(403, 'No college is assigned to your account.');
        return CollegeModel::findOrFail($user->college_id);
    }

    // GET: /admin/dashboard/collegepage/index
    public function index()
    {
        $menus   = $this->getMenus();
        $college = $this->getOperatorCollege();

        $pages = CollegePageModel::where('college_id', $college->id)->latest()->get();

        foreach ($pages as $page) {
            $page->banner_url          = $this->decryptImage($page->banner);
            $page->principle_image_url = $this->decryptImage($page->principle_image);
        }

        $latestId = $pages->first()->id ?? null;

        return view('backend.admin.collegePage.viewCollegePage', compact('menus', 'pages', 'latestId'));
    }

    // GET: /admin/dashboard/collegepage/show/{id}
    public function show($id)
    {
        $menus   = $this->getMenus();
        $college = $this->getOperatorCollege();
        $page = CollegePageModel::where('college_id', $college->id)->findOrFail($id);

        $bannerUrl         = $this->decryptImage($page->banner);
        $principleImageUrl = $this->decryptImage($page->principle_image);

        return view('backend.admin.collegePage.collegePageDetails', compact('menus', 'page', 'bannerUrl', 'principleImageUrl'));
    }

    // GET: /admin/dashboard/collegepage/create
    public function create()
    {
        $menus   = $this->getMenus();
        $college = $this->getOperatorCollege();

        $latest = CollegePageModel::where('college_id', $college->id)->latest()->first();

        // Block access if the latest page exists and its status is not "rejected"
        if ($latest && $latest->status !== 'rejected') {
            return redirect('admin/dashboard/collegepage/index')
                ->with('error', 'The page is already inserted and available.');
        }

        return view('backend.admin.collegePage.addCollegePage', compact('menus', 'college'));
    }

    // POST: /admin/dashboard/collegepage/store
    public function store(Request $request)
    {
        $college = $this->getOperatorCollege();

        $request->validate(
            [
                'page' => ['required', 'string', 'max:100', 'in:home,about,contact'],
                'description' => ['required', 'string', 'min:20', 'max:10000'],
                'banner' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
                'principle_image' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
                'principle_message' => ['required', 'string', 'min:20', 'max:5000'],
            ],
            [
                'page.required' => 'Please select a page.',
                'page.in' => 'Invalid page selected.',
                'description.required' => 'Description is required.',
                'description.min' => 'Description must contain at least 20 characters.',
                'description.max' => 'Description cannot exceed 10000 characters.',
                'banner.required' => 'Please upload a banner image.',
                'banner.image' => 'Banner must be an image.',
                'banner.mimes' => 'Banner must be jpeg, jpg, png or webp.',
                'banner.max' => 'Banner image size must not exceed 2 MB.',
                'principle_image.required' => 'Please upload the Principal image.',
                'principle_image.image' => 'Principal image must be an image.',
                'principle_image.mimes' => 'Principal image must be jpeg, jpg, png or webp.',
                'principle_image.max' => 'Principal image size must not exceed 2 MB.',
                'principle_message.required' => 'Principal message is required.',
                'principle_message.min' => 'Principal message should contain at least 20 characters.',
                'principle_message.max' => 'Principal message cannot exceed 5000 characters.',
            ]
        );

        $latest = CollegePageModel::where('college_id', $college->id)->latest()->first();

        if ($latest && $latest->status !== 'rejected') {
            return redirect('admin/dashboard/collegepage/index')
                ->with('error', 'The page is already inserted and available.');
        }

        $encryptedBanner         = $this->encryptImage($request, 'banner');
        $encryptedPrincipleImage = $this->encryptImage($request, 'principle_image');

        // Always create a fresh row — old rejected row stays as history, not overwritten
        CollegePageModel::create([
            'college_id'         => $college->id,
            'page'               => $request->page,
            'description'        => $request->description,
            'banner'             => $encryptedBanner,
            'principle_image'    => $encryptedPrincipleImage,
            'principle_message'  => $request->principle_message,
            'status'             => 'draft',
        ]);

        return redirect('admin/dashboard/collegepage/index')->with('success', 'College page saved successfully!');
    }

    // GET: /admin/dashboard/collegepage/edit/{id}
    public function edit($id)
    {
        $menus   = $this->getMenus();
        $college = $this->getOperatorCollege();
        $page = CollegePageModel::where('college_id', $college->id)->findOrFail($id);

        if (!in_array($page->status, ['draft', 'reverted'])) {
            abort(403, 'This page cannot be edited in its current status.');
        }

        $bannerUrl         = $this->decryptImage($page->banner);
        $principleImageUrl = $this->decryptImage($page->principle_image);

        return view('backend.admin.collegePage.editCollegePage', compact('menus', 'page', 'college', 'bannerUrl', 'principleImageUrl'));
    }

    // POST: /admin/dashboard/collegepage/update/{id}
    public function update(Request $request, $id)
    {
        $college = $this->getOperatorCollege();
        $page = CollegePageModel::where('college_id', $college->id)->findOrFail($id);

        if (!in_array($page->status, ['draft', 'reverted'])) {
            abort(403, 'This page cannot be edited in its current status.');
        }

        $request->validate(
            [
                'page' => ['required', 'string', 'max:100', 'in:home,about,contact'],
                'description' => ['required', 'string', 'min:20', 'max:10000'],
                'banner' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
                'principle_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
                'principle_message' => ['required', 'string', 'min:20', 'max:5000'],
            ],
            [
                'page.required' => 'Please select a page.',
                'page.in' => 'Invalid page selected.',
                'description.required' => 'Description is required.',
                'description.min' => 'Description must contain at least 20 characters.',
                'description.max' => 'Description cannot exceed 10000 characters.',
                'banner.image' => 'Banner must be an image.',
                'banner.mimes' => 'Banner must be jpeg, jpg, png or webp.',
                'banner.max' => 'Banner image size must not exceed 2 MB.',
                'principle_image.image' => 'Principal image must be an image.',
                'principle_image.mimes' => 'Principal image must be jpeg, jpg, png or webp.',
                'principle_image.max' => 'Principal image size must not exceed 2 MB.',
                'principle_message.required' => 'Principal message is required.',
                'principle_message.min' => 'Principal message should contain at least 20 characters.',
                'principle_message.max' => 'Principal message cannot exceed 5000 characters.',
            ]
        );

        $encryptedBanner         = $this->encryptImage($request, 'banner', $page->banner);
        $encryptedPrincipleImage = $this->encryptImage($request, 'principle_image', $page->principle_image);

        $page->update([
            'page' => $request->page,
            'description' => $request->description,
            'banner' => $encryptedBanner,
            'principle_image' => $encryptedPrincipleImage,
            'principle_message' => $request->principle_message,
            'status' => 'draft', // reverted → draft after edit
        ]);

        return redirect('admin/dashboard/collegepage/index')->with('success', 'College page updated successfully!');
    }

    // DELETE: /admin/dashboard/collegepage/destroy/{id}
    public function destroy($id)
    {
        $college = $this->getOperatorCollege();
        $page = CollegePageModel::where('college_id', $college->id)->findOrFail($id);

        if (!in_array($page->status, ['draft', 'reverted', 'rejected'])) {
            abort(403, 'This page cannot be deleted in its current status.');
        }

        $page->delete();
        return redirect('admin/dashboard/collegepage/index')->with('success', 'College page deleted successfully!');
    }

    // AJAX POST: /admin/dashboard/collegepage/forward/{id}
    public function forward(Request $request, $id)
    {
        $college = $this->getOperatorCollege();
        $page = CollegePageModel::where('college_id', $college->id)->findOrFail($id);

        if (!in_array($page->status, ['draft', 'reverted'])) {
            return response()->json(['success' => false, 'message' => 'This page cannot be forwarded right now.'], 422);
        }

        $page->update(['status' => 'forwarded']);

        CollegePageLog::create([
            'college_page_id' => $page->id,
            'action'          => 'forward',
            'reason'          => null,
            'performed_by'    => Auth::id(),
            'ip_address'      => $request->ip(),
        ]);

        return response()->json(['success' => true, 'status' => 'forwarded', 'message' => 'Page forwarded for approval.']);
    }
}
