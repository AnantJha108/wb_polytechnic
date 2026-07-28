@extends('backend.layout.app')
@section('title', 'Review Employees')
@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-4 mt-3">
        <h2 class="h4 mb-3">Employee Records — Pending Review</h2>
        <table class="table">
            <thead><tr><th>Employee ID</th><th>Name</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($employees as $emp)
                    <tr>
                        <td>{{ $emp->employee_id }}</td>
                        <td>{{ $emp->first_name }} {{ $emp->last_name }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $emp->status)) }}</span></td>
                        <td><a href="{{ url('admin/dashboard/employeeReviewPrincipal/show/' . $emp->id) }}" class="btn btn-sm btn-primary">Open</a></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">No records yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection