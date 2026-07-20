<?php

namespace App\Http\Controllers\backend;

use Captcha;
use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function refreshCaptcha()
    {
        return response()->json(['captcha' => Captcha::src()]);
    }

    private function getMenus()
    {
        $user = Auth::user();
        if (!$user->master_id) return collect();

        $menuIds = DB::table('menu_user_maps')
            ->where('master_id', $user->master_id)
            ->pluck('menu_id')
            ->toArray();

        if (empty($menuIds)) return collect();

        $childMenus = Menu::whereIn('id', $menuIds)
            ->where('menu_id', '!=', 0)
            ->get()
            ->groupBy('menu_id');

        return Menu::where('menu_id', 0)->get()
            ->filter(fn($parent) => isset($childMenus[$parent->id]))
            ->map(function ($parent) use ($childMenus) {
                $parent->children = $childMenus[$parent->id];
                return $parent;
            });
    }


    public function showLogin()
    {
        return view('backend.auth.login');
    }


    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => [
                'required',
                'min:8',
                'regex:/[A-Z]/',      // Capital Letter
                'regex:/[a-z]/',      // Small Letter
                'regex:/[0-9]/',      // Number
                'regex:/[@$!%*#?&]/', // Special Character
            ],
            'captcha' => 'required|captcha',
        ], [
            'captcha.required' => 'The CAPTCHA field is required.',
            'captcha.captcha' => 'The CAPTCHA code is invalid.',
        ]);

        $login = trim($request->login);

        // Try matching a College ID first (e.g. COLL123423)
        $college = College::where('college_id', $login)->first();

        if ($college) {
            // Found a college — look up the user tied to that college
            $user = User::with('master')->where('college_id', $college->id)->first();
        } else {
            // Fallback: normal email / username / phone login
            $user = User::with('master')->where('email', $login)
                ->orWhere('username', $login)
                ->orWhere('phone', $login)
                ->first();
        }

        // Check user exists
        if (!$user) {
            return back()->with('error', 'Invalid Username !!');
        }

        // CHECK ACCOUNT LOCK
        if ($user->login_attempts >= 3) {
            if ($user->locked_until && Carbon::now()->lt($user->locked_until)) {

                $remainingMinutes = ceil(Carbon::now()->diffInMinutes($user->locked_until));

                return back()->with(
                    'error',
                    "Account locked. Try again after {$remainingMinutes} minutes."
                );
            }

            // AUTO RESET AFTER LOCK TIME COMPLETED
            if ($user->locked_until && Carbon::now()->gte($user->locked_until)) {

                $user->update([
                    'login_attempts' => 0,
                    'locked_until' => null
                ]);
            }

            if (!$user->locked_until) {

                return back()->with(
                    'error',
                    'Your account is locked. Please contact admin.'
                );
            }
        }


        if (!Hash::check($request->password, $user->password)) {

            $attempts = ($user->login_attempts ?? 0) + 1;

            // LOCK AFTER 3 ATTEMPTS
            if ($attempts >= 3) {

                $lockTime = Carbon::now();

                $user->update([

                    'login_attempts' => 3,

                    // DISPLAY PURPOSE
                    'locked_until' => $lockTime->copy()->addMinutes(30),

                    // REAL SECURITY TIMER
                    'lock_started_at' => $lockTime
                ]);

                return back()->with(
                    'error',
                    'Account locked for 30 minutes due to 3 wrong password attempts.'
                );
            }

            // UPDATE ATTEMPTS
            $user->update([
                'login_attempts' => $attempts
            ]);

            $remaining = 3 - $attempts;

            return back()->with(
                'error',
                "Wrong password. {$remaining} attempts remaining."
            );
        }

        // Check master role exists
        if (!$user->master) {
            return back()->with('error', 'Role not assigned!');
        }

        // Check role name
        if (!in_array($user->master->name, ['director', 'operator', 'principal', 'hod'])) {
            return back()->with('error', 'Not authorized as admin');
        }

        // Check role status (active or not)
        if ($user->master->status != 1) {
            return back()->with('error', 'Role is inactive!');
        }

        // SUCCESS LOGIN
        $user->update([
            'login_attempts' => 0,
            'locked_until' => null
        ]);


        // Login
        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }


    public function showChangePassword()
    {
        $menus = $this->getMenus();
        return view('backend.auth.changePassword', compact('menus'));
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make(
            $request->all(),
            [
                'current_password' => ['required'],
                'new_password' => [
                    'required',
                    'min:8',
                    'different:current_password',
                    'confirmed',
                    'regex:/[A-Z]/',
                    'regex:/[a-z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*#?&]/',
                ],
            ],
            [
                'current_password.required' => 'Please enter your current password.',
                'new_password.required' => 'Please enter a new password.',
                'new_password.min' => 'New password must be at least 8 characters.',
                'new_password.different' => 'New password must be different from current password.',
                'new_password.confirmed' => 'New password and confirm password do not match.',
                'new_password.regex' => 'Password must include uppercase, lowercase, number and special character.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json(['message' => 'Password changed successfully!']);
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
