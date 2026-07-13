@extends('backend.layout.app')

@section('title', 'Admin Dashboard || Operator Details')

@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-5 mt-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4">Operator Details</h2>
            <a href="{{ url('admin/dashboard/operator/index') }}" class="btn btn-secondary btn-sm">
                &larr; Back to List
            </a>
        </div>

        @if(session('success'))
            <p style="color:green">{{ session('success') }}</p>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-borderless mb-4">
                    <tr>
                        <th style="width: 200px;">Name</th>
                        <td>{{ $operator->username }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $operator->phone }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $operator->email }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>{{ ucfirst($operator->master->name ?? 'N/A') }}</td>
                    </tr>
                    <tr>
                        <th>Login Attempts</th>
                        <td>{{ $operator->login_attempts ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>Account Status</th>
                        <td>
                            @if($operator->locked_until && \Carbon\Carbon::now()->lt($operator->locked_until))
                                <span class="badge bg-danger">Locked</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $operator->created_at ? $operator->created_at->format('d M Y, h:i A') : 'N/A' }}</td>
                    </tr>
                </table>

                <a href="{{ url('admin/dashboard/operator/edit/' . $operator->id) }}" class="btn btn-primary">
                    Edit Operator
                </a>
            </div>
        </div>

    </div>
</div>
@endsection