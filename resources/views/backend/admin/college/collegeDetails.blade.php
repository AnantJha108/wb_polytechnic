@extends('backend.layout.app')

@section('title', 'Admin Dashboard || College Details')

@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-5 mt-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4">College Details</h2>
            <a href="{{ url('admin/dashboard/college/index') }}" class="btn btn-secondary btn-sm">
                &larr; Back to List
            </a>
        </div>

        @if(session('success'))
            <p style="color:green">{{ session('success') }}</p>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-3 text-center mb-4">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" class="img-fluid rounded" style="max-height: 180px;" alt="{{ $college->name }}">
                        @else
                            <div class="border rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                                No Logo
                            </div>
                        @endif
                    </div>

                    <div class="col-md-9">
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 200px;">College ID</th>
                                <td>{{ $college->college_id }}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>{{ $college->name }}</td>
                            </tr>
                            <tr>
                                <th>Slug</th>
                                <td>{{ $college->slug }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $college->email }}</td>
                            </tr>
                            <tr>
                                <th>Contact Number</th>
                                <td>{{ $college->contact_no }}</td>
                            </tr>
                            <tr>
                                <th>District</th>
                                <td>{{ $college->district }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $college->address }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($college->status == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $college->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        </table>

                        <a href="{{ url('admin/dashboard/college/edit/' . $college->id) }}" class="btn btn-primary">
                            Edit College
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection