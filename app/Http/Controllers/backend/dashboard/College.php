<?php

namespace App\Http\Controllers\backend\dashboard;

use App\Http\Controllers\Controller;
use App\Models\College as CollegeModel;
use App\Models\Menu;
use App\Models\Template;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class College extends Controller
{
    public function getMenus()
    {
        $user = Auth::user();

        //  Step 1: Get allowed menu IDs
        $menuIds = DB::table('menu_user_maps')
            ->where('user_id', $user->id)
            ->pluck('menu_id')
            ->toArray();

        //  If no access → empty sidebar
        if (empty($menuIds)) {
            return collect();
        }

        $childMenus = Menu::whereIn('id', $menuIds)
            ->where('menu_id', '!=', 0)
            ->get()
            ->groupBy('menu_id');

        return Menu::where('menu_id', 0)
            ->get()
            ->filter(function ($parent) use ($childMenus) {
                return isset($childMenus[$parent->id]);
            })
            ->map(function ($parent) use ($childMenus) {
                $parent->children = $childMenus[$parent->id];
                return $parent;
            });
    }

    // /admin/dashboard/college/index
    public function index()
    {
        $colleges = CollegeModel::all();
        $menus = $this->getMenus();
        foreach ($colleges as $college) {
            $college->logo_url = null;

            if ($college->logo) {
                try {
                    $decrypted = Crypt::decryptString($college->logo);
                    $parts = explode('|', $decrypted, 2);

                    if (count($parts) === 2) {
                        [$mimeType, $imageData] = $parts;
                        $college->logo_url = "data:{$mimeType};base64,{$imageData}";
                    }
                } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                    $college->logo_url = null;
                }
            }
        }
        return view('backend.admin.college.viewCollege', compact('colleges', 'menus'));
    }

    // /admin/dashboard/college/store  (GET shows form, POST saves)
    public function create(Request $request, $id)
    {

        $menus     = $this->getMenus();
        $templates = Template::all(); // for the dropdown

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

            // ── Step 1: Save college WITHOUT logo and college_id first ──
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

            // ── Step 2: Generate college_id using primary key ──
            // Format: COLL1234 + id  → e.g. id=1 → "COLL12341"
            $collegeId = 'COLL1234' . $college->id;

            // ── Step 3: Handle logo upload + encrypt ──
            $encryptedLogo = null;

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');

                $mimeType     = $file->getMimeType();               // e.g. image/png
                $imageContent = file_get_contents($file->getRealPath());
                $base64       = base64_encode($imageContent);

                $encryptedLogo = Crypt::encryptString($mimeType . '|' . $base64);
            }


            // ── Step 4: Update college with college_id and logo ──
            $college->update([
                'college_id' => $collegeId,
                'logo'       => $encryptedLogo,
            ]);

            return redirect()->back()->with('success', 'College added successfully! ID: ' . $collegeId);
        }

        // GET — show the form
        return view('backend.admin.college.addCollege', compact('menus', 'templates'));
    }


    // GET: /admin/dashboard/college/show/{id}
    public function show($id)
    {
        $menus   = $this->getMenus();
        $college = CollegeModel::findOrFail($id);

        // Decrypt logo for display
        $logoUrl = null;
        if ($college->logo) {
            try {
                $decrypted = Crypt::decryptString($college->logo);
                $parts = explode('|', $decrypted, 2);
                if (count($parts) === 2) {
                    [$mimeType, $imageData] = $parts;
                    $logoUrl = "data:{$mimeType};base64,{$imageData}";
                }
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                $logoUrl = null;
            }
        }

        return view('backend.admin.college.collegeDetails', compact('menus', 'college', 'logoUrl'));
    }


    // GET: /admin/dashboard/college/edit/{id}
    public function edit($id)
    {
        $menus     = $this->getMenus();
        $templates = Template::all();
        $college   = CollegeModel::findOrFail($id);

        // Decrypt logo for preview
        $logoUrl = null;
        if ($college->logo) {
            try {
                $decrypted = Crypt::decryptString($college->logo);
                $parts = explode('|', $decrypted, 2);
                if (count($parts) === 2) {
                    [$mimeType, $imageData] = $parts;
                    $logoUrl = "data:{$mimeType};base64,{$imageData}";
                }
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                $logoUrl = null;
            }
        }

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

        // Keep old logo unless a new one is uploaded
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

        return redirect('admin/dashboard/college/index')
            ->with('success', 'College updated successfully!');
    }

    // /admin/dashboard/college/destroy/5
    public function destroy(Request $request, $id)
    {
        CollegeModel::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'College deleted.');
    }
}
