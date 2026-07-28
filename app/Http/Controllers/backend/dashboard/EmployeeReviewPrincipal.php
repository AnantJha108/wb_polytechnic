<?php
// app/Http/Controllers/backend/dashboard/EmployeeReviewPrincipal.php

namespace App\Http\Controllers\backend\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee as EmployeeModel;
use App\Models\EmployeeLog;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeReviewPrincipal extends Controller
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

    // GET: /admin/dashboard/employeeReviewPrincipal/index
    public function index()
    {
        $menus     = $this->getMenus();
        $collegeId = Auth::user()->college_id;

        $employees = EmployeeModel::where('college_id', $collegeId)->latest()->get();

        return view('backend.admin.employee.principalReviewList', compact('menus', 'employees'));
    }

    // GET: /admin/dashboard/employeeReviewPrincipal/show/{id}
    public function show($id)
    {
        $menus     = $this->getMenus();
        $collegeId = Auth::user()->college_id;

        $employee = EmployeeModel::where('college_id', $collegeId)
            ->with('academicDetails', 'higherStudies', 'principalIncharges', 'deputations')
            ->findOrFail($id);

        $logs = EmployeeLog::where('employee_id', $employee->id)->with('performer')->latest()->get();

        return view('backend.admin.employee.principalReviewShow', compact('menus', 'employee', 'logs'));
    }

    // POST: /admin/dashboard/employeeReviewPrincipal/forward/{id}
    public function forward(Request $request, $id)
    {
        $collegeId = Auth::user()->college_id;

        $employee = EmployeeModel::where('college_id', $collegeId)->findOrFail($id);

        if ($employee->status !== 'forwarded_to_principal') {
            return response()->json(['success' => false, 'message' => 'This record cannot be forwarded right now.'], 422);
        }

        $employee->update(['status' => 'forwarded_to_director']);

        EmployeeLog::create([
            'employee_id'  => $employee->id,
            'action'       => 'forward_to_director',
            'performed_by' => Auth::id(),
            'ip_address'   => $request->ip(),
        ]);

        return response()->json(['success' => true, 'message' => 'Forwarded to Director.']);
    }
}