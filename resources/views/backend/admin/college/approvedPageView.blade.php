@extends('backend.layout.app')

@section('title', 'Admin Dashboard || ' . $college->name)

@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4">{{ $college->name }} — Approved Page</h2>
            <a href="{{ url('admin/dashboard/college/index') }}" class="btn btn-secondary btn-sm">
                &larr; Back to Colleges
            </a>
        </div>

        @if($page)
        <div class="card">
            <div class="card-body">

                @if($page->banner_url)
                    <img src="{{ $page->banner_url }}" class="w-100 mb-3 rounded" style="max-height:300px; object-fit:cover;">
                @endif

                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="mb-0">{{ ucfirst($page->page) }}</h5>
                    <span class="badge bg-success">Approved</span>
                </div>

                <h6>Description</h6>
                <p style="white-space: pre-line;">{{ $page->description }}</p>

                <div class="d-flex align-items-center mt-4">
                    @if($page->principle_image_url)
                        <img src="{{ $page->principle_image_url }}" width="80" height="80" class="rounded-circle me-3" style="object-fit:cover;">
                    @endif
                    <div>
                        <h6 class="mb-1">Principal's Message</h6>
                        <p class="mb-0" style="white-space: pre-line;">{{ $page->principle_message }}</p>
                    </div>
                </div>

            </div>
        </div>
        @else
            <div class="alert alert-secondary">
                No approved page is available for this college yet.
            </div>
        @endif

    </div>
</div>
@endsection