@extends('backend.layout.app')
@section('title', 'News & Notice — Select College')
@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-5 mt-3">
        <h2 class="h4 mb-4">Select a College</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>College Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($colleges as $college)
                    <tr>
                        <td>{{ $college->name }}</td>
                        <td>
                            <a href="{{ url('admin/dashboard/newsEventDirectorView/college/' . $college->id) }}"
                               class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection