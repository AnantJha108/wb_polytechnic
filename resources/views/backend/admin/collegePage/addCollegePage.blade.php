@extends('backend.layout.app')

@section('title', 'Admin Dashboard || Add College Page')

@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-5 mt-3">

        <div class="card">
            <div class="card-body">
                <h2 class="h4">Add College Page</h2>

                <form action="{{ url('admin/dashboard/collegepage/store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="container">
                        <div class="row">

                            <div class="col-6 mb-3">
                                <label>College</label>
                                <select class="form-control" disabled>
                                    <option selected>{{ $college->name }}</option>
                                </select>
                                <small class="text-muted">You can only manage the page for your assigned college.</small>
                            </div>

                            <div class="col-6 mb-3">
                                <label>Page Title</label>
                                <input type="text" name="page" placeholder="e.g. Home, About Us"
                                    class="form-control @error('page') is-invalid @enderror"
                                    value="{{ old('page') }}">
                                @error('page')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label>Description</label>
                                <textarea name="description" rows="3"
                                    class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-6 mb-3">
                                <label>Banner Image</label>
                                <input type="file" name="banner" class="form-control @error('banner') is-invalid @enderror" accept="image/*">
                                @error('banner')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-6 mb-3">
                                <label>Principal Image</label>
                                <input type="file" name="principle_image" class="form-control @error('principle_image') is-invalid @enderror" accept="image/*">
                                @error('principle_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label>Principal Message</label>
                                <textarea name="principle_message" rows="5"
                                    class="form-control @error('principle_message') is-invalid @enderror">{{ old('principle_message') }}</textarea>
                                @error('principle_message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Save College Page</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection