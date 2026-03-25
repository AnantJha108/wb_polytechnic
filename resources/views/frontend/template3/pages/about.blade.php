@extends('frontend.template3.layout.app')

@section('title', 'About || Alipurduar Damanpur Government Polytechnic')

@section('content')
<div class="contianer mt-5 mb-5">
    <section>
        <div>
            <div class="d-flex">
                <h2>About || </h2>
                <h2>{{$college->name }}</h2>
            </div>
            <p>{{ $page->description }}</p>
        </div>
    </section>
</div>
@endsection