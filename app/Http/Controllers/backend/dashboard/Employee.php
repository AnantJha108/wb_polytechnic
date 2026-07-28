<?php

namespace App\Http\Controllers\backend\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\College as CollegeModel;
use App\Models\District;
use App\Models\Employee as EmployeeModel;
use App\Models\EmployeeAcademicDetail;
use App\Models\EmployeeHigherStudy;
use App\Models\EmployeePrincipalIncharge;
use App\Models\EmployeeDeputation;
use App\Models\EmployeeLog;
use App\Models\Menu;
use App\Models\State;
use App\Models\SubDivision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class Employee extends Controller
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

    private function getOperatorCollege()
    {
        $user = Auth::user();
        if (!$user->college_id) abort(403, 'No college is assigned to your account.');
        return CollegeModel::findOrFail($user->college_id);
    }


    private function storeEncryptedPhoto($file): string
    {
        $raw       = file_get_contents($file->getRealPath());
        $encrypted = Crypt::encrypt($raw);

        $filename = 'employee_photos/' . Str::uuid() . '.enc';
        Storage::disk('local')->put($filename, $encrypted);

        unset($raw, $encrypted);

        return $filename;
    }

    // GET: /admin/dashboard/employee/photo/{id}
    public function photo($id)
    {
        $college = $this->getOperatorCollege();
        $employee = EmployeeModel::where('college_id', $college->id)->findOrFail($id);

        if (!$employee->photo_path) {
            abort(404);
        }

        $encrypted = Storage::disk('local')->get($employee->photo_path);
        $raw       = Crypt::decrypt($encrypted);

        return response($raw, 200)->header('Content-Type', 'image/jpeg');
    }


    // GET: /admin/dashboard/employee/index
    public function index()
    {
        $menus   = $this->getMenus();
        $college = $this->getOperatorCollege();

        $employees = EmployeeModel::where('college_id', $college->id)->latest()->get();

        return view('backend.admin.employee.viewEmployee', compact('menus', 'employees'));
    }

    // GET: /admin/dashboard/employee/show/{id}
    public function show($id)
    {
        $menus   = $this->getMenus();
        $college = $this->getOperatorCollege();

        $employee = EmployeeModel::where('college_id', $college->id)
            ->with('academicDetails', 'higherStudies', 'principalIncharges', 'deputations')
            ->findOrFail($id);

        return view('backend.admin.employee.employeeDetails', compact('menus', 'employee'));
    }

    // GET: /admin/dashboard/employee/create
    public function create()
    {
        $menus   = $this->getMenus();
        $college = $this->getOperatorCollege();

        return view('backend.admin.employee.addEmployee', compact('menus', 'college'));
    }

    private function validationRules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'father_first_name' => ['required', 'string', 'max:100'],
            'father_last_name' => ['required', 'string', 'max:100'],
            'employee_id' => ['required', 'string', 'max:50'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'string'],
            'religion' => ['required', 'string'],
            'caste' => ['required', 'string'],
            'pwd_status' => ['required', 'in:yes,no'],
            'date_of_initial_joining' => ['required', 'date'],
            'email' => ['nullable', 'email'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg', 'max:300'], // 300 KB
        ];
    }

    // POST: /admin/dashboard/employee/store
    public function store(Request $request)
    {
        $college = $this->getOperatorCollege();

        $request->validate($this->validationRules());

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $this->storeEncryptedPhoto($request->file('photo'));
        }

        $employee = EmployeeModel::create(array_merge(
            $request->except([
                'photo',
                '_token',
                'academic',
                'qip_rows',
                'non_qip_rows',
                'principal_incharge',
                'principal_incharge_rows',
                'deputation',
                'deputation_rows',
            ]),
            [
                'college_id'  => $college->id,
                'photo_path'  => $photoPath,
                'status'      => 'draft',
            ]
        ));

        $this->saveRepeatingSections($request, $employee);

        return redirect('admin/dashboard/employee/index')->with('success', 'Employee saved successfully!');
    }

    // Shared by store() and update() — persists Academic (fixed 4) + all dynamic (max 4) sections
    private function saveRepeatingSections(Request $request, EmployeeModel $employee)
    {
        // Academic details — always 4 fixed levels
        if ($request->has('academic')) {
            foreach ($request->input('academic') as $level => $row) {
                if (empty($row['qualification']) && empty($row['discipline_trade']) && empty($row['passing_year'])) {
                    continue;
                }
                EmployeeAcademicDetail::updateOrCreate(
                    ['employee_id' => $employee->id, 'level' => $level],
                    [
                        'qualification'    => $row['qualification'] ?? null,
                        'discipline_trade' => $row['discipline_trade'] ?? null,
                        'passing_year'     => $row['passing_year'] ?? null,
                    ]
                );
            }
        }

        // Higher Study QIP (max 4)
        if ($request->boolean('higher_study_qip') && $request->has('qip_rows')) {
            foreach (array_slice($request->input('qip_rows'), 0, 4) as $row) {
                EmployeeHigherStudy::create([
                    'employee_id'    => $employee->id,
                    'type'           => 'qip',
                    'session'        => $row['session'] ?? null,
                    'course'         => $row['course'] ?? null,
                    'institute_name' => $row['institute_name'] ?? null,
                    'start_date'     => $row['start_date'] ?? null,
                    'end_date'       => $row['end_date'] ?? null,
                ]);
            }
        }

        // Higher Study Non-QIP (max 4)
        if ($request->boolean('higher_study_non_qip') && $request->has('non_qip_rows')) {
            foreach (array_slice($request->input('non_qip_rows'), 0, 4) as $row) {
                EmployeeHigherStudy::create([
                    'employee_id'    => $employee->id,
                    'type'           => 'non_qip',
                    'session'        => $row['session'] ?? null,
                    'course'         => $row['course'] ?? null,
                    'institute_name' => $row['institute_name'] ?? null,
                    'start_date'     => $row['start_date'] ?? null,
                    'end_date'       => $row['end_date'] ?? null,
                ]);
            }
        }

        if ($request->has('posting_rows')) {
            foreach (array_slice($request->input('posting_rows'), 0, 4) as $postingData) {

                if (($postingData['principal_incharge'] ?? 'no') === 'yes' && !empty($postingData['principal_incharge_rows'])) {
                    foreach (array_slice($postingData['principal_incharge_rows'], 0, 4) as $row) {
                        if (empty($row['polytechnic_name']) && empty($row['from_date']) && empty($row['to_date'])) continue;

                        EmployeePrincipalIncharge::create([
                            'employee_id'      => $employee->id,
                            'polytechnic_name' => $row['polytechnic_name'] ?? null,
                            'from_date'        => $row['from_date'] ?? null,
                            'to_date'          => $row['to_date'] ?? null,
                        ]);
                    }
                }

                if (($postingData['deputation'] ?? 'no') === 'yes' && !empty($postingData['deputation_rows'])) {
                    foreach (array_slice($postingData['deputation_rows'], 0, 4) as $row) {
                        if (empty($row['office_name']) && empty($row['designation'])) continue;

                        EmployeeDeputation::create([
                            'employee_id' => $employee->id,
                            'office_name' => $row['office_name'] ?? null,
                            'designation' => $row['designation'] ?? null,
                            'from_date'   => $row['from_date'] ?? null,
                            'to_date'     => $row['to_date'] ?? null,
                        ]);
                    }
                }
            }
        }
    }

    // GET: /admin/dashboard/employee/edit/{id}
    public function edit($id)
    {
        $menus   = $this->getMenus();
        $college = $this->getOperatorCollege();

        $employee = EmployeeModel::where('college_id', $college->id)
            ->with('academicDetails', 'higherStudies', 'principalIncharges', 'deputations')
            ->findOrFail($id);

        if (!in_array($employee->status, ['draft', 'reverted'])) {
            abort(403, 'This record cannot be edited in its current status.');
        }

        return view('backend.admin.employee.editEmployee', compact('menus', 'employee', 'college'));
    }

    // POST: /admin/dashboard/employee/update/{id}
    public function update(Request $request, $id)
    {
        $college = $this->getOperatorCollege();

        $employee = EmployeeModel::where('college_id', $college->id)->findOrFail($id);

        if (!in_array($employee->status, ['draft', 'reverted'])) {
            abort(403, 'This record cannot be edited in its current status.');
        }

        $request->validate($this->validationRules());

        $photoPath = $employee->photo_path;
        if ($request->hasFile('photo')) {
            if ($photoPath) {
                Storage::disk('local')->delete($photoPath);
            }
            $photoPath = $this->storeEncryptedPhoto($request->file('photo'));
        }

        $employee->update(array_merge(
            $request->except([
                'photo',
                '_token',
                '_method',
                'academic',
                'qip_rows',
                'non_qip_rows',
                'principal_incharge',
                'principal_incharge_rows',
                'deputation',
                'deputation_rows',
            ]),
            [
                'photo_path' => $photoPath,
                'status'     => 'draft',
            ]
        ));

        // Replace all dynamic rows on edit (simplest, avoids merge conflicts)
        $employee->higherStudies()->delete();
        $employee->principalIncharges()->delete();
        $employee->deputations()->delete();

        $this->saveRepeatingSections($request, $employee);

        return redirect('admin/dashboard/employee/index')->with('success', 'Employee updated successfully!');
    }

    // DELETE: /admin/dashboard/employee/destroy/{id}
    public function destroy($id)
    {
        $college = $this->getOperatorCollege();

        $employee = EmployeeModel::where('college_id', $college->id)->findOrFail($id);

        if (!in_array($employee->status, ['draft', 'reverted', 'rejected'])) {
            abort(403, 'This record cannot be deleted in its current status.');
        }

        if ($employee->photo_path) {
            Storage::disk('public')->delete($employee->photo_path);
        }

        $employee->delete();

        return redirect('admin/dashboard/employee/index')->with('success', 'Employee deleted successfully!');
    }

    // AJAX POST: /admin/dashboard/employee/forward/{id}
    public function forward(Request $request, $id)
    {
        $college = $this->getOperatorCollege();

        $employee = EmployeeModel::where('college_id', $college->id)->findOrFail($id);

        if (!in_array($employee->status, ['draft', 'reverted'])) {
            return response()->json(['success' => false, 'message' => 'This record cannot be forwarded right now.'], 422);
        }

        $employee->update(['status' => 'forwarded_to_principal']);

        EmployeeLog::create([
            'employee_id'  => $employee->id,
            'action'       => 'forward_to_principal',
            'performed_by' => Auth::id(),
            'ip_address'   => $request->ip(),
        ]);

        return response()->json(['success' => true, 'message' => 'Forwarded to Principal.']);
    }

    // GET: /admin/dashboard/employee/getStates
    public function getStates()
    {
        return response()->json(State::orderBy('name')->get(['id', 'name']));
    }

    // GET: /admin/dashboard/employee/getDistricts/{stateId}
    public function getDistricts($stateId)
    {
        return response()->json(District::where('state_id', $stateId)->orderBy('name')->get(['id', 'name']));
    }

    // GET: /admin/dashboard/employee/getSubDivisions/{districtId}
    public function getSubDivisions($districtId)
    {
        return response()->json(SubDivision::where('district_id', $districtId)->orderBy('name')->get(['id', 'name']));
    }

    // GET: /admin/dashboard/employee/getBlocks/{subDivisionId}
    public function getBlocks($subDivisionId)
    {
        return response()->json(Block::where('sub_division_id', $subDivisionId)->orderBy('name')->get(['id', 'name']));
    }
}
