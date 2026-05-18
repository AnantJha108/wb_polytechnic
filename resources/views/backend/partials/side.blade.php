<div class="d-flex flex-column shrink-0 p-3 text-bg-dark" style="width: 260px;height:680px">
    <ul class="nav nav-pills flex-column mb-auto">
        {{-- <li>
            <a href="{{route('admin.dashboard')}}" class="nav-link text-white">
                <i class="bi bi-speedometer2 me-2 fs-5"></i>Dashboard
            </a>
        </li> --}}
        @foreach($menus as $menu)
        @if(isset($menu->children) && count($menu->children) > 0)
        <li>
            <a class="nav-link text-white" data-bs-toggle="collapse" href="#employeeMenu">
                {{ $menu->name }}
            </a>
            @foreach($menu->children as $child)
            <div class="collapse" id="employeeMenu">
                <ul class="btn-toggle-nav list-unstyled fw-normal small ps-3">
                    <li><a href="{{ url('admin/'.$child->slug) }}" class="nav-link text-white"> {{ $child->name }}</a>
                    </li>
                </ul>
            </div>
            @endforeach
        </li>
        @endif
        @endforeach
    </ul>
    <hr>
    <div>
        <form method="POST" action="{{route('admin.logout')}}">
            @csrf
            <input type="submit" class="btn btn-secondary w-100" value="Logout">
        </form>
    </div>
</div>