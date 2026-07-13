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

<!-- Notice Section -->

<section class="py-5">

    <div class="container">

        <h2 class="text-center mb-5">
            Our <span class="text-warning">Notice & Announcement</span>
        </h2>

        <div class="notice-box">

            <h5>Notice of Assessor</h5>
            <p>Notice from Council</p>

            <a href="#" class="btn btn-outline-warning">Download PDF</a>

        </div>

    </div>
</section>

<!-- News Section -->

<section class="py-5 bg-light">

    <div class="container">

        <h2 class="text-center mb-5">
            Upcoming <span class="text-warning">News & Events</span>
        </h2>

        <div class="notice-box">

            <h5>National Webinar on Data Dependant Society</h5>

            <p>Registration Link Available</p>

            <a class="btn btn-outline-warning">Download PDF</a>

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