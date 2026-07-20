@extends('backend.layout.app')
@section('title', 'About Page')
@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-5 mt-3">

        <div class="card mb-4">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h2 class="h4 mb-0">About Page</h2>

                    @if ($current)
                        <span class="badge
                            @if($current->status == 'draft') bg-secondary
                            @elseif($current->status == 'forwarded') bg-warning text-dark
                            @elseif($current->status == 'approved') bg-success
                            @elseif($current->status == 'rejected') bg-danger
                            @else bg-info @endif">
                            {{ ucfirst($current->status) }}
                        </span>
                    @endif
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- CASE 1: No record yet, or current is rejected → ADD form --}}
                @if (!$current || $current->status === 'rejected')

                    @if ($current && $current->status === 'rejected' && $current->reject_reason)
                        <div class="alert alert-danger">
                            <strong>Previous submission was rejected:</strong> {{ $current->reject_reason }}
                            <div class="small mt-1">It has been archived below. Please submit a new About page.</div>
                        </div>
                    @endif

                    <form action="{{ url('admin/dashboard/aboutPage/store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>College</label>
                            <input type="text" class="form-control" value="{{ $college->name }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" rows="8"
                                class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Save About Page</button>
                    </form>

                {{-- CASE 2: draft, reverted, or approved → EDIT form (editable even after approval) --}}
                @elseif (in_array($current->status, ['draft', 'reverted', 'approved']))

                    @if ($current->status === 'reverted' && $current->revert_reason)
                        <div class="alert alert-warning">
                            <strong>Reverted — Reason:</strong> {{ $current->revert_reason }}
                        </div>
                    @endif

                    @if ($current->status === 'approved')
                        <div class="alert alert-success">
                            This page is currently <strong>live</strong>. Editing it will reset the status to Draft and require re-approval.
                        </div>
                    @endif

                    <form action="{{ url('admin/dashboard/aboutPage/update/' . $current->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>College</label>
                            <input type="text" class="form-control" value="{{ $college->name }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" rows="8"
                                class="form-control @error('description') is-invalid @enderror">{{ old('description', $current->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ $current->status === 'approved' ? 'Update Live Page' : 'Update' }}
                            </button>

                            @if (in_array($current->status, ['draft', 'reverted']))
                                <button type="button" class="btn btn-success forward-btn" data-id="{{ $current->id }}">Forward</button>
                            @endif

                            <button type="button" class="btn btn-outline-danger ms-auto delete-btn" data-id="{{ $current->id }}">Delete</button>
                        </div>
                    </form>

                {{-- CASE 3: forwarded → read-only, pending review --}}
                @elseif ($current->status === 'forwarded')

                    <div class="alert alert-warning">This page is forwarded and pending review. It cannot be edited right now.</div>

                    <div class="mb-3">
                        <label class="fw-bold">College</label>
                        <p>{{ $college->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Description</label>
                        <p>{!! nl2br(e($current->description)) !!}</p>
                    </div>

                @endif

            </div>
        </div>

        {{-- Archived (superseded rejected) records — Operator only, view + delete --}}
        @if ($archived->isNotEmpty())
            <h5 class="mb-3">Archived Submissions</h5>

            @foreach ($archived as $old)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge bg-secondary">Archived</span>
                                <span class="badge
                                    @if($old->status == 'rejected') bg-danger
                                    @elseif($old->status == 'approved') bg-success
                                    @else bg-info @endif ms-1">
                                    {{ ucfirst($old->status) }}
                                </span>
                                <div class="small text-muted mt-1">Submitted {{ $old->created_at->format('d M Y, h:i A') }}</div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $old->id }}">Delete</button>
                        </div>

                        @if ($old->status === 'rejected' && $old->reject_reason)
                            <div class="alert alert-danger mt-2 mb-2 py-2 small">
                                <strong>Rejected:</strong> {{ $old->reject_reason }}
                            </div>
                        @endif

                        <p class="mt-2 mb-0">{{ \Illuminate\Support\Str::limit($old->description, 150) }}</p>
                    </div>
                </div>
            @endforeach
        @endif

    </div>
</div>

@endsection