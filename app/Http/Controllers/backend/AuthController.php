<?php

namespace App\Http\Controllers\backend;

use Captcha;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function refreshCaptcha()
    {
        return response()->json(['captcha' => Captcha::src()]);
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

        // Get user with master relation
        $user = User::with('master')->where('email', $login)
            ->orWhere('username', $login)
            ->orWhere('phone', $login)
            ->first();

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

        // Check password
        if (!Hash::check($request->password, $user->password)) {

            $attempts = ($user->login_attempts ?? 0) + 1;

            //  LOCK ACCOUNT AFTER 3 ATTEMPTS
            if ($attempts >= 3) {

                $user->update([
                    'login_attempts' => 3,
                    'locked_until' => Carbon::now()->addMinutes(30)
                ]);

                return back()->with(
                    'error',
                    'Account locked for 30 minutes due to 3 wrong password attempts.'
                );
            }

            // UPDATE ATTEMPTS
            $remaining = 3 - $attempts;

            $user->update([
                'login_attempts' => $attempts
            ]);

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

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
