@extends('backend.layout.app')

@section('title', 'Admin Dashboard || View College')

@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col mt-4">
        <div class="row">
            <h2 class="h4">College List</h2>

            @if(session('success'))
            <p style="color:green">{{ session('success') }}</p>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>College ID</th>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Slug</th>
                            <th>Contact</th>
                            <th>District</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($colleges as $key => $college)
                        <tr onclick="window.location='{{ url('admin/dashboard/college/show/' . $college->id) }}'"
                            style="cursor: pointer;">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $college->college_id }}</td>
                            <td>
                                @if($college->logo_url)
                                <img src="{{ $college->logo_url }}" width="60" height="60" alt="{{ $college->name }}">
                                @else
                                N/A
                                @endif
                            </td>
                            <td>{{ $college->name }}</td>
                            <td>{{ $college->email }}</td>
                            <td>{{ $college->slug }}</td>
                            <td>{{ $college->contact_no }}</td>
                            <td>{{ $college->district }}</td>
                            <td>
                                @if($college->status == 1)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ url('admin/dashboard/college/edit/' . $college->id) }}"
                                        class="btn btn-sm btn-primary me-2">Edit</a>

                                    <form action="{{ url('admin/dashboard/college/destroy/' . $college->id) }}"
                                        method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No colleges found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection