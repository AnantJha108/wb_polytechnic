@extends('backend.layout.app')

@section('title', 'Forget Password')

@section('content')
<div class="container mt-5">
    <div class="card p-4 shadow">

        <h4 class="mb-3">Forgot Password</h4>

        {{-- Messages --}}
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- OTP Display --}}
        @if(session('otp'))
        <div class="alert alert-warning">
            Your OTP is: <strong>{{ session('otp') }}</strong>
        </div>
        @endif

        <div id="message"></div>

        {{-- EMAIL --}}
        <div id="emailDiv">
            <input type="email" id="email" class="form-control mb-2" placeholder="Enter Email">
            <button id="sendOtp" class="btn btn-primary">Send OTP</button>
        </div>

        {{-- OTP --}}
        <div id="otpDiv" style="display:none;">
            <input type="text" id="otp" class="form-control mb-2" placeholder="Enter OTP">

            <button id="verifyOtp" class="btn btn-success">Verify OTP</button>
            <button id="resendOtp" class="btn btn-warning">Resend OTP</button>
        </div>

        {{-- PASSWORD --}}
        <div id="passwordDiv" style="display:none;">
            <input type="password" id="password" class="form-control mb-2" placeholder="New Password">
            <input type="password" id="confirmPassword" class="form-control mb-2" placeholder="Confirm Password">

            <button id="resetPassword" class="btn btn-success">Reset Password</button>
        </div>

    </div>
</div>
@endsection