@extends('backend.layout.app')

@section('title', 'Admin Dashboard || College Page')

@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col mt-4">
        @php $latest = $pages->first(); @endphp

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4">College Page</h2>
            @if(!$latest || $latest->status === 'rejected')
            <a href="{{ url('admin/dashboard/collegepage/create') }}" id="addPageBtn" class="btn btn-primary btn-sm">
                + Add College Page
            </a>
            @endif
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <div id="alertBox"></div>

        @if($latest && $latest->status === 'rejected' && $latest->reject_reason)
        <div class="alert alert-danger" id="reasonBox">
            <strong>Rejected — Reason:</strong> {{ $latest->reject_reason }}
        </div>
        @elseif($latest && $latest->status === 'reverted' && $latest->revert_reason)
        <div class="alert alert-warning" id="reasonBox">
            <strong>Reverted — Reason:</strong> {{ $latest->revert_reason }}
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Page</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="pageTableBody">
                    @forelse($pages as $key => $page)
                    <tr data-page-id="{{ $page->id }}">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $page->page }}</td>
                        <td class="status-cell">
                            <span class="badge
                                @if($page->status == 'draft') bg-secondary
                                @elseif($page->status == 'forwarded') bg-warning text-dark
                                @elseif($page->status == 'approved') bg-success
                                @elseif($page->status == 'rejected') bg-danger
                                @else bg-info @endif">
                                {{ ucfirst($page->status) }}
                            </span>
                        </td>
                        <td class="action-cell">
                            <a href="{{ url('admin/dashboard/collegepage/show/' . $page->id) }}"
                                class="btn btn-sm btn-info">View</a>

                            @if($page->id === $latestId)
                                @if(in_array($page->status, ['draft', 'reverted']))
                                <a href="{{ url('admin/dashboard/collegepage/edit/' . $page->id) }}"
                                    class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ url('admin/dashboard/collegepage/destroy/' . $page->id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                                <button type="button" class="btn btn-sm btn-success forward-btn"
                                    data-id="{{ $page->id }}">Forward</button>
                                @elseif($page->status === 'forwarded')
                                <button class="btn btn-sm btn-success" disabled>Forwarded</button>
                                @elseif($page->status === 'rejected')
                                <form action="{{ url('admin/dashboard/collegepage/destroy/' . $page->id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                                @endif
                            @else
                                <span class="small badge bg-warning text-white ">Archived</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No college page found for your college yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection