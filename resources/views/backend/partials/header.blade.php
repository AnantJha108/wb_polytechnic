<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg">
    <div class="container position-relative">
        {{-- Left: Role name instead of "Admin || Pannel" --}}
        <a class="navbar-brand fw-bold" href="#">
            {{ auth()->user() && auth()->user()->master
                ? ucfirst(auth()->user()->master->name)
                : 'Admin Panel' }} || Pannel
        </a>

        {{-- Center: College name (blank for director, since college_id is NULL) --}}
        <div class="position-absolute top-50 start-50 translate-middle text-white fw-semibold d-none d-lg-block">
            @if(auth()->user() && auth()->user()->college)
                {{ auth()->user()->college->name }}
            @endif
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>