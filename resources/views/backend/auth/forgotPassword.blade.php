@extends('backend.layout.app')

@section('title', 'Forget Password')

@section('content')
<div class="container mt-5">
    <div class="col-5 mx-auto">
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

            <div id="loginDiv">
                <input type="text" id="login" class="form-control mb-2" placeholder="Enter username">
                <button id="sendOtp" class="btn btn-primary mt-1">Send OTP</button>
            </div>

            {{-- OTP --}}
            <div id="otpDiv" style="display:none;">
                <input type="text" id="otp" class="form-control mb-2" placeholder="Enter OTP">

                <button id="verifyOtp" class="btn btn-success">Verify OTP</button>
                <button id="resendOtp" class="btn btn-warning ms-4">Resend OTP</button>
            </div>

            {{-- PASSWORD --}}
            <div id="passwordDiv" style="display:none;">
                {{-- Error Messages --}}
                <input type="password" id="password" class="form-control mb-2" placeholder="New Password">
                <div id="passwordErrors" class="text-danger mt-2"></div>
                <input type="password" id="confirmPassword" class="form-control mb-2" placeholder="Confirm Password">
                <div id="confirmError" class="text-danger mt-2"></div>

                <button id="resetPassword" class="btn btn-success">Reset Password</button>
            </div>

        </div>
    </div>
</div>

<script>
    $('#password').on('keyup', function () {

    let password = $(this).val();

    let errors = [];

    // Minimum 8 characters
    if (password.length < 8) {
        errors.push("• Password must be at least 8 characters");
    }

    // Capital letter
    if (!/[A-Z]/.test(password)) {
        errors.push("• At least 1 Capital Letter required");
    }

    // Small letter
    if (!/[a-z]/.test(password)) {
        errors.push("• At least 1 Small Letter required");
    }

    // Number
    if (!/[0-9]/.test(password)) {
        errors.push("• At least 1 Number required");
    }

    // Special character
    if (!/[@$!%*#?&]/.test(password)) {
        errors.push("• At least 1 Special Character required");
    }

    // Show errors
    if (errors.length > 0) {

        $('#passwordErrors').html(errors.join('<br>'));

    } else {

        $('#passwordErrors').html(
            '<span class="text-success">Strong Password ✓</span>'
        );
    }

});


/* Confirm Password Match */

$('#confirmPassword').on('keyup', function () {

    let password = $('#password').val();
    let confirmPassword = $(this).val();

    if (password !== confirmPassword) {

        $('#confirmError').html(
            '• Password and Confirm Password do not match'
        );

    } else {

        $('#confirmError').html(
            '<span class="text-success">Password Matched ✓</span>'
        );
    }

});

</script>
@endsection