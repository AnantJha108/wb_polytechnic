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
