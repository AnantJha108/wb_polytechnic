@extends('backend.layout.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col mt-4">
        <div class="row">
            <h2>Employee List</h2>

            @if(session('success'))
            <p style="color:green">{{ session('success') }}</p>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $emp)
                            <tr>
                                <td>{{ $emp->id }}</td>
                                <td><a class="text-decoration-none text-dark" href="{{ url('admin/employee/'.$emp->id) }}">{{ $emp->name }}</a></td>
                                <td><a class="text-decoration-none text-dark" href="{{ url('admin/employee/'.$emp->id) }}">{{ $emp->email}}</a></td>
                                <td><a class="text-decoration-none text-dark" href="{{ url('admin/employee/'.$emp->id) }}">{{ $emp->phone }}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection