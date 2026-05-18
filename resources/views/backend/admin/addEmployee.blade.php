@extends('backend.layout.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row ">
    @include('backend.partials.side')
    <div class="col p-5 mt-4">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h2>Add Employee</h2>

                    @if(session('success'))
                    <p style="color:green">{{ session('success') }}</p>
                    @endif

                    <form method="POST">
                        @csrf

                        <div class="mb-3 text-start">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter Name" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label class="form-label">Email</label>
                            <input type="text" name="email" class="form-control" placeholder="Enter Email" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" placeholder="Enter Phone" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success btn-sm">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection