<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function getMenus()
    {
        $user = Auth::user();

        if (!$user->master_id) {
            return collect();
        }

        $menuIds = DB::table('menu_user_maps')
            ->where('master_id', $user->master_id)   // ← changed from user_id
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


    public function dashboard(Request $request)
    {
        $user = Auth::user();

        $menus = $this->getMenus();

        return view(
            'backend.admin.dashboard',
            compact('user', 'menus')
        );
    }
}
