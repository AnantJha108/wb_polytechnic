<!-- Top Bar -->

<div class="topbar text-center">
    Department of Technical Education, Training & Skill Development Govt. of West Bengal
    <a class="text-decoration-none text-white" href="{{route('admin.login')}}">Login</a>
</div>



<!-- Navbar -->

<nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            APCR Polytechnic
        </a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item"><a class="nav-link" href="{{route('poly.about',$college->slug) }}">About</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route('poly.contact',$college->slug) }}">Contact</a></li>
            </ul>

        </div>
    </div>
</nav>