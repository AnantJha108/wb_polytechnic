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

@if(session('otp'))
<script>
    let time = 300; // 5 minutes

    let timer = setInterval(function() {
        let minutes = Math.floor(time / 60);
        let seconds = time % 60;

        document.getElementById('timer').innerText =
            "OTP expires in: " + minutes + ":" + (seconds < 10 ? '0' : '') + seconds;

        time--;

        if (time < 0) {
            clearInterval(timer);
            document.getElementById('timer').innerText = "OTP Expired";
        }
    }, 1000);
</script>
@endif

</html>