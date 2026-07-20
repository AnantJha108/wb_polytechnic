@extends('backend.layout.app')
@section('title', $college->name . ' — News & Notice')
@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-4 mt-3">

        <a href="{{ url('admin/dashboard/newsEventDirectorView/index') }}" class="btn btn-sm btn-secondary mb-3">
            ← Back to College List
        </a>

        <h2 class="h4 mb-4">{{ $college->name }}</h2>

        <div class="row">

            {{-- LEFT: News & Events --}}
            <div class="col-6">
                <h5 class="mb-3">News & Events</h5>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($newsItems as $item)
                            <tr>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ url('admin/dashboard/newsEventDirectorView/show/' . $item->id) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">No approved News & Events yet.</td></tr>
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
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($noticeItems as $item)
                            <tr>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ url('admin/dashboard/newsEventDirectorView/show/' . $item->id) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">No approved Notice & Announcement yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection