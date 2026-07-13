@extends('backend.layout.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col mt-4">
        <h3>Admin Dashboard</h3>
        <div class="row">
            <h4>
                Welcome, {{ $user->name }}
            </h4>

            <h5 class="mt-3">
                Logged in as:
                <span class="badge bg-primary text-uppercase">
                    {{ $user->master->name}}
                </span>
            </h5>

            {{-- Role-based message --}}
            @if($user->master->name == 'director')
            <p>You are Director. You have full access.</p>

            @elseif($user->master->name == 'operator')
            <p>You are Operator. Limited control.</p>

            @elseif($user->master->name == 'principal')
            <p>You are Principal.</p>

            @elseif($user->master->name == 'hod')
            <p>You are HOD.</p>
            @endif
        </div>
    </div>
</div>
@endsection