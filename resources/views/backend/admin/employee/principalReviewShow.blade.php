@extends('backend.layout.app')
@section('title', $employee->first_name . ' ' . $employee->last_name)
@section('content')
<div class="row" style="height:100vh; overflow:hidden;">
    @include('backend.partials.side')
    <div class="col d-flex flex-column" style="height:100vh; overflow:hidden;">
        <div class="p-3 border-bottom bg-white" style="flex-shrink:0;">
            <a href="{{ url('admin/dashboard/employeeReviewPrincipal/index') }}" class="btn btn-sm btn-secondary">← Back</a>
        </div>

        <div class="row flex-grow-1" style="overflow:hidden;">
            <div class="col-md-8 h-100" style="overflow-y:auto;">
                <div class="p-3">
                    <div class="card" id="empCard" data-emp-id="{{ $employee->id }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h2 class="h4 mb-0">{{ $employee->first_name }} {{ $employee->last_name }}</h2>
                                <span class="badge bg-warning text-dark">{{ ucfirst(str_replace('_', ' ', $employee->status)) }}</span>
                            </div>

                            <p>Employee ID: {{ $employee->employee_id }}</p>
                            <p>Date of Birth: {{ $employee->date_of_birth }}</p>
                            <p>Mobile: {{ $employee->mobile_no }} — Email: {{ $employee->email }}</p>

                            @if ($employee->status === 'forwarded_to_principal')
                                <button type="button" class="btn btn-success forward-btn">Forward to Director</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 h-100 border-start" style="overflow-y:auto;">
                <div class="p-3">
                    <h6 class="mb-3">Activity Log</h6>
                    @forelse ($logs as $log)
                        <div class="border rounded p-2 small mb-2">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</span>
                                <span class="text-muted">{{ $log->created_at->format('d M, h:i A') }}</span>
                            </div>
                            <div class="text-muted">By: {{ $log->performer->username ?? 'N/A' }}</div>
                        </div>
                    @empty
                        <p class="text-muted small">No activity yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(function () {
    const empId = $('#empCard').data('emp-id');
    $('.forward-btn').on('click', function () {
        $.ajax({
            url: '{{ url("admin/dashboard/employeeReviewPrincipal/forward") }}/' + empId,
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) { if (res.success) location.reload(); else alert(res.message); }
        });
    });
});
</script>
@endsection