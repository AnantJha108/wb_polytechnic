@extends('backend.layout.app')

@section('title', 'Admin Dashboard || College Page Details')

@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-5 mt-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4">{{ $page->college->name ?? 'N/A' }} — Page Details</h2>
            <a href="{{ url('admin/dashboard/collegepage/index') }}" class="btn btn-secondary btn-sm">&larr; Back</a>
        </div>
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h5>Welcome Message</h5>
                    <p>{{ $page->description ?? 'No message added.' }}</p>
                </div>
            </div>
        </div>

        @if($bannerUrl)
        <img src="{{ $bannerUrl }}" class="img-fluid w-100 rounded mb-4" style="max-height:300px; object-fit:cover;">
        @endif

        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        @if($principleImageUrl)
                        <img src="{{ $principleImageUrl }}" class="rounded-circle" width="100" height="100"
                            style="object-fit:cover;">
                        @else
                        <div class="border rounded-circle d-flex align-items-center justify-content-center mx-auto"
                            style="width:100px; height:100px;">No Photo</div>
                        @endif
                    </div>
                    <div class="col-md-10">
                        <h5>Principal's Message</h5>
                        <p>{{ $page->principle_message ?? 'No message added.' }}</p>
                    </div>
                </div>

                <a href="{{ url('admin/dashboard/collegepage/edit/' . $page->id) }}"
                    class="btn btn-primary mt-3">Edit</a>
            </div>
        </div>
    </div>
</div>
@endsection