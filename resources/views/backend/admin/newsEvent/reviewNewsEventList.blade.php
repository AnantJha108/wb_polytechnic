@extends('backend.layout.app')
@section('title', 'Review News & Notice')
@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-4 mt-3">
        <h2 class="h4 mb-4">Review News & Events / Notice & Announcement</h2>

        <div class="row">

            {{-- LEFT: News & Events --}}
            <div class="col-6">
                <h5 class="mb-3">News & Events</h5>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($newsItems as $item)
                            <tr>
                                <td>{{ $item->title }}</td>
                                <td>
                                    <span class="badge
                                        @if($item->status == 'draft') bg-secondary
                                        @elseif($item->status == 'forwarded') bg-warning text-dark
                                        @elseif($item->status == 'approved') bg-success
                                        @elseif($item->status == 'rejected') bg-danger
                                        @else bg-info @endif">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ url('admin/dashboard/newsEventReview/show/' . $item->id) }}" class="btn btn-sm btn-primary">Open</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">No submissions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- RIGHT: Notice & Announcement --}}
            <div class="col-6">
                <h5 class="mb-3">Notice & Announcement</h5>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($noticeItems as $item)
                            <tr>
                                <td>{{ $item->title }}</td>
                                <td>
                                    <span class="badge
                                        @if($item->status == 'draft') bg-secondary
                                        @elseif($item->status == 'forwarded') bg-warning text-dark
                                        @elseif($item->status == 'approved') bg-success
                                        @elseif($item->status == 'rejected') bg-danger
                                        @else bg-info @endif">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ url('admin/dashboard/newsEventReview/show/' . $item->id) }}" class="btn btn-sm btn-primary">Open</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">No submissions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection