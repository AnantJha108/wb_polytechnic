<?php
// app/Http/Controllers/backend/dashboard/EmployeeReviewDirector.php

namespace App\Http\Controllers\backend\dashboard;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\Employee as EmployeeModel;
use App\Models\EmployeeLog;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeReviewDirector extends Controller
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

    private function guardDirectorOnly()
    {
        $user = Auth::user();
        if (!$user->master || $user->master->name !== 'director') {
            abort(403, 'Only Director can access this page.');
        }
    }

    // GET: /admin/dashboard/employeeReviewDirector/index
    public function index()
    {
        $this->guardDirectorOnly();
        $menus = $this->getMenus();

        $colleges = College::orderBy('name')->get();

        return view('backend.admin.employee.directorCollegeList', compact('menus', 'colleges'));
    }

    // GET: /admin/dashboard/employeeReviewDirector/college/{collegeId}
    public function college($collegeId)
    {
        $this->guardDirectorOnly();
        $menus   = $this->getMenus();
        $college = College::findOrFail($collegeId);

        $employees = EmployeeModel::where('college_id', $college->id)->latest()->get();

        return view('backend.admin.employee.directorReviewList', compact('menus', 'college', 'employees'));
    }

    // GET: /admin/dashboard/employeeReviewDirector/show/{id}
    public function show($id)
    {
        $this->guardDirectorOnly();
        $menus = $this->getMenus();

        $employee = EmployeeModel::with('academicDetails', 'higherStudies', 'principalIncharges', 'deputations', 'college')
            ->findOrFail($id);

        $logs = EmployeeLog::where('employee_id', $employee->id)->with('performer')->latest()->get();

        return view('backend.admin.employee.directorReviewShow', compact('menus', 'employee', 'logs'));
    }

    // POST: /admin/dashboard/employeeReviewDirector/approve/{id}
    public function approve(Request $request, $id)
    {
        $this->guardDirectorOnly();
        $employee = EmployeeModel::findOrFail($id);

        if ($employee->status !== 'forwarded_to_director') {
            return response()->json(['success' => false, 'message' => 'Only records forwarded to Director can be approved.'], 422);
        }

        $employee->update(['status' => 'approved', 'reject_reason' => null, 'revert_reason' => null]);

        EmployeeLog::create([
            'employee_id'  => $employee->id,
            'action'       => 'approve',
            'performed_by' => Auth::id(),
            'ip_address'   => $request->ip(),
        ]);

        return response()->json(['success' => true, 'message' => 'Employee record approved.']);
    }

    // POST: /admin/dashboard/employeeReviewDirector/reject/{id}
    public function reject(Request $request, $id)
    {
        $this->guardDirectorOnly();
        $request->validate(['reason' => 'required|string|min:5|max:1000']);

        $employee = EmployeeModel::findOrFail($id);

        if ($employee->status !== 'forwarded_to_director') {
            return response()->json(['success' => false, 'message' => 'Only records forwarded to Director can be rejected.'], 422);
        }

        $employee->update(['status' => 'rejected', 'reject_reason' => $request->reason]);

        EmployeeLog::create([
            'employee_id'  => $employee->id,
            'action'       => 'reject',
            'reason'       => $request->reason,
            'performed_by' => Auth::id(),
            'ip_address'   => $request->ip(),
        ]);

        return response()->json(['success' => true, 'message' => 'Employee record rejected.']);
    }

    // POST: /admin/dashboard/employeeReviewDirector/revert/{id}
    public function revert(Request $request, $id)
    {
        $this->guardDirectorOnly();
        $request->validate(['reason' => 'required|string|min:5|max:1000']);

        $employee = EmployeeModel::findOrFail($id);

        if ($employee->status !== 'forwarded_to_director') {
            return response()->json(['success' => false, 'message' => 'Only records forwarded to Director can be reverted.'], 422);
        }

        $employee->update(['status' => 'reverted', 'revert_reason' => $request->reason]);

        EmployeeLog::create([
            'employee_id'  => $employee->id,
            'action'       => 'revert',
            'reason'       => $request->reason,
            'performed_by' => Auth::id(),
            'ip_address'   => $request->ip(),
        ]);

        return response()->json(['success' => true, 'message' => 'Reverted back to Operator for edits.']);
    }

    // DELETE: /admin/dashboard/employeeReviewDirector/destroy/{id}
    public function destroy($id)
    {
        $this->guardDirectorOnly();
        $employee = EmployeeModel::findOrFail($id);
        $employee->delete();

        return redirect()->back()->with('success', 'Employee record deleted.');
    }
}