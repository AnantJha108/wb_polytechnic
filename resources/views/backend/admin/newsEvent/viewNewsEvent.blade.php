@extends('backend.layout.app')
@section('title', 'News & Notice')
@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-5 mt-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h4 mb-0">News & Events / Notice & Announcement</h2>
                    <a href="{{ url('admin/dashboard/newsEvent/create') }}" class="btn btn-primary">Add New</a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                 <div class="row">

            {{-- LEFT: News & Events --}}
            <div class="col-6">
                <h5 class="mb-3">News & Events</h5>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Actions</th>
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
                                    <a href="{{ url('admin/dashboard/newsEvent/show/' . $item->id) }}" class="btn btn-sm btn-info">View</a>

                                    @if (in_array($item->status, ['draft', 'reverted']))
                                        <a href="{{ url('admin/dashboard/newsEvent/edit/' . $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <button type="button" class="btn btn-sm btn-success forward-btn" data-id="{{ $item->id }}">Forward</button>
                                    @endif

                                    @if (in_array($item->status, ['draft', 'reverted', 'rejected']))
                                        <form action="{{ url('admin/dashboard/newsEvent/destroy/' . $item->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete this item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">No items yet.</td></tr>
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
                            <th>Actions</th>
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
                                    <a href="{{ url('admin/dashboard/newsEvent/show/' . $item->id) }}" class="btn btn-sm btn-info">View</a>

                                    @if (in_array($item->status, ['draft', 'reverted']))
                                        <a href="{{ url('admin/dashboard/newsEvent/edit/' . $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <button type="button" class="btn btn-sm btn-success forward-btn" data-id="{{ $item->id }}">Forward</button>
                                    @endif

                                    @if (in_array($item->status, ['draft', 'reverted', 'rejected']))
                                        <form action="{{ url('admin/dashboard/newsEvent/destroy/' . $item->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete this item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">No items yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
            </div>
        </div>
    </div>
</div>

@endsection
