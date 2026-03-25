<?php

namespace App\Http\Controllers\backend;
use Captcha;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    // Handle Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'captcha' => 'required|captcha',
        ], [
            // Custom error message for the captcha field
            'captcha.required' => 'The CAPTCHA field is required.',
            'captcha.captcha' => 'The CAPTCHA code is invalid.',
        ]);

        // Check if user exists
        $user = User::where('email', $request->email)->first();

        // If email not found
        if (!$user) {
            return back()->with('error', 'Invalid Username !!');
        }

        // If password does not match
        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Invalid Password !!');
        }

        // Check role (admin only)
        if (!in_array($user->role, ['director', 'operator', 'principal', 'hod'])) {
            return back()->with('error', 'Not authorized as admin');
        }

        // Login user manually
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
