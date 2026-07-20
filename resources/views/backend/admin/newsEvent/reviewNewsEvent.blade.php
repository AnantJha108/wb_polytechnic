@extends('backend.layout.app')
@section('title', 'Review — ' . $item->title)
@section('content')
<div class="row" style="height:100vh; overflow:hidden;">
    @include('backend.partials.side')
    <div class="col d-flex flex-column" style="height:100vh; overflow:hidden;">

        <div class="p-3 border-bottom bg-white" style="flex-shrink:0;">
            <h2 class="h4 mb-0">Review — {{ $item->title }}</h2>
        </div>

        <div class="row flex-grow-1" style="overflow:hidden;">
            <div class="col-md-8 h-100" style="overflow-y:auto;">
                <div class="p-3">
                    <div class="card" id="itemCard" data-item-id="{{ $item->id }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="mb-1">{{ $item->type === 'news_events' ? 'News & Events' : 'Notice & Announcement' }}</h5>
                                    <span class="badge
                                        @if($item->status == 'draft') bg-secondary
                                        @elseif($item->status == 'forwarded') bg-warning text-dark
                                        @elseif($item->status == 'approved') bg-success
                                        @elseif($item->status == 'rejected') bg-danger
                                        @else bg-info @endif">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </div>

                                @if ($item->status === 'forwarded')
                                <div>
                                    <button type="button" class="btn btn-sm btn-success approve-btn">Approve</button>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#revertModal">Revert</button>
                                </div>
                                @endif
                            </div>

                            @if ($item->status === 'approved')
                                <div class="alert alert-success">This item has been approved and is live.</div>
                            @elseif ($item->status === 'rejected' && $item->reject_reason)
                                <div class="alert alert-danger"><strong>Rejected — Reason:</strong> {{ $item->reject_reason }}</div>
                            @elseif ($item->status === 'reverted' && $item->revert_reason)
                                <div class="alert alert-warning"><strong>Reverted — Reason:</strong> {{ $item->revert_reason }}</div>
                            @endif

                            <h6>Title</h6>
                            <p>{{ $item->title }}</p>

                            <h6>Description</h6>
                            <p>{!! nl2br(e($item->description)) !!}</p>

                            @if ($item->files->isNotEmpty())
                                <h6>Attached Files</h6>
                                <ul>
                                    @foreach ($item->files as $file)
                                        <li>{{ $file->original_name }}</li>
                                    @endforeach
                                </ul>
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
                                    {{ ucfirst($log->action) }}
                                </span>
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

@endsection