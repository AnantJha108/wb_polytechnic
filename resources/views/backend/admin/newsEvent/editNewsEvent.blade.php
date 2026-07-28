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

                <form action="{{ url('admin/dashboard/newsEvent/update/' . $item->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label>College</label>
                        <input type="text" class="form-control" value="{{ $college->name }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', $item->title) }}">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label>Type</label>
                        <select name="type" class="form-control @error('type') is-invalid @enderror">
                            <option value="news_events" {{ old('type', $item->type) == 'news_events' ? 'selected' : ''
                                }}>News & Events</option>
                            <option value="notice_announcement" {{ old('type', $item->type) == 'notice_announcement' ?
                                'selected' : '' }}>Notice & Announcement</option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" rows="6"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description', $item->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="mb-0">Existing Files</label>
                            <button type="button" id="toggleExistingFilesBtn" class="btn btn-sm btn-outline-secondary">
                                View Existing Files ({{ $item->files->count() }})
                            </button>
                        </div>

                        <div id="existingFilesList" class="mt-2" style="display:none;">
                            @if ($item->files->isNotEmpty())
                            <ul class="list-group">
                                @foreach ($item->files as $index => $file)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted small flex-grow-1 mx-2 text-truncate">{{
                                            $file->original_name }}</span>
                                        <button type="button" class="btn btn-sm btn-outline-warning edit-file-toggle"
                                            data-target="editFile{{ $file->id }}">Edit</button>
                                        <a href="{{ url('admin/dashboard/newsEvent/downloadFile/' . $file->id) }}"
                                            class="btn btn-sm btn-outline-success ms-2">
                                            Download {{ $index + 1 }}
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-file-btn ms-1"
                                            data-id="{{ $file->id }}">Remove</button>
                                    </div>

                                    {{-- Hidden replace input, revealed only when "Edit" is clicked for THIS file --}}
                                    <div id="editFile{{ $file->id }}" class="mt-2" style="display:none;">
                                        <label class="small text-muted mb-1">Upload a replacement for this file:</label>
                                        <input type="file" name="replace_file[{{ $file->id }}]"
                                            class="form-control form-control-sm">
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <p class="text-muted small mt-2">No files attached yet.</p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="mb-0">Attach New Files (Word, PDF, PPT — max 5 MB each)</label>
                            <button type="button" id="addMoreFileBtn" class="btn btn-sm btn-outline-primary">+ Add
                                More</button>
                        </div>

                        <div id="fileInputsContainer" class="mt-2"></div>

                        @error('files.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                        @if (session('temp_files'))
                        <div class="mt-2">
                            <p class="mb-1 text-muted small">Previously selected files (kept after error):</p>
                            <ul class="list-group">
                                @foreach (session('temp_files') as $index => $temp)
                                <li class="list-group-item small">
                                    {{ $temp['name'] }}
                                    <input type="hidden" name="temp_files[{{ $index }}][path]"
                                        value="{{ $temp['path'] }}">
                                    <input type="hidden" name="temp_files[{{ $index }}][name]"
                                        value="{{ $temp['name'] }}">
                                    <input type="hidden" name="temp_files[{{ $index }}][mime]"
                                        value="{{ $temp['mime'] }}">
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection