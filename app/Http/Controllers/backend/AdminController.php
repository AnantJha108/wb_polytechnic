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
    public function dashboard(Request $request, $slug = null, $id = null)
    {
        $user = Auth::user();

        //  Step 1: Get allowed menu IDs
        $menuIds = DB::table('menu_user_maps')
            ->where('user_id', $user->id)
            ->pluck('menu_id')
            ->toArray();

        //  If no access → empty sidebar
        if (empty($menuIds)) {
            $menus = collect();
        } else {

            // Step 2: Get allowed CHILD menus
            $childMenus = Menu::whereIn('id', $menuIds)
                ->where('menu_id', '!=', 0)
                ->get()
                ->groupBy('menu_id');

            //  Step 3: Get ONLY parents that have children
            $menus = Menu::where('menu_id', 0)
                ->get()
                ->filter(function ($parent) use ($childMenus) {
                    return isset($childMenus[$parent->id]);
                })
                ->map(function ($parent) use ($childMenus) {
                    $parent->children = $childMenus[$parent->id];
                    return $parent;
                });
        }
        return view('backend.admin.dashboard', compact('user','menus'));
    }

    public function handle(Request $request, $slug = null, $id = null)
    {

        $user = Auth::user();
        
        $menuIds = DB::table('menu_user_maps')
            ->where('user_id', $user->id)
            ->pluck('menu_id')
            ->toArray();

        if (empty($menuIds)) {
            $menus = collect();
        } else {

            $childMenus = Menu::whereIn('id', $menuIds)
                ->where('menu_id', '!=', 0)
                ->get()
                ->groupBy('menu_id');

            $menus = Menu::where('menu_id', 0)
                ->get()
                ->filter(function ($parent) use ($childMenus) {
                    return isset($childMenus[$parent->id]);
                })
                ->map(function ($parent) use ($childMenus) {
                    $parent->children = $childMenus[$parent->id];
                    return $parent;
                });
        }

        if (!$slug) {
            return view('backend.admin.dashboard', compact('menus'));
        }

        switch ($slug) {

            case 'add-employee':

                if ($request->isMethod('post')) {
                    $request->validate([
                        'name' => 'required',
                        'email' => 'required|email',
                        'phone' => 'required'
                    ]);

                    Employee::create($request->all());

                    return redirect('/admin/view-employee')
                        ->with('success', 'Employee Added Successfully');
                }

                return view('backend.admin.addEmployee', compact('menus'));

            case 'view-employee':
                $employees = Employee::all();
                return view('backend.admin.viewEmployee', compact('employees', 'menus'));

            case 'employee':
                $employee = Employee::findOrFail($id);
                return view('backend.admin.employeeDetails', compact('employee', 'menus'));

            default:
                abort(404);
        }
    }
}
