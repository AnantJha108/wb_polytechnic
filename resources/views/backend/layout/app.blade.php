<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>

<body>

    @include('backend.partials.header')

    <main class="container-fluid">
        @yield('content')
    </main>



    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script src="https://jquery.com"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#reload').click(function () {
        $.ajax({
            type: 'GET',
            url: '{{ route('refresh_captcha') }}', // Define this route in web.php
            success: function (data) {
                $('.captcha-img').attr('src', data.captcha);
            }
        });
    });
</script>
<script>
    $(document).ready(function(){

    // SEND OTP
    $('#sendOtp').click(function(){

        let email = $('#email').val();

        $.post('/admin/send-otp', {
            email: email,
            _token: '{{ csrf_token() }}'
        }, function(res){

            if(res.status){
                $('#message').html('<div class="alert alert-success">'+res.message+' | OTP: '+res.otp+'</div>');
                $('#emailDiv').hide();
                $('#otpDiv').show();
            } else {
                $('#message').html('<div class="alert alert-danger">'+res.message+'</div>');
            }

        });
    });

    // VERIFY OTP
    $('#verifyOtp').click(function(){

        $.post('/admin/verify-otp', {
            email: $('#email').val(),
            otp: $('#otp').val(),
            _token: '{{ csrf_token() }}'
        }, function(res){

            if(res.status){
                $('#message').html('<div class="alert alert-success">OTP Verified</div>');
                $('#otpDiv').hide();
                $('#passwordDiv').show();
            } else {
                $('#message').html('<div class="alert alert-danger">'+res.message+'</div>');
            }

        });
    });

    // RESEND OTP
    $('#resendOtp').click(function(){

        $('#sendOtp').click();

    });

    // RESET PASSWORD
    $('#resetPassword').click(function(){

        $.post('/admin/reset-password', {
            email: $('#email').val(),
            password: $('#password').val(),
            password_confirmation: $('#confirmPassword').val(),
            _token: '{{ csrf_token() }}'
        }, function(res){

            if(res.status){
                $('#message').html('<div class="alert alert-success">'+res.message+'</div>');
                $('#passwordDiv').hide();
                 setTimeout(function(){
                window.location.href = res.redirect;
               }, 2000);
            } else {
                $('#message').html('<div class="alert alert-danger">Error</div>');
            }

        });

    });

});
</script>

</html>