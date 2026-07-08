@extends('backend.layout.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row ">
    @include('backend.partials.side')
    <div class="col px-5 mt-3 ">

        @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
        @endif

        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h2 class="h4">Add Colleges</h2>

                    <form action="/admin/dashboard/college/store" method="POST" enctype="multipart/form-data">

                        @csrf

                        <div class="container">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label>College Name</label>
                                    <input type="text" placeholder="College Name" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-6 mb-3">
                                    <label>Contact Number</label>
                                    <input type="text" placeholder="Contact Number" name="contact_no"
                                        class="form-control @error('contact_no') is-invalid @enderror"
                                        value="{{ old('contact_no') }}">
                                    @error('contact_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-6 mb-3">
                                    <label>Email</label>
                                    <input type="email" placeholder="Email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $college->email ?? '') }}">
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-6 mb-3">
                                    <label>District</label>
                                    <input name="district" placeholder="District"
                                        class="form-control @error('district') is-invalid @enderror"
                                        value="{{ old('district') }}">
                                    @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label>Address</label>
                                    <textarea name="address" placeholder="Address"
                                        class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                                    @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-6 mb-3">
                                    <label>Logo</label>
                                    <input type="file" id="logo" placeholder="Logo" name="logo" class="form-control"
                                        accept="image/*">

                                    <!-- Button trigger modal -->
                                    <button type="button" id="previewLogoBtn" class="btn btn-primary mt-2" disabled
                                        data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        Preview Logo
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade w-100 h-100"  id="exampleModal" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Logo Preview</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img id="logoPreviewImg" src="" alt="Logo Preview"
                                                        class="img-fluid rounded" style="max-height: 400px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 mb-3">
                                    <label>Slug</label>
                                    <input type="text" placeholder="Slug" name="slug"
                                        class="form-control  @error('slug') is-invalid @enderror"
                                        value="{{ old('slug', $college->slug ?? '') }}">
                                    @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-6 mb-3">
                                    <label>Template</label>
                                    <select name="template_id" class="form-control">
                                        <option value=""> Select Template </option>

                                        @foreach($templates as $template)
                                        <option value=" {{ $template->id }}">
                                            {{ $template->id }} -- {{ $template->template_name }}
                                        </option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-6 mb-3">
                                    <label>Status</label>

                                    <select name="status" class="form-control">

                                        <option value="1">Active</option>

                                        <option value="0">Inactive</option>

                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    Save College
                                </button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection