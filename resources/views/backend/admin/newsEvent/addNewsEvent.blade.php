@extends('backend.layout.app')
@section('title', 'Add News/Notice')
@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-5 mt-3">
        <div class="card">
            <div class="card-body">
                <h2 class="h4">Add News / Event / Notice / Announcement</h2>

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

                <form action="{{ url('admin/dashboard/newsEvent/store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label>College</label>
                        <input type="text" class="form-control" value="{{ $college->name }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label>Type</label>
                        <select name="type" class="form-control @error('type') is-invalid @enderror">
                            <option value="">-- Select Type --</option>
                            <option value="news_events" {{ old('type')=='news_events' ? 'selected' : '' }}>News & Events
                            </option>
                            <option value="notice_announcement" {{ old('type')=='notice_announcement' ? 'selected' : ''
                                }}>Notice & Announcement</option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" rows="6"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label>Attach Files (Word, PDF, PPT — max 5 MB each, multiple allowed)</label>
                        <input type="file" name="files[]" multiple
                            class="form-control @error('files.*') is-invalid @enderror">
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

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection