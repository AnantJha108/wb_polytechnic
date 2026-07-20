<?php

namespace App\Http\Controllers\backend\dashboard;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\CollegePage;
use App\Models\Menu;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class CollegeIndexPreview extends Controller
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

    private function guardDirectorOnly()
    {
        $user = Auth::user();

        if (!$user->master || $user->master->name !== 'director') {
            abort(403, 'Only Director can access this page.');
        }
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

    // GET: /admin/dashboard/collegeindexpreview/index
    public function index()
    {
        $this->guardDirectorOnly();

        $menus    = $this->getMenus();
        $colleges = College::orderBy('name')->get();

        return view('backend.admin.collegePreview.list', compact('menus', 'colleges'));
    }

    // GET: /admin/dashboard/collegeIndexPreview/show/{id}
    public function show($id)
    {
        $this->guardDirectorOnly();

        $menus   = $this->getMenus();
        $college = College::findOrFail($id);

        $page = CollegePage::where('college_id', $college->id)
            ->where('page', 'home')
            ->where('status', 'approved')
            ->latest()
            ->first(); // not firstOrFail — we want to handle "no approved page" gracefully

        $bannerUrl = $page ? $this->decryptImage($page->banner) : null;
        $principleImageUrl = $page ? $this->decryptImage($page->principle_image) : null;

        return view('backend.admin.collegePreview.show', compact('menus', 'college', 'page', 'bannerUrl', 'principleImageUrl'));
    }

}
