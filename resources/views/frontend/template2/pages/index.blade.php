@extends('frontend.template1.layout.app')

@section('title', $college->name)

@section('content')
<!-- Hero Slider -->
@if($page)
<div>
    @if($bannerUrl)
    <img src="{{ $bannerUrl }}" width="100%" class="img-fluid">
    @endif
</div>

<!-- Committee Section -->

<section class="py-5">

    <div class="container">

        <div class="row g-4">

            <div class="col-md-4">
                <div class="committee-card">
                    <i class="bi bi-people fs-1 text-warning"></i>
                    <h5 class="mt-3">Student Welfare Committee</h5>
                </div>
            </div>

            <div class="col-md-4">
                <div class="committee-card">
                    <i class="bi bi-shield-check fs-1 text-warning"></i>
                    <h5 class="mt-3">Internal Complaint Committee</h5>
                </div>
            </div>

            <div class="col-md-4">
                <div class="committee-card">
                    <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                    <h5 class="mt-3">Anti Ragging Committee</h5>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Welcome Section -->

<section class="py-5 bg-light">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-md-12">
                <h2>
                    Welcome to <span class="text-warning">{{ $college->name }}</span>
                </h2>
                <p>
                    @if($page)
                    {!! $page->description !!}
                    @endif
                </p>
                <button class="btn btn-warning">Read More</button>
            </div>
        </div>

    </div>
</section>

<!-- Features -->

<section class="feature-section mt-5">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-3">
                <div class="feature-box">
                    <div class="feature-icon">
                        <div class="card position-relative">
                            <img src="https://polytechnic.wbtetsd.gov.in/themes/main/images/img1.jpg" class="card-img">
                            <div class="card-overlay">
                                Courses Offered
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="feature-box">
                    <div class="feature-icon">
                        <div class="card position-relative">
                            <img src="https://polytechnic.wbtetsd.gov.in/themes/main/images/img2.jpg" class="card-img">
                            <div class="card-overlay">
                                Facilities
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="feature-box">
                    <div class="feature-icon">
                        <div class="card position-relative">
                            <img src="https://polytechnic.wbtetsd.gov.in/themes/main/images/img3.jpg" class="card-img">
                            <div class="card-overlay">
                                Admission
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="feature-box">
                    <div class="feature-icon">
                        <div class="card position-relative">
                            <img src="https://polytechnic.wbtetsd.gov.in/themes/main/images/img4.jpg" class="card-img">
                            <div class="card-overlay">
                                Institution
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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

<!-- Principal Message -->

<section class="message-section mt-4 mb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h4 class="mb-3">Principal Message </h4>
                <p>
                    {!! $page->principle_message !!}
                </p>
                <p class="py-1 m-0">Thanks</p>
                <p class="py-1 m-0">Principal</p>
                <p class="py-1 m-0">{{ $college->name }}</p>

            </div>

            <div class="col-md-6 text-center">

                @if($principleImageUrl)
                <img src="{{ $principleImageUrl }}" width="450">
                @endif

            </div>

        </div>

    </div>
</section>
@else
    <div class="alert alert-warning text-center mt-5">
        <h5>No data available.</h5>
    </div>

@endif
@endsection