@extends('backend.layout.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col mt-4">
        <h3>Admin Dashboard</h3>
        {{-- <div class="row mb-5">
            <div class="col-4">
                <div class="card bg-success text-white py-3">
                    <div class="card-body py-6">
                        <h1 class="h2">30+</h1>
                        <h2 class="h4">Total Users</h2>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card bg-danger text-white py-3">
                    <div class="card-body py-6">
                        <h1 class="h2">56+</h1>
                        <h2 class="h4">Total Tasks</h2>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="row">
            <h4>
                Welcome, {{ $user->name }}
            </h4>

            <h5 class="mt-3">
                Logged in as:
                <span class="badge bg-primary text-uppercase">
                    {{ $user->role }}
                </span>
            </h5>

            {{-- Role-based message --}}
            @if($user->role == 'director')
            <p>You are Director. You have full access.</p>

            @elseif($user->role == 'operator')
            <p>You are Operator. Limited control.</p>

            @elseif($user->role == 'principal')
            <p>You are Principal.</p>

            @elseif($user->role == 'hod')
            <p>You are HOD.</p>
            @endif
        </div>
    </div>
</div>
@endsection