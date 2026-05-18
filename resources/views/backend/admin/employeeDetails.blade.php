@extends('backend.layout.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col mt-4">
        <div class="row">
            <h2>Employee Details</h2>
            
            <p><b>Name :</b> {{ $employee->name }}</p>
            <p><b>Email :</b> {{ $employee->email }}</p>
            <p><b>Phone :</b> {{ $employee->phone }}</p>

        </div>
    </div>
</div>
@endsection