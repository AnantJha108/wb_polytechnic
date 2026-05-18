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
        $request->validate([
            'login' => 'required'
        ]);

        $login = trim($request->login);

        $user = User::where('email', $request->login)
            ->orWhere('username', $request->login)
            ->orWhere('phone', $request->login)
            ->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Invalid Email']);
        }

        $otp = rand(100000, 999999);

        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        if ($user->phone == $login) {

            // Mask phone number
            $masked = substr($user->phone, 0, 3)
                . '*****'
                . substr($user->phone, -2);

        } elseif ($user->email == $login) {

            // Mask email
            $emailParts = explode('@', $user->email);

            $name = substr($emailParts[0], 0, 2) . '****';

            $masked = $name . '@' . $emailParts[1];
        } else {

            // Username entered
            $masked = $user->username;
        }

        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully to ' . $masked,
            'otp' => $otp,
        ]);
    }


    public function verifyOtp(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'otp' => 'required'
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('username', $request->login)
            ->orWhere('phone', $request->login)
            ->where('otp', $request->otp)
            ->first();

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
            'login' => 'required',
            'password' =>  [
                'required',
                'min:8',
                'regex:/[A-Z]/',      // Capital Letter
                'regex:/[a-z]/',      // Small Letter
                'regex:/[0-9]/',      // Number
                'regex:/[@$!%*#?&]/', // Special Character
                'confirmed'
            ]
        ], [
            'password.min' => 'Password must be at least 8 characters',

            'password.regex' =>
            'Password must contain Capital, Small, Number and Special Character',

            'password.confirmed' =>
            'Password and Confirm Password do not match'
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('username', $request->login)
            ->orWhere('phone', $request->login)
            ->first();

        $user->password = Hash::make($request->password);
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        return response()->json(['status' => true, 'message' => 'Password Reset Successful', 'redirect' => route('admin.login')]);
    }
}
