<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
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
            return response()->json([
                'status' => false,
                'message' => 'Invalid Email'
            ]);
        }

        // CHECK LOGIN LOCK

        if ($user->login_attempts >= 3 && $user->lock_started_at) {

            // REAL UNLOCK TIME
            $unlockTime = Carbon::parse(
                $user->lock_started_at
            )->addMinutes(30);  

            // STILL LOCKED
            if (Carbon::now()->lt($unlockTime)) {

                $remainingMinutes = ceil(
                    Carbon::now()->diffInMinutes($unlockTime)
                );

                return response()->json([

                    'status' => false,

                    'message' =>
                    "Your account is locked for {$remainingMinutes} minutes. Forgot password is temporarily blocked."
                ]);
            }

            // RESET AFTER 30 MINUTES

            $user->login_attempts = 0;

            $user->locked_until = null;

            $user->lock_started_at = null;

            $user->save();
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
            ->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ]);
        }

        // CHECK OTP LOCK
        if (
            $user->otp_attempts >= 3 &&
            $user->otp_lock_started_at
        ) {

            // REAL UNLOCK TIME
            $realUnlockTime = Carbon::parse(
                $user->otp_lock_started_at
            )->addMinutes(15);

            // STILL LOCKED
            if (Carbon::now()->lt($realUnlockTime)) {
                $remainingMinutes = ceil(
                    Carbon::now()->diffInMinutes($realUnlockTime)
                );

                return response()->json([
                    'status' => false,
                    'message' =>
                    "Too many wrong OTP attempts. Try again after {$remainingMinutes} minutes."
                ]);
            }
            // RESET AFTER REAL TIME COMPLETE
            $user->otp_attempts = 0;
            $user->otp_resend_locked_until = null;
            $user->otp_lock_started_at = null;
            $user->save();
        }

        // EXISTING OTP EXPIRE CHECK
        if (now()->gt($user->otp_expires_at)) {
            return response()->json([
                'status' => false,
                'message' => 'OTP Expired'
            ]);
        }

        // WRONG OTP CHECK
        if ($user->otp != $request->otp) {

            $attempts = ($user->otp_attempts ?? 0) + 1;

            // LOCK AFTER 3 ATTEMPTS
            if ($attempts >= 3) {

                $lockTime = Carbon::now();

                $user->otp_attempts = 3;

                $user->otp_resend_locked_until =
                    $lockTime->copy()->addMinutes(15);

                $user->otp_lock_started_at = $lockTime;

                $user->save();

                return response()->json([
                    'status' => false,
                    'message' =>
                    'Too many wrong OTP attempts. OTP resend blocked for 15 minutes.'
                ]);
            }

            // UPDATE ATTEMPTS
            $user->otp_attempts = $attempts;

            $user->save();

            $remaining = 3 - $attempts;

            return response()->json([
                'status' => false,
                'message' =>
                "Invalid OTP. {$remaining} attempts remaining."
            ]);
        }

        // RESET ATTEMPTS AFTER SUCCESS
        $user->otp_attempts = 0;
        $user->otp_resend_locked_until = null;
        $user->otp_lock_started_at = null;

        $user->save();

        return response()->json([
            'status' => true
        ]);
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' =>  [
                'required',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
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
