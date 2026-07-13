@extends('backend.layout.app')

@section('title', 'Admin Dashboard || Operators')

@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4">Operators</h2>
            <a href="{{ url('admin/dashboard/operator/create') }}" class="btn btn-primary btn-sm">+ Add Operator</a>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            <p class="mb-2">{{ session('success') }}</p>
            @if(session('operator_password'))
            <div class="border rounded p-3 bg-white">
                <strong>Login Credentials (save now — password won't be shown again):</strong>
                <p class="mb-1 mt-2">Email: <code>{{ session('operator_email') }}</code></p>
                <p class="mb-0">Password: <code>{{ session('operator_password') }}</code></p>
            </div>
            @endif
        </div>
        @endif

        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($operators as $key => $operator)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $operator->username }}</td>
                    <td>{{ $operator->phone }}</td>
                    <td>{{ $operator->email }}</td>
                    <td>
                        <a href="{{ url('admin/dashboard/operator/show/' . $operator->id) }}"
                            class="btn btn-sm btn-info">View</a>
                        <a href="{{ url('admin/dashboard/operator/edit/' . $operator->id) }}"
                            class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ url('admin/dashboard/operator/destroy/' . $operator->id) }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No operators found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection