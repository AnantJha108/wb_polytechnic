@extends('backend.layout.app')

@section('title', 'Admin Dashboard || Edit College Page')

@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-5 mt-3">

        <div class="card">
            <div class="card-body">
                <h2 class="h4">Edit College Page</h2>

                <form action="{{ url('admin/dashboard/collegepage/update/' . $page->id) }}" method="POST" enctype="multipart/form-data">
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
                            </div>

                            <div class="col-6 mb-3">
                                <label>Page Title</label>
                                <input type="text" name="page"
                                    class="form-control @error('page') is-invalid @enderror"
                                    value="{{ old('page', $page->page) }}">
                                @error('page')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label>Description</label>
                                <textarea name="description" rows="3"
                                    class="form-control @error('description') is-invalid @enderror">{{ old('description', $page->description) }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-6 mb-3">
                                <label>Banner Image</label>
                                <input type="file" name="banner" class="form-control @error('banner') is-invalid @enderror" accept="image/*">
                                @error('banner')<div class="invalid-feedback">{{ $message }}</div>@enderror

                                @if($bannerUrl)
                                    <div class="mt-2">
                                        <p class="mb-1 text-muted">Current banner:</p>
                                        <img src="{{ $bannerUrl }}" width="150" height="80" style="object-fit:cover;">
                                    </div>
                                @endif
                            </div>

                            <div class="col-6 mb-3">
                                <label>Principal Image</label>
                                <input type="file" name="principle_image" class="form-control @error('principle_image') is-invalid @enderror" accept="image/*">
                                @error('principle_image')<div class="invalid-feedback">{{ $message }}</div>@enderror

                                @if($principleImageUrl)
                                    <div class="mt-2">
                                        <p class="mb-1 text-muted">Current image:</p>
                                        <img src="{{ $principleImageUrl }}" width="80" height="80" style="object-fit:cover; border-radius:50%;">
                                    </div>
                                @endif
                            </div>

                            <div class="col-12 mb-3">
                                <label>Principal Message</label>
                                <textarea name="principle_message" rows="5"
                                    class="form-control @error('principle_message') is-invalid @enderror">{{ old('principle_message', $page->principle_message) }}</textarea>
                                @error('principle_message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Update College Page</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection