@extends('backend.layout.app')
@section('title', 'Edit News/Notice')
@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-5 mt-3">
        <div class="card">
            <div class="card-body">
                <h2 class="h4">Edit News / Notice</h2>

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

                @if ($item->status === 'reverted' && $item->revert_reason)
                    <div class="alert alert-warning">
                        <strong>Reverted — Reason:</strong> {{ $item->revert_reason }}
                    </div>
                @endif

                <form action="{{ url('admin/dashboard/newsEvent/update/' . $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label>College</label>
                        <input type="text" class="form-control" value="{{ $college->name }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $item->title) }}">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label>Type</label>
                        <select name="type" class="form-control @error('type') is-invalid @enderror">
                            <option value="news_events" {{ old('type', $item->type) == 'news_events' ? 'selected' : '' }}>News & Events</option>
                            <option value="notice_announcement" {{ old('type', $item->type) == 'notice_announcement' ? 'selected' : '' }}>Notice & Announcement</option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" rows="6" class="form-control @error('description') is-invalid @enderror">{{ old('description', $item->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    @if ($item->files->isNotEmpty())
                        <div class="mb-3">
                            <label>Existing Files</label>
                            <ul class="list-group">
                                @foreach ($item->files as $file)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="{{ url('admin/dashboard/newsEvent/downloadFile/' . $file->id) }}">{{ $file->original_name }}</a>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-file-btn" data-id="{{ $file->id }}">Remove</button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label>Attach More Files</label>
                        <input type="file" name="files[]" multiple class="form-control @error('files.*') is-invalid @enderror">
                        @error('files.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection