@extends('backend.layout.app')

@section('title', 'Admin Dashboard || Add College Page')

@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-5 mt-3">

        <div class="card">
            <div class="card-body">
                <h2 class="h4">Add College Page</h2>

                <form action="{{ url('admin/dashboard/collegepage/store') }}" method="POST"
                    enctype="multipart/form-data">
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

                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="container">
                        <div class="row">

                            <div class="col-6 mb-3">
                                <label>College</label>
                                <select class="form-control" disabled>
                                    <option selected>{{ $college->name }}</option>
                                </select>
                                <small class="text-muted">You can only manage the page for your assigned
                                    college.</small>
                            </div>

                            <div class="col-6 mb-3">
                                <label>Page Title</label>
                                <input type="text" name="page" placeholder="e.g. Home, About Us"
                                    class="form-control @error('page') is-invalid @enderror" value="{{ old('page') }}">
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
                                <input type="file" name="banner" id="bannerInput"
                                    class="form-control @error('banner') is-invalid @enderror" accept="image/*">
                                @error('banner')<div class="invalid-feedback">{{ $message }}</div>@enderror

                                <div class="mt-2">
                                    <img id="bannerThumb" src="" style="display:none; max-width:150px; max-height:90px; object-fit:cover; cursor:pointer; border:1px solid #ddd; border-radius:4px;">
                                </div>
                            </div>

                            <div class="col-6 mb-3">
                                <label>Principal Image</label>
                                <input type="file" name="principle_image" id="principleImageInput"
                                    class="form-control @error('principle_image') is-invalid @enderror"
                                    accept="image/*">
                                @error('principle_image')<div class="invalid-feedback">{{ $message }}</div>@enderror

                                <div class="mt-2">
                                    <img id="principleImageThumb" src="" style="display:none; width:70px; height:70px; object-fit:cover; border-radius:50%; cursor:pointer; border:1px solid #ddd;">
                                </div>
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

{{-- Full-screen Image Preview Overlay --}}
<div id="fullScreenPreview" style="
    display:none; position:fixed; top:0; left:0; width:100%; height:100%;
    background:rgba(141, 133, 133, 0.9); z-index:2000; justify-content:center; align-items:center;">

    <span id="closePreview" style="
        position:absolute; top:20px; right:30px; color:#fff; font-size:40px;
        font-weight:bold; cursor:pointer; line-height:1; z-index:2001;">&times;</span>

    <img id="fullScreenPreviewImage" src="" style="max-width:90%; max-height:90%; object-fit:contain; border-radius:6px;">
</div>
@endsection