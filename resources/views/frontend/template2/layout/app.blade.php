<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        /* Top Bar */

        .topbar {
            background: #333;
            color: white;
            font-size: 14px;
            padding: 6px 0;
        }

        /* Navbar */

        .navbar-nav .nav-link {
            font-weight: 500;
        }

        .navbar-nav .nav-link.active {
            background: #f7b500;
            color: white !important;
            border-radius: 4px;
            padding: 6px 14px;
        }

        /* Slider */

        .carousel-item img {
            height: 450px;
            object-fit: cover;
        }

        /* committee cards */

        .committee-card {
            background: #f5f5f5;
            padding: 30px;
            text-align: center;
            border-radius: 6px;
            transition: 0.3s;
        }

        .committee-card:hover {
            transform: translateY(-5px);
        }

        /* feature section */

        .feature-section {
            background: url('https://images.unsplash.com/photo-1503676260728-1c00da094a0b');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
        }

        .feature-box {
            text-align: center;
        }

        .feature-icon {
            background: #f7b500;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            font-size: 30px;
        }

        /* notice */

        .notice-box {
            background: #f5f5f5;
            padding: 25px;
            border-left: 5px solid #f7b500;
        }

        /* principal message */

        .message-section {
            background: #222;
            color: white;
            padding: 60px 0;
        }

        .message-img {
            background: #f7b500;
            padding: 20px;
        }

        /* footer */

        .footer {
            background: #111;
            color: white;
            padding: 50px 0;
        }

        .footer a {
            color: #ccc;
            text-decoration: none;
        }

        .footer a:hover {
            color: white;
        }
    </style>


    @stack('styles')
</head>

<body>

    @include('frontend.template2.partials.header')
    <main class="container">
        <div class="positon position-absolute">
            @if(session('success'))
            <div class="alert alert-success">
                {{session('success')}}
            </div>
            @endif

            @if(session('warning'))
            <div class="alert alert-warning">
                {{session('warning')}}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">
                {{session('error')}}
            </div>
            @endif

        </div>
        @yield('content')
    </main>

    @include('frontend.template2.partials.footer')


    <script src="{{ asset('js/app.js') }}"></script>

</body>

</html>