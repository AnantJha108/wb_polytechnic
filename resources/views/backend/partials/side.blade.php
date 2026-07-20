<div class="d-flex flex-column shrink-0 p-3 text-bg-dark" style="width: 260px;height:680px">
    <ul class="nav nav-pills flex-column mb-auto">
        {{-- <li>
            <a href="{{route('admin.dashboard')}}" class="nav-link text-white">
                <i class="bi bi-speedometer2 me-2 fs-5"></i>Dashboard
            </a>
        </li> --}}
        @foreach($menus as $parent)
        <li class="nav-item">
            <a class="nav-link collapsed text-white" data-bs-target="#menu-{{ $parent->id }}" data-bs-toggle="collapse"
                href="#">
                <i class="bi bi-grid me-2"></i>
                <span>{{ $parent->name }}</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="menu-{{ $parent->id }}" class="nav-content collapse list-unstyled ">

                @foreach($parent->children as $child)
                @php
                $parts = explode('-', $child->slug, 2);
                $verb = $parts[0] ?? '';
                $module = $parts[1] ?? $parts[0];

                $action = match($verb) {
                'add' => 'create', // shows the empty form (GET)
                'view' => 'index',
                'edit' => 'edit',
                'delete' => 'destroy',
                default => 'index',
                };

                // Special case: principal's page-review link needs a distinct method name
                if ($module === 'collegepagestatus') {
                $url = url('admin/dashboard/college/collegepagestatus');
                } else {
                $url = url("admin/dashboard/{$module}/{$action}");
                }

                @endphp

                <li>
                    <a href="{{ $url }}" class="nav-link text-white py-1">
                        <i class="bi bi-circle me-2"></i>
                        <span>{{ $child->name }}</span>
                    </a>
                </li>
                @endforeach

            </ul>
        </li>
        @endforeach
    </ul>
    <hr>
    <div>
        <a href="{{ route('admin.change.password') }}" class="btn btn-outline-light w-100 mb-2">
            Change Password
        </a>

        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-secondary w-100">Logout</button>
        </form>
    </div>
</div>