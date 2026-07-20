@extends('backend.layout.app')
@section('title', $college->name . ' - Home Preview')
@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-5 mt-3">

        <a href="{{ url('admin/dashboard/collegeIndexPreview/index') }}" class="btn btn-sm btn-secondary mb-3">
            ← Back to College List
        </a>

        <h2 class="h4 mb-4">{{ $college->name }}</h2>

        @if (!$page)
            <div class="alert alert-warning">
                There is no approved page for this college yet.
            </div>
        @else
            @if ($bannerUrl)
                <img src="{{ $bannerUrl }}" class="img-fluid mb-3" style="max-height:300px; object-fit:cover;">
            @endif

            <p>{{ $page->description }}</p>

            <hr>

            <div class="d-flex align-items-center gap-3 mb-2">
                @if ($principleImageUrl)
                    <img src="{{ $principleImageUrl }}" width="80" height="80" style="border-radius:50%; object-fit:cover;">
                @endif
                <h5 class="mb-0">Principal's Message</h5>
            </div>
            <p>{{ $page->principle_message }}</p>
        @endif

    </div>
</div>
@endsection