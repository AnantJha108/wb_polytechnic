<!-- TOP BAR -->

<div class="topbar">
    <div class="container d-flex justify-content-between">

        <div>
            Department of Technical Education, Training & Skill Development, Govt. of West Bengal
        </div>


        <a class="text-decoration-none text-white" href="{{route('admin.login')}}">📧 ajcbpolytechnic@gmail.com | 🔐 Login</a>

    </div>
</div>

<!-- NAVBAR -->

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">

    <div class="container">

        <a class="navbar-brand d-flex align-items-center" href="#">
            <span class="ms-2 fw-bold">AJCB Polytechnic</span>
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item"><a class="nav-link" href="">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route('poly.about',$college->slug) }}">About</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route('poly.contact',$college->slug) }}">Contact</a></li>

            </ul>

        </div>

    </div>
</nav>