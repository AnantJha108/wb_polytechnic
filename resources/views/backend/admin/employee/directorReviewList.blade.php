@extends('backend.layout.app')
@section('title', $college->name . ' — Employees')
@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-4 mt-3">
        <a href="{{ url('admin/dashboard/employeeReviewDirector/index') }}" class="btn btn-sm btn-secondary mb-3">← Back to College List</a>
        <h2 class="h4 mb-3">{{ $college->name }} — Employees</h2>

        <table class="table">
            <thead><tr><th>Employee ID</th><th>Name</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($employees as $emp)
                    <tr>
                        <td>{{ $emp->employee_id }}</td>
                        <td>{{ $emp->first_name }} {{ $emp->last_name }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $emp->status)) }}</span></td>
                        <td><a href="{{ url('admin/dashboard/employeeReviewDirector/show/' . $emp->id) }}" class="btn btn-sm btn-primary">Open</a></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">No employees yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection