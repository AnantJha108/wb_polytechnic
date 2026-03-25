<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('backend.auth.forgotPassword');
    }

    // STEP 1: Send OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !in_array($user->role, ['director', 'operator', 'principal', 'hod'])) {
            return back()->with('error', 'Invalid Admin Email');
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        // Show OTP on screen (as per your requirement)
        return back()->with([
            'success' => 'OTP Generated Successfully',
            'otp' => $otp,
            'email' => $user->email
        ]);
    }

    // STEP 2: Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
            'password' => 'required|min:6|confirmed'
        ], [
            'password.confirmed' => 'New Password and Confirm Password does not match !!'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'User not found')->withInput();
        }

        // Check OTP
        if ($user->otp != $request->otp) {
            return back()->with('error', 'Invalid OTP')->withInput();
        }

        // Check expiry
        if (!$user->otp_expires_at || now()->gt($user->otp_expires_at)) {
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();

            return back()->with('error', 'Your OTP is expired.')->withInput();
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        return redirect()->route('admin.login')->with('success', 'Password Reset Successfully');
    }
}
