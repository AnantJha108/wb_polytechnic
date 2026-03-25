@extends('frontend.template1.layout.app')

@section('title', 'Acharya Prafulla Chandra Ray Polytechnic')

@section('content')
<!-- Hero Slider -->

<div id="hero" class="carousel slide" data-bs-ride="carousel">

    <div class="carousel-inner">

        <div class="carousel-item active">
            <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7" class="d-block w-100">
        </div>

        <div class="carousel-item">
            <img src="https://images.unsplash.com/photo-1580587771525-78b9dba3b914" class="d-block w-100">
        </div>

    </div>

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

            <div class="col-md-6">

                <h2>
                    Welcome to <span class="text-warning">Acharya Prafulla Chandra Ray Polytechnic</span>
                </h2>

                <p>
                    Acharya Prafulla Chandra Ray Polytechnic is a Technical Institute located in Kolkata.
                </p>

                <button class="btn btn-warning">Read More</button>

            </div>

        </div>

    </div>
</section>

<!-- Features -->

<section class="feature-section">

    <div class="container">

        <div class="row text-center g-4">

            <div class="col-md-3">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <h5 class="mt-3">Courses Offered</h5>
                </div>
            </div>

            <div class="col-md-3">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <h5 class="mt-3">Facilities</h5>
                </div>
            </div>

            <div class="col-md-3">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <h5 class="mt-3">Admission</h5>
                </div>
            </div>

            <div class="col-md-3">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="bi bi-bank"></i>
                    </div>
                    <h5 class="mt-3">Institution</h5>
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

<section class="message-section">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-md-6">

                <p>
                    Acharya Prafulla Chandra Ray Polytechnic is a state-of-the-art AICTE approved institute offering
                    diploma engineering courses.
                </p>

                <h6>Principal</h6>

            </div>

            <div class="col-md-6 text-center">

                <div class="message-img">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" class="img-fluid">
                </div>

            </div>

        </div>

    </div>
</section>
@endsection