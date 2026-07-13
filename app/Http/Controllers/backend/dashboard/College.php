<?php

namespace App\Http\Controllers\backend\dashboard;

use App\Http\Controllers\Controller;
use App\Models\College as CollegeModel;
use App\Models\CollegePage;
use App\Models\CollegePage as CollegePageModel;
use App\Models\CollegePageLog;
use App\Models\Master;
use App\Models\Menu;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;


class College extends Controller
{
    public function getMenus()
    {
        $user = Auth::user();

        if (!$user->master_id) {
            return collect();
        }

        $menuIds = DB::table('menu_user_maps')
            ->where('master_id', $user->master_id)
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

    private function generateStrongPassword(int $length = 10): string
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers   = '0123456789';
        $special   = '@$!%*#?&';

        $password  = $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $special[random_int(0, strlen($special) - 1)];

        $allChars = $uppercase . $lowercase . $numbers . $special;
        for ($i = strlen($password); $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        return str_shuffle($password);
    }

    // Helper — decrypt an encrypted "mime|base64" image into a data URI
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
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return null;
        }

        return null;
    }

    // /admin/dashboard/college/index
    public function index()
    {
        $colleges = CollegeModel::all();
        $menus = $this->getMenus();

        foreach ($colleges as $college) {
            $college->logo_url = $this->decryptImage($college->logo);
        }

        return view('backend.admin.college.viewCollege', compact('colleges', 'menus'));
    }

    // GET: /admin/dashboard/college/create
    public function create(Request $request, $id)
    {
        $menus     = $this->getMenus();
        $templates = Template::all();

        return view('backend.admin.college.addCollege', compact('menus', 'templates'));
    }

    public function store(Request $request, $id)
    {
        $menus     = $this->getMenus();
        $templates = Template::all();

        if ($request->isMethod('POST')) {

            $request->validate([
                'name'        => 'required|string|max:255',
                'district'    => 'required|string|max:255',
                'slug'        => ['required', 'string', Rule::unique('colleges', 'slug')->ignore($id)],
                'contact_no'  => 'required|string|max:20',
                'email'       => ['required', 'email', Rule::unique('colleges', 'email')->ignore($id)],
                'address'     => 'required|string',
                'template_id' => 'required|exists:templates,id',
                'status'      => 'required|in:0,1',
                'logo'        =>  'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'email.unique' => 'This email is already registered. Please use a different email.',
                'slug.unique'  => 'This slug is already taken. Please enter a new slug.',
            ]);

            $college = CollegeModel::create([
                'name'        => $request->name,
                'slug'        => $request->slug,
                'district'    => $request->district,
                'contact_no'  => $request->contact_no,
                'email'       => $request->email,
                'address'     => $request->address,
                'template_id' => $request->template_id,
                'status'      => $request->status,
            ]);

            $collegeId = 'COLL1234' . $college->id;

            $encryptedLogo = null;

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');

                $mimeType     = $file->getMimeType();
                $imageContent = file_get_contents($file->getRealPath());
                $base64       = base64_encode($imageContent);

                $encryptedLogo = Crypt::encryptString($mimeType . '|' . $base64);
            }

            $college->update([
                'college_id' => $collegeId,
                'logo'       => $encryptedLogo,
            ]);

            $plainPassword = $this->generateStrongPassword(10);

            $principalRole = Master::where('name', 'principal')->first();

            $user = User::create([
                'college_id' => $college->id,
                'username'   => $college->name,
                'phone'      => $college->contact_no,
                'email'      => $college->email,
                'master_id'  => $principalRole->id ?? 3,
                'password'   => Hash::make($plainPassword),
            ]);

            return redirect('admin/dashboard/college/index')->with([
                'success'          => 'College created successfully!',
                'college_id_shown' => $collegeId,
                'college_password' => $plainPassword,
            ]);
        }
    }

    // GET: /admin/dashboard/college/show/{id}
    public function show($id)
    {
        $menus   = $this->getMenus();
        $college = CollegeModel::findOrFail($id);
        $logoUrl = $this->decryptImage($college->logo);

        return view('backend.admin.college.collegeDetails', compact('menus', 'college', 'logoUrl'));
    }

    // GET: /admin/dashboard/college/edit/{id}
    public function edit($id)
    {
        $menus     = $this->getMenus();
        $templates = Template::all();
        $college   = CollegeModel::findOrFail($id);
        $logoUrl   = $this->decryptImage($college->logo);

        return view('backend.admin.college.editCollege', compact('menus', 'templates', 'college', 'logoUrl'));
    }

    // POST: /admin/dashboard/college/update/{id}
    public function update(Request $request, $id)
    {
        $college = CollegeModel::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'district'    => 'required|string|max:255',
            'slug'        => ['required', 'string', Rule::unique('colleges', 'slug')->ignore($id)],
            'contact_no'  => 'required|string|max:20',
            'email'       => ['required', 'email', Rule::unique('colleges', 'email')->ignore($id)],
            'address'     => 'required|string',
            'template_id' => 'nullable|exists:templates,id',
            'status'      => 'required|in:0,1',
            'logo'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'email.unique' => 'This email is already registered. Please use a different email.',
            'slug.unique'  => 'This slug is already taken. Please enter a new slug.',
        ]);

        $encryptedLogo = $college->logo;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');

            $mimeType     = $file->getMimeType();
            $imageContent = file_get_contents($file->getRealPath());
            $base64       = base64_encode($imageContent);

            $encryptedLogo = Crypt::encryptString($mimeType . '|' . $base64);
        }

        $college->update([
            'name'        => $request->name,
            'slug'        => $request->slug,
            'district'    => $request->district,
            'contact_no'  => $request->contact_no,
            'email'       => $request->email,
            'address'     => $request->address,
            'template_id' => $request->template_id,
            'status'      => $request->status,
            'logo'        => $encryptedLogo,
        ]);

        // ── Sync the linked principal user account with the updated college info ──
        $principalRoleId = Master::where('name', 'principal')->value('id');

        $user = User::where('college_id', $college->id)
            ->where('master_id', $principalRoleId)
            ->first();

        if ($user) {
            $user->update([
                'username' => $request->name,
                'phone'    => $request->contact_no,
                'email'    => $request->email,
            ]);
        }

        return redirect('admin/dashboard/college/index')
            ->with('success', 'College updated successfully!');
    }

    // /admin/dashboard/college/destroy/5
    public function destroy(Request $request, $id)
    {
        $college = CollegeModel::findOrFail($id);

        // Delete every user tied to this college (principal login + all operators)
        User::where('college_id', $college->id)->delete();

        // Clean up related college page content (banner/description/etc.)
        CollegePage::where('college_id', $college->id)->delete();

        $college->delete();

        return redirect()->back()->with('success', 'College and all associated users deleted.');
    }


    // GET: /admin/dashboard/college/collegepagestatus
    public function collegePageStatus()
    {
        $menus     = $this->getMenus();
        $collegeId = Auth::user()->college_id;

        $page = CollegePageModel::where('college_id', $collegeId)->first();

        if ($page) {
            $page->banner_url          = $this->decryptImage($page->banner);
            $page->principle_image_url = $this->decryptImage($page->principle_image);
        }

        $logs = $page ? CollegePageLog::with('performer')
            ->where('college_page_id', $page->id)
            ->orderByDesc('created_at')
            ->get() : collect();

        return view('backend.admin.college.collegePageApproval', compact('menus', 'page', 'logs'));
    }

    // AJAX POST: /admin/dashboard/college/approve/{id}
    public function approve(Request $request, $id)
    {
        $collegeId = Auth::user()->college_id;
        $page = CollegePageModel::where('college_id', $collegeId)->findOrFail($id);

        if ($page->status !== 'forwarded') {
            return response()->json(['success' => false, 'message' => 'Only forwarded pages can be approved.'], 422);
        }

        $page->update(['status' => 'approved']);

        CollegePageLog::create([
            'college_page_id' => $page->id,
            'action'          => 'approve',
            'reason'          => null,
            'performed_by'    => Auth::id(),
            'ip_address'      => $request->ip(),
        ]);

        return response()->json(['success' => true, 'status' => 'approved', 'message' => 'Page approved successfully.']);
    }

    // AJAX POST: /admin/dashboard/college/reject/{id}
    public function reject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:1000']);

        $collegeId = Auth::user()->college_id;
        $page = CollegePageModel::where('college_id', $collegeId)->findOrFail($id);

        if ($page->status !== 'forwarded') {
            return response()->json(['success' => false, 'message' => 'Only forwarded pages can be rejected.'], 422);
        }

        $page->update(['status' => 'rejected', 'reject_reason' => $request->reason]);

        CollegePageLog::create([
            'college_page_id' => $page->id,
            'action'          => 'reject',
            'reason'          => $request->reason,
            'performed_by'    => Auth::id(),
            'ip_address'      => $request->ip(),
        ]);

        return response()->json(['success' => true, 'status' => 'rejected', 'reason' => $request->reason, 'message' => 'Page rejected.']);
    }

    // AJAX POST: /admin/dashboard/college/revert/{id}
    public function revert(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:1000']);

        $collegeId = Auth::user()->college_id;
        $page = CollegePageModel::where('college_id', $collegeId)->findOrFail($id);

        if ($page->status !== 'forwarded') {
            return response()->json(['success' => false, 'message' => 'Only forwarded pages can be reverted.'], 422);
        }

        $page->update(['status' => 'reverted', 'revert_reason' => $request->reason]);

        CollegePageLog::create([
            'college_page_id' => $page->id,
            'action'          => 'revert',
            'reason'          => $request->reason,
            'performed_by'    => Auth::id(),
            'ip_address'      => $request->ip(),
        ]);

        return response()->json(['success' => true, 'status' => 'reverted', 'reason' => $request->reason, 'message' => 'Page reverted to operator.']);
    }
}
