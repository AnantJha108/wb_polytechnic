@extends('backend.layout.app')

@section('title', 'Admin Dashboard || Edit Operator')

@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-5 mt-3">
        <div class="card">
            <div class="card-body">
                <h2 class="h4">Edit Operator</h2>

                <form action="{{ url('admin/dashboard/operator/update/' . $operator->id) }}" method="POST">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $operator->username) }}">
                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $operator->phone) }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $operator->email) }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update Operator</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection