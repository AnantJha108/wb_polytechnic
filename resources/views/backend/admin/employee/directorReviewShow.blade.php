@extends('backend.layout.app')
@section('title', $employee->first_name . ' ' . $employee->last_name)
@section('content')
<div class="row" style="height:100vh; overflow:hidden;">
    @include('backend.partials.side')
    <div class="col d-flex flex-column" style="height:100vh; overflow:hidden;">
        <div class="p-3 border-bottom bg-white" style="flex-shrink:0;">
            <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary">← Back</a>
        </div>

        <div class="row flex-grow-1" style="overflow:hidden;">
            <div class="col-md-8 h-100" style="overflow-y:auto;">
                <div class="p-3">
                    <div class="card" id="empCard" data-emp-id="{{ $employee->id }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h2 class="h4 mb-1">{{ $employee->first_name }} {{ $employee->last_name }}</h2>
                                    <span class="badge
                                        @if($employee->status == 'approved') bg-success
                                        @elseif($employee->status == 'rejected') bg-danger
                                        @elseif($employee->status == 'reverted') bg-warning text-dark
                                        @else bg-info @endif">
                                        {{ ucfirst(str_replace('_', ' ', $employee->status)) }}
                                    </span>
                                </div>

                                @if ($employee->status === 'forwarded_to_director')
                                <div>
                                    <button type="button" class="btn btn-sm btn-success approve-btn">Approve</button>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#revertModal">Revert</button>
                                </div>
                                @endif
                            </div>

                            @if ($employee->status === 'approved')
                                <div class="alert alert-success">This employee record has been approved.</div>
                            @elseif ($employee->status === 'rejected' && $employee->reject_reason)
                                <div class="alert alert-danger"><strong>Rejected:</strong> {{ $employee->reject_reason }}</div>
                            @elseif ($employee->status === 'reverted' && $employee->revert_reason)
                                <div class="alert alert-warning"><strong>Reverted:</strong> {{ $employee->revert_reason }}</div>
                            @endif

                            <p class="text-muted">{{ $employee->college->name }}</p>
                            <h6 class="mt-3">Employee ID</h6><p>{{ $employee->employee_id }}</p>
                            <h6>Date of Birth</h6><p>{{ $employee->date_of_birth }}</p>
                            <h6>Contact</h6><p>{{ $employee->mobile_no }} — {{ $employee->email }}</p>

                            @if ($employee->academicDetails->isNotEmpty())
                                <h6 class="mt-3">Academic Details</h6>
                                <table class="table table-sm">
                                    <thead><tr><th>Level</th><th>Qualification</th><th>Discipline</th><th>Year</th></tr></thead>
                                    <tbody>
                                        @foreach ($employee->academicDetails as $ac)
                                            <tr><td>{{ $ac->level }}</td><td>{{ $ac->qualification }}</td><td>{{ $ac->discipline_trade }}</td><td>{{ $ac->passing_year }}</td></tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
                                <span class="fw-semibold
                                    @if($log->action == 'approve') text-success
                                    @elseif($log->action == 'reject') text-danger
                                    @elseif($log->action == 'revert') text-warning
                                    @else text-secondary @endif">
                                    {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                </span>
                                <span class="text-muted">{{ $log->created_at->format('d M, h:i A') }}</span>
                            </div>
                            <div class="text-muted">By: {{ $log->performer->username ?? 'N/A' }}</div>
                            @if ($log->reason)<div class="mt-1">{{ $log->reason }}</div>@endif
                        </div>
                    @empty
                        <p class="text-muted small">No activity yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5>Reject Reason</h5></div>
        <div class="modal-body"><textarea id="rejectReason" class="form-control" rows="3"></textarea></div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-danger reject-submit-btn">Reject</button>
        </div>
    </div></div>
</div>

<div class="modal fade" id="revertModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5>Revert Reason</h5></div>
        <div class="modal-body"><textarea id="revertReason" class="form-control" rows="3"></textarea></div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-warning revert-submit-btn">Revert</button>
        </div>
    </div></div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(function () {
    const empId = $('#empCard').data('emp-id');
    function post(url, data, cb) {
        $.ajax({
            url: url, method: 'POST',
            data: Object.assign({ _token: '{{ csrf_token() }}' }, data),
            success: cb,
            error: xhr => alert(xhr.responseJSON?.message || 'Something went wrong.')
        });
    }
    $('.approve-btn').on('click', () => post('{{ url("admin/dashboard/employeeReviewDirector/approve") }}/' + empId, {}, r => r.success && location.reload()));
    $('.reject-submit-btn').on('click', () => post('{{ url("admin/dashboard/employeeReviewDirector/reject") }}/' + empId, { reason: $('#rejectReason').val() }, r => r.success && location.reload()));
    $('.revert-submit-btn').on('click', () => post('{{ url("admin/dashboard/employeeReviewDirector/revert") }}/' + empId, { reason: $('#revertReason').val() }, r => r.success && location.reload()));
});
</script>
@endsection