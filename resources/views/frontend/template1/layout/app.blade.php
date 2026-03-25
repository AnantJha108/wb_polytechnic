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
            background: #1aa34a;
            color: white;
            font-size: 14px;
            padding: 5px 0;
        }

        .hero {
            background: url('banner.jpg') center/cover no-repeat;
            height: 350px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }

        .hero h1 {
            font-weight: 700;
            font-size: 48px;
            text-shadow: 0 2px 5px rgba(0, 0, 0, .5);
        }

        .card-overlay {
            background: linear-gradient(transparent, rgba(0, 0, 0, .7));
            color: white;
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 10px;
        }

        .card-img {
            height: 180px;
            object-fit: cover;
        }

        .committee {
            background: #d88c0f;
            color: white;
            padding: 60px 0;
        }

        .news-card {
            border-left: 5px solid orange;
        }

        footer {
            background: #f1f1f1;
            padding: 40px 0;
        }

        .footer-bottom {
            background: #1aa34a;
            color: white;
            padding: 10px;
            text-align: center;
        }
    </style>

    @stack('styles')
</head>

<body>

    @include('frontend.template1.partials.header')
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

    @include('frontend.template1.partials.footer')


    <script src="{{ asset('js/app.js') }}"></script>

</body>

</html>