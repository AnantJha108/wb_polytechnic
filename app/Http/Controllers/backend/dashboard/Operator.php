<?php

namespace App\Http\Controllers\backend\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Master;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Operator extends Controller
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

    private function operatorRoleId()
    {
        return Master::where('name', 'operator')->value('id') ?? 2;
    }

    /**
     * Generates a random password that always satisfies:
     * min 8 chars, at least 1 uppercase, 1 lowercase, 1 number, 1 special char.
     */
    private function generateStrongPassword(int $length = 10): string
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers   = '0123456789';
        $special   = '@$!%*#?&';

        // Guarantee at least one of each required type
        $password  = $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $special[random_int(0, strlen($special) - 1)];

        // Fill the rest randomly from all allowed characters
        $allChars = $uppercase . $lowercase . $numbers . $special;
        for ($i = strlen($password); $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        // Shuffle so guaranteed chars aren't always in the same position
        return str_shuffle($password);
    }

    // GET: /admin/dashboard/operator/index
    public function index()
    {
        $menus     = $this->getMenus();
        $collegeId = Auth::user()->college_id;

        $operators = User::where('college_id', $collegeId)
            ->where('master_id', $this->operatorRoleId())
            ->get();

        return view('backend.admin.operator.viewOperator', compact('menus', 'operators'));
    }

    // GET: /admin/dashboard/operator/show/{id}
    public function show($id)
    {
        $menus     = $this->getMenus();
        $collegeId = Auth::user()->college_id;

        $operator = User::with('master')
            ->where('college_id', $collegeId)
            ->where('master_id', $this->operatorRoleId())
            ->findOrFail($id);

        return view('backend.admin.operator.operatorDetails', compact('menus', 'operator'));
    }

    // GET: /admin/dashboard/operator/create
    public function create()
    {
        $menus = $this->getMenus();
        return view('backend.admin.operator.addOperator', compact('menus'));
    }

    // POST: /admin/dashboard/operator/store
    public function store(Request $request)
    {
        $collegeId = Auth::user()->college_id;

        $request->validate([
            'username' => 'required|string|max:255',
            'phone'    => 'required|string|max:20',
            'email'    => ['required', 'email', Rule::unique('users', 'email')],
        ]);

        // Generate a strong random password (guarantees all validation rules pass)
        $plainPassword = $this->generateStrongPassword(10);

        $operator = User::create([
            'college_id' => $collegeId,
            'username'   => $request->username,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'master_id'  => $this->operatorRoleId(),
            'password'   => Hash::make($plainPassword),
        ]);

        return redirect('admin/dashboard/operator/index')->with([
            'success'           => 'Operator created successfully!',
            'operator_email'    => $operator->email,
            'operator_password' => $plainPassword,
        ]);
    }

    // GET: /admin/dashboard/operator/edit/{id}
    public function edit($id)
    {
        $menus     = $this->getMenus();
        $collegeId = Auth::user()->college_id;

        $operator = User::where('college_id', $collegeId)
            ->where('master_id', $this->operatorRoleId())
            ->findOrFail($id);

        return view('backend.admin.operator.editOperator', compact('menus', 'operator'));
    }

    // POST: /admin/dashboard/operator/update/{id}
    public function update(Request $request, $id)
    {
        $collegeId = Auth::user()->college_id;

        $operator = User::where('college_id', $collegeId)
            ->where('master_id', $this->operatorRoleId())
            ->findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255',
            'phone'    => 'required|string|max:20',
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($operator->id)],
        ]);

        $operator->update([
            'username' => $request->username,
            'phone'    => $request->phone,
            'email'    => $request->email,
        ]);

        return redirect('admin/dashboard/operator/index')
            ->with('success', 'Operator updated successfully!');
    }

    // DELETE: /admin/dashboard/operator/destroy/{id}
    public function destroy($id)
    {
        $collegeId = Auth::user()->college_id;

        User::where('college_id', $collegeId)
            ->where('master_id', $this->operatorRoleId())
            ->findOrFail($id)
            ->delete();

        return redirect('admin/dashboard/operator/index')
            ->with('success', 'Operator deleted successfully!');
    }
}
