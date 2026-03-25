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


    public function sendOtp(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Invalid Email']);
        }

        $otp = rand(100000, 999999);

        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'OTP Sent',
            'otp' => $otp, 
        ]);
    }


    public function verifyOtp(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || $user->otp != $request->otp) {
            return response()->json(['status' => false, 'message' => 'Invalid OTP']);
        }

        if (now()->gt($user->otp_expires_at)) {
            return response()->json(['status' => false, 'message' => 'OTP Expired']);
        }

        return response()->json(['status' => true]);
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::where('email', $request->email)->first();

        $user->password = Hash::make($request->password);
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        return response()->json(['status' => true, 'message' => 'Password Reset Successful','redirect' => route('admin.login')]);
    }
}
