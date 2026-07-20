@extends('backend.layout.app')
@section('title', $item->title)
@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-5 mt-3">

        <a href="{{ url('admin/dashboard/newsEvent/index') }}" class="btn btn-sm btn-secondary mb-3">← Back</a>

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h2 class="h4 mb-0">{{ $item->title }}</h2>
                    <span class="badge
                        @if($item->status == 'draft') bg-secondary
                        @elseif($item->status == 'forwarded') bg-warning text-dark
                        @elseif($item->status == 'approved') bg-success
                        @elseif($item->status == 'rejected') bg-danger
                        @else bg-info @endif">
                        {{ ucfirst($item->status) }}
                    </span>
                </div>

                <p class="text-muted">{{ $item->type === 'news_events' ? 'News & Events' : 'Notice & Announcement' }}</p>

                @if ($item->status === 'rejected' && $item->reject_reason)
                    <div class="alert alert-danger"><strong>Rejected — Reason:</strong> {{ $item->reject_reason }}</div>
                @endif
                @if ($item->status === 'reverted' && $item->revert_reason)
                    <div class="alert alert-warning"><strong>Reverted — Reason:</strong> {{ $item->revert_reason }}</div>
                @endif
                @if ($item->status === 'approved')
                    <div class="alert alert-success">This item has been approved and is live.</div>
                @endif

                <h6 class="mt-4">Description</h6>
                <p>{!! nl2br(e($item->description)) !!}</p>

                @if ($item->files->isNotEmpty())
                    <h6 class="mt-4">Attached Files</h6>
                    <ul class="list-group">
                        @foreach ($item->files as $file)
                            <li class="list-group-item">
                                <a href="{{ url('admin/dashboard/newsEvent/downloadFile/' . $file->id) }}">{{ $file->original_name }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection