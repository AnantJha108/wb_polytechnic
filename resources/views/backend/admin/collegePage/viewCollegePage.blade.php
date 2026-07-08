@extends('backend.layout.app')

@section('title', 'Admin Dashboard || College Pages')

@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4">College Pages</h2>
            <a href="{{ url('admin/dashboard/collegepage/create') }}" class="btn btn-primary btn-sm">
                + Add College Page
            </a>
        </div>

        @if(session('success'))
            <p style="color:green">{{ session('success') }}</p>
        @endif
        @if(session('error'))
            <p style="color:red">{{ session('error') }}</p>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>College</th>
                        <th>Page</th>
                        <th>Banner</th>
                        <th>Principal Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $key => $page)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $page->college->name ?? 'N/A' }}</td>
                        <td>{{ $page->page }}</td>
                        <td>
                            @if($page->banner_url)
                                <img src="{{ $page->banner_url }}" width="80" height="50" style="object-fit:cover;">
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if($page->principle_image_url)
                                <img src="{{ $page->principle_image_url }}" width="50" height="50" style="object-fit:cover; border-radius:50%;">
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <a href="{{ url('admin/dashboard/collegepage/show/' . $page->id) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ url('admin/dashboard/collegepage/edit/' . $page->id) }}" class="btn btn-sm btn-primary">Edit</a>

                            <form action="{{ url('admin/dashboard/collegepage/destroy/' . $page->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No college page found for your college yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection