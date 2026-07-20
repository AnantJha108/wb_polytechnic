@extends('backend.layout.app')
@section('title', 'View College Index Page')
@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-5 mt-3">
        <h2 class="h4 mb-4">All Colleges</h2>
        <div class="row">
            @foreach ($colleges as $college)
                <div class="col-md-4 mb-3">
                    <div class="card p-3">
                        <h5>{{ $college->name }}</h5>
                        <a href="{{ url('admin/dashboard/collegeIndexPreview/show/' . $college->id) }}"
                           class="btn btn-sm btn-primary mt-2">
                            View Index Page
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection