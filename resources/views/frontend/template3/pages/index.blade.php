@extends('frontend.template1.layout.app')

@section('title', $college->name)

@section('content')
<!-- Hero Banner -->
@if($page)
<section class="hero">
    <div>
        @if($bannerUrl)
        <img src="{{ $bannerUrl }}" width="100%" class="img-fluid">
        @endif
    </div>
</section>


<!-- Welcome Section -->

<section class="py-5">

    <div class="container text-center">

        <h2 class="section-title">
            Welcome to <span class="text-success">{{ $college->name }}</span>
        </h2>

        <p>
            @if($page)
            {!! $page->description !!}
            @endif
        </p>

    </div>

</section>


<!-- Cards Section -->

<section class="blue-bg">

    <div class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card card-custom text-center">
                    <img src="https://polytechnic.wbtetsd.gov.in/themes/main/images/img1.jpg" class="card-img">
                    <div class="card-body">
                        <h5 class="card-title">Courses Offered</h5>
                        <p>Computer Science & Tech<br>Electrical Engineering</p>
                        <a href="#" class="btn btn-outline-success">View More</a>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card card-custom text-center">
                    <img src="https://polytechnic.wbtetsd.gov.in/themes/main/images/img2.jpg" class="card-img">
                    <div class="card-body">
                        <h5 class="card-title">Facilities</h5>
                        <p>Academic building, labs, campus facilities etc.</p>
                        <a href="#" class="btn btn-outline-success">View More</a>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card card-custom text-center">
                    <img src="https://polytechnic.wbtetsd.gov.in/themes/main/images/img3.jpg" class="card-img">
                    <div class="card-body">
                        <h5 class="card-title">Admission</h5>
                        <p>Government Polytechnic admission information.</p>
                        <a href="#" class="btn btn-outline-success">View More</a>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card card-custom text-center">
                    <img src="https://polytechnic.wbtetsd.gov.in/themes/main/images/img4.jpg" class="card-img">
                    <div class="card-body">

                        <h5 class="card-title">Institution</h5>

                        <p>Located near Bhutan gateway in Alipurduar.</p>

                        <a href="#" class="btn btn-outline-success">View More</a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<section class="container p-5">
        <div class="row align-items-center">
            <div class="col-md-6">
                @if($principleImageUrl)
                <img src="{{ $principleImageUrl }} "width="400">
                @endif
            </div>

            <div class="col-md-6 bg-success text-white p-5">
                <h5 class="h4">Principal Message</h5>
                <p>
                    {!! $page->principle_message !!}
                <p class="py-1 m-0">Thanks</p>
                <p class="py-1 m-0">Principal</p>
                <p class="py-1 m-0">{{ $college->name }}</p>
                </p>

            </div>

        </div>

    </section>

    <!-- NEWS SECTION -->

    <section class="container py-5">

        <div class="row">

            <div class="col-md-6">

                <h4 class="text-success">News & Events</h4>

                @forelse ($newsItems ?? [] as $item)
                <div class="card news-card p-3 mb-3">
                    <h6>{{ $item->title }}</h6>
                    <p>{{ Str::limit($item->description, 150) }}</p>

                    @if ($item->files->isNotEmpty())
                    @foreach ($item->files as $file)
                    <a href="{{ route('newsEvent.download', $file->id) }}" class="btn btn-success btn-sm mb-1">
                        Download {{ $item->files->count() > 1 ? '(' . $file->original_name . ')' : '' }}
                    </a>
                    @endforeach
                    @endif
                </div>
                @empty
                <p class="text-muted">No news or events available right now.</p>
                @endforelse

            </div>


            <div class="col-md-6">

                <h4 class="text-success">Notice & Announcement</h4>

                @forelse ($noticeItems ?? [] as $item)
                <div class="card news-card p-3 mb-3">
                    <h6>{{ $item->title }}</h6>
                    <p>{{ Str::limit($item->description, 150) }}</p>

                    @if ($item->files->isNotEmpty())
                    @foreach ($item->files as $file)
                    <a href="{{ route('newsEvent.download', $file->id) }}" class="btn btn-success btn-sm mb-1">
                        Download {{ $item->files->count() > 1 ? '(' . $file->original_name . ')' : '' }}
                    </a>
                    @endforeach
                    @endif
                </div>
                @empty
                <p class="text-muted">No notices or announcements right now.</p>
                @endforelse

            </div>

        </div>

    </section>
@else

    <div class="alert alert-warning text-center mt-5">
        <h5>No data available.</h5>
    </div>

@endif
@endsection