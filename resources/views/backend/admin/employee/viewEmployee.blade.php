@extends('backend.layout.app')
@section('title', 'Employee List')
@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-4 mt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">Employee Management</h2>
            <a href="{{ url('admin/dashboard/employee/create') }}" class="btn btn-primary">Register Employee</a>
        </div>

        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        <table class="table">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $emp)
                    <tr>
                        <td>{{ $emp->employee_id }}</td>
                        <td>{{ $emp->first_name }} {{ $emp->last_name }}</td>
                        <td>
                            <span class="badge
                                @if($emp->status == 'draft') bg-secondary
                                @elseif(str_contains($emp->status, 'forwarded')) bg-warning text-dark
                                @elseif($emp->status == 'approved') bg-success
                                @elseif($emp->status == 'rejected') bg-danger
                                @else bg-info @endif">
                                {{ ucfirst(str_replace('_', ' ', $emp->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ url('admin/dashboard/employee/show/' . $emp->id) }}" class="btn btn-sm btn-info">View</a>

                            @if (in_array($emp->status, ['draft', 'reverted']))
                                <a href="{{ url('admin/dashboard/employee/edit/' . $emp->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <button type="button" class="btn btn-sm btn-success forward-btn" data-id="{{ $emp->id }}">Forward</button>
                            @endif

                            @if (in_array($emp->status, ['draft', 'reverted', 'rejected']))
                                <form action="{{ url('admin/dashboard/employee/destroy/' . $emp->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this employee record?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">No employees registered yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(function () {
    $('.forward-btn').on('click', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("admin/dashboard/employee/forward") }}/' + id,
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) { if (res.success) location.reload(); else alert(res.message); }
        });
    });
});
</script>
@endsection