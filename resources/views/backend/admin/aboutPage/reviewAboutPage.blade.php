@extends('backend.layout.app')

@section('title', 'Admin Dashboard || Review About Page')

@section('content')
<div class="row" style="height:100vh; overflow:hidden;">
    @include('backend.partials.side')

    <div class="col d-flex flex-column" style="height:100vh; overflow:hidden;">

        <div class="p-3 border-bottom bg-white" style="flex-shrink:0;">
            <h2 class="h4 mb-0">Review About Page</h2>
        </div>

        <div id="alertBox" class="px-3" style="flex-shrink:0;"></div>

        @if($page)
        <div class="row flex-grow-1" style="overflow:hidden;">

            {{-- LEFT: Page details --}}
            <div class="col-md-8 h-100" style="overflow-y:auto;">
                <div class="p-3">
                    <div class="card" id="pageCard" data-page-id="{{ $page->id }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="mb-1">About</h5>
                                    <span class="badge status-badge
                                        @if($page->status == 'draft') bg-secondary
                                        @elseif($page->status == 'forwarded') bg-warning text-dark
                                        @elseif($page->status == 'approved') bg-success
                                        @elseif($page->status == 'rejected') bg-danger
                                        @else bg-info @endif">
                                        {{ ucfirst($page->status) }}
                                    </span>
                                </div>

                                <div id="actionButtons">
                                    @if($page->status === 'forwarded')
                                    <button type="button" class="btn btn-sm btn-success approve-btn">Approve</button>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#rejectModal">Reject</button>
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#revertModal">Revert</button>
                                    @endif
                                </div>
                            </div>

                            <div id="statusMessage">
                                @if($page->status === 'approved')
                                <div class="alert alert-success mb-3">This page has been approved and is live.</div>
                                @elseif($page->status === 'rejected' && $page->reject_reason)
                                <div class="alert alert-danger mb-3"><strong>Rejected — Reason:</strong> {{
                                    $page->reject_reason }}</div>
                                @elseif($page->status === 'reverted' && $page->revert_reason)
                                <div class="alert alert-warning mb-3"><strong>Reverted — Reason:</strong> {{
                                    $page->revert_reason }}</div>
                                @endif
                            </div>

                            <h6>Description</h6>
                            <p>{{ $page->description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Activity Log --}}
            <div class="col-md-4 h-100 border-start" style="overflow-y:auto;">
                <div class="p-3">
                    <h6 class="mb-3">Activity Log</h6>

                    @if($logs->isNotEmpty())
                    <div class="d-flex flex-column gap-2">
                        @foreach($logs as $log)
                        <div class="border rounded p-2 small">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold
                                        @if($log->action == 'approve') text-success
                                        @elseif($log->action == 'reject') text-danger
                                        @elseif($log->action == 'revert') text-warning
                                        @else text-secondary @endif">
                                    {{ ucfirst($log->action) }}
                                </span>
                                <span class="text-muted">{{ $log->created_at->format('d M, h:i A') }}</span>
                            </div>
                            <div class="text-muted">By: {{ $log->performer->username ?? 'N/A' }}</div>
                            <div class="text-muted">IP: {{ $log->ip_address }}</div>
                            @if($log->reason)
                            <div class="mt-1">{{ $log->reason }}</div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted small">No activity yet.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Reject Modal --}}
        <div class="modal fade" id="rejectModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Reject Reason</h5>
                    </div>
                    <div class="modal-body">
                        <textarea id="rejectReason" class="form-control" rows="3" required
                            placeholder="Explain why this page is being rejected..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger reject-submit-btn">Reject</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Revert Modal --}}
        <div class="modal fade" id="revertModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Revert Reason</h5>
                    </div>
                    <div class="modal-body">
                        <textarea id="revertReason" class="form-control" rows="3" required
                            placeholder="Explain what needs to be changed..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-warning revert-submit-btn">Revert</button>
                    </div>
                </div>
            </div>
        </div>

        @else
        <div class="p-3">
            <p class="text-muted">No About page has been submitted yet.</p>
        </div>
        @endif
    </div>
</div>

@endsection