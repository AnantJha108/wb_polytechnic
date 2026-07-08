@extends('backend.layout.app')

@section('title', 'Admin Dashboard || Edit College')

@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-5 mt-3">

        @if(session('success'))
            <p style="color:green">{{ session('success') }}</p>
        @endif

        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h2 class="h4">Edit College</h2>

                    <form action="{{ url('admin/dashboard/college/update/' . $college->id) }}"
                          method="POST" enctype="multipart/form-data">

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
                                    <label>College Name</label>
                                    <input type="text" placeholder="College Name" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $college->name) }}">
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-6 mb-3">
                                    <label>Contact Number</label>
                                    <input type="text" placeholder="Contact Number" name="contact_no"
                                        class="form-control @error('contact_no') is-invalid @enderror"
                                        value="{{ old('contact_no', $college->contact_no) }}">
                                    @error('contact_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-6 mb-3">
                                    <label>Email</label>
                                    <input type="email" placeholder="Email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $college->email) }}">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-6 mb-3">
                                    <label>District</label>
                                    <input name="district" placeholder="District"
                                        class="form-control @error('district') is-invalid @enderror"
                                        value="{{ old('district', $college->district) }}">
                                    @error('district')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label>Address</label>
                                    <textarea name="address" placeholder="Address"
                                        class="form-control @error('address') is-invalid @enderror">{{ old('address', $college->address) }}</textarea>
                                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-6 mb-3">
                                    <label>Logo</label>
                                    <input type="file" id="logo" placeholder="Logo" name="logo"
                                        class="form-control" accept="image/*">

                                    @if($logoUrl)
                                        <div class="mt-2">
                                            <p class="mb-1 text-muted">Current logo:</p>
                                            <img src="{{ $logoUrl }}" width="80" height="80" alt="Current logo">
                                            <small class="text-muted d-block">Upload a new file only if you want to replace it.</small>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-6 mb-3">
                                    <label>Slug</label>
                                    <input type="text" placeholder="Slug" name="slug"
                                        class="form-control @error('slug') is-invalid @enderror"
                                        value="{{ old('slug', $college->slug) }}">
                                    @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-6 mb-3">
                                    <label>Template</label>
                                    <select name="template_id" class="form-control">
                                        <option value="">Select Template</option>
                                        @foreach($templates as $template)
                                            <option value="{{ $template->id }}"
                                                {{ old('template_id', $college->template_id) == $template->id ? 'selected' : '' }}>
                                                {{ $template->id }} -- {{ $template->template_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6 mb-3">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="1" {{ old('status', $college->status) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $college->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    Update College
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