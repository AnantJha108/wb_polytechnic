@extends('frontend.template1.layout.app')

@section('title', 'About || ' . $college->name)

@section('content')
<div class="container px-5 mt-5 mb-5">
    <section>
        <div>
            <div class="d-flex">
                <h2>About || </h2>
                <h2>{{ $college->name }}</h2>
            </div>

            @if ($aboutPage)
                <p class="mt-3">
                    {!! nl2br(e($aboutPage->description)) !!}
                </p>
            @else
                <p class="mt-3 text-muted">
                    About page content is not available yet.
                </p>
            @endif
        </div>
    </section>
</div>
@endsection