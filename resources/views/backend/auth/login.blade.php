@extends('backend.layout.app')

@section('title', 'Admin Login Page')

@section('content')
<div class="overlay"></div>

<div class="container">
    <div class="row mt-5">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        <div class="col-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="login-card text-center">
                        <!-- Logo -->
                        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" width="100" alt="logo">

                        <h5 class="mt-3 text-success fw-bold">Government Polytechnic College</h5>
                        <p class="text-muted small">Provide Username and Password for login</p>

                        <!-- Form -->
                        <form method="POST" action="{{route('admin.login.submit')}}">
                            @csrf
                            <div class="mb-3 text-start">
                                <label class="form-label">Username</label>
                                <input type="text" name="email" class="form-control" placeholder="Enter username"
                                    required>
                            </div>

                            <div class="mb-3 text-start">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter password"
                                    required>
                            </div>
                            <div class="mb-3 text-start">
                                <div class="row">
                                    {{-- Display the CAPTCHA image --}}
                                    <div class="col-6">
                                        <img src="{{ captcha_src() }}"  alt="captcha" width="100%" class="captcha-img">
                                    </div>

                                    <div class="col-1 text-center">
                                        <button type="button" class="btn btn-outline-primary" id="reload">
                                              <i class="fa fa-refresh" aria-hidden="true"></i>
                                        </button>
                                    </div>


                                    {{-- Input field for the user's entry --}}
                                    <div class="col-5">
                                        <input type="text" name="captcha"
                                            class="form-control @error('captcha') is-invalid @enderror"
                                            placeholder="Enter CAPTCHA">
                                    </div>

                                    {{-- Display validation errors --}}
                                    @error('captcha')
                                    <span class="text-danger">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100 mb-3">Login</button>
                            <a href="{{ route('admin.forgot') }}" class="btn btn-warning w-100">Forgot Password</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection