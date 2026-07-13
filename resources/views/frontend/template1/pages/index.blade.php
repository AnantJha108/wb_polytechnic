@extends('frontend.template1.layout.app')

@section('title', $college->name)

@section('content')
@if($page)
<div>
    @if($bannerUrl)
    <img src="{{ $bannerUrl }}" width="100%" class="img-fluid">
    @endif
</div>
<div class="contianer">
    <!-- WELCOME SECTION -->
    <section class="container py-5">
        <div class="row">
            <div class="col-lg-6">
                <h3 class="text-success">
                    Welcome to {{ $college->name }}
                </h3>
                <p>
                    @if($page)
                    {!! $page->description !!}
                    @endif
                </p>
            </div>

            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="card position-relative">
                            <img src="https://polytechnic.wbtetsd.gov.in/themes/main/images/img1.jpg" class="card-img">
                            <div class="card-overlay">
                                Courses Offered
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card position-relative">
                            <img src="https://polytechnic.wbtetsd.gov.in/themes/main/images/img2.jpg" class="card-img">
                            <div class="card-overlay">
                                Facilities
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card position-relative">
                            <img src="https://polytechnic.wbtetsd.gov.in/themes/main/images/img3.jpg" class="card-img">
                            <div class="card-overlay">
                                Admission
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
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
    </section>


    <!-- PRINCIPAL MESSAGE -->

    <section class="container pb-5">
        <div class="row">
            <div class="col-md-6">
                @if($principleImageUrl)
                <img src="{{ $principleImageUrl }}" width="100%">
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


    <!-- COMMITTEE SECTION -->

    <section class="committee">

        <div class="container">

            <div class="row text-center">

                <div class="col-md-4">

                    <h5>Student Welfare Committee</h5>

                </div>

                <div class="col-md-4">

                    <h5>Internal Complaint Committee</h5>

                </div>

                <div class="col-md-4">

                    <h5>Anti Ragging Committee</h5>

                </div>

            </div>

        </div>

    </section>


    <!-- NEWS SECTION -->

    <section class="container py-5">

        <div class="row">

            <div class="col-md-6">

                <h4 class="text-success">News & Events</h4>

                <div class="card news-card p-3">

                    <h6>Student Grievance Redressal Link</h6>

                    <p>
                        Student grievance redressal link of department of technical education.
                    </p>

                    <button class="btn btn-success btn-sm">Download</button>

                </div>

            </div>


            <div class="col-md-6">

                <h4 class="text-success">Notice & Announcement</h4>

                <div class="card news-card p-3">

                    <h6>Summer Vacation Notice</h6>

                    <p>
                        SUMMER VACATION from 28 April to 10 June.
                    </p>

                    <button class="btn btn-success btn-sm">Download</button>

                </div>

            </div>

        </div>

    </section>
</div>
@else

<div class="alert alert-warning text-center mt-5">
    <h5>No data available.</h5>
</div>

@endif
@endsection