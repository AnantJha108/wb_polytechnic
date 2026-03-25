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
        .topbar {
            background: #10a651;
            color: white;
            font-size: 14px;
            padding: 6px 0;
        }

        .hero {
            background: url("college.jpg") center/cover no-repeat;
            height: 420px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }

        .hero h1 {
            font-weight: 700;
            font-size: 45px;
        }

        .section-title {
            font-weight: 700;
            margin-bottom: 25px;
        }

        .blue-bg {
            background: #223b6b;
            padding: 60px 0;
        }

        .card-custom img {
            height: 180px;
            object-fit: cover;
        }

        .committee-box {
            padding: 50px;
            color: white;
            font-weight: 600;
            font-size: 20px;
            text-align: center;
        }

        .committee-green {
            background: #10a651;
        }

        .committee-blue {
            background: #243e73;
        }

        .notice-box {
            padding: 40px;
            background: #f7f7f7;
        }

        .footer {
            background: #1e3563;
            color: white;
            padding: 50px 0;
        }

        .footer a {
            color: white;
            text-decoration: none;
        }
    </style>

    @stack('styles')
</head>

<body>

    @include('frontend.template3.partials.header')
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

    @include('frontend.template3.partials.footer')


    <script src="{{ asset('js/app.js') }}"></script>

</body>

</html>