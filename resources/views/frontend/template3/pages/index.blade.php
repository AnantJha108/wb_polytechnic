@extends('frontend.template1.layout.app')

@section('title', 'Alipurduar Damanpur Government Polytechnic')

@section('content')
<!-- Hero Banner -->

<section class="hero">

    <div>

        <h1>Alipurduar Damanpur Government Polytechnic</h1>

    </div>

</section>


<!-- Welcome Section -->

<section class="py-5">

    <div class="container text-center">

        <h2 class="section-title">
            Welcome to <span class="text-success">Alipurduar Damanpur Government Polytechnic</span>
        </h2>

        <p>
            North Bengal in general and Alipurduar in particular is a beautiful place of nature, tea gardens,
            forests and rivers. The institute provides quality education and encourages students
            to build a bright career.
        </p>

    </div>

</section>


<!-- Cards Section -->

<section class="blue-bg">

    <div class="container">

        <div class="row g-4">

            <div class="col-md-3">

                <div class="card card-custom text-center">

                    <img src="course.jpg" class="card-img-top">

                    <div class="card-body">

                        <h5 class="card-title">Courses Offered</h5>

                        <p>Computer Science & Tech<br>Electrical Engineering</p>

                        <a href="#" class="btn btn-outline-success">View More</a>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card card-custom text-center">

                    <img src="facilities.jpg" class="card-img-top">

                    <div class="card-body">

                        <h5 class="card-title">Facilities</h5>

                        <p>Academic building, labs, campus facilities etc.</p>

                        <a href="#" class="btn btn-outline-success">View More</a>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card card-custom text-center">

                    <img src="admission.jpg" class="card-img-top">

                    <div class="card-body">

                        <h5 class="card-title">Admission</h5>

                        <p>Government Polytechnic admission information.</p>

                        <a href="#" class="btn btn-outline-success">View More</a>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card card-custom text-center">

                    <img src="institution.jpg" class="card-img-top">

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



<!-- Committee Section -->

<section class="py-5">

    <div class="container text-center">

        <h2 class="section-title">
            Our <span class="text-success">Committee</span>
        </h2>

        <div class="row g-4">

            <div class="col-md-4">

                <div class="committee-box committee-green">
                    Student Welfare Committee
                </div>

            </div>


            <div class="col-md-4">

                <div class="committee-box committee-blue">
                    Internal Complaint Committee
                </div>

            </div>


            <div class="col-md-4">

                <div class="committee-box committee-green">
                    Anti Ragging Committee
                </div>

            </div>

        </div>

    </div>

</section>



<!-- Notice Section -->

<section class="notice-box">

    <div class="container">

        <h2 class="text-center section-title">
            Our <span class="text-success">Notice & Announcement</span>
        </h2>

        <div class="row align-items-center">

            <div class="col-md-2 text-center">

                <h1>18</h1>

                <p>FEB 2026</p>

            </div>

            <div class="col-md-7">

                <h4>Quotation for compound street light</h4>

                <p>
                    Sealed quotations are invited for maintenance & supply of compound street lights.
                </p>

                <a href="#">Download PDF</a>

            </div>

            <div class="col-md-3 text-center">

                <button class="btn btn-outline-success">View More</button>

            </div>

        </div>

    </div>

</section>



<!-- Events -->

<section class="py-5">

    <div class="container text-center">

        <h2 class="section-title">
            Upcoming <span class="text-success">News and Events</span>
        </h2>

        <div class="row align-items-center">

            <div class="col-md-2">

                <h1>22</h1>

                <p>JUL 2022</p>

            </div>

            <div class="col-md-7 text-start">

                <h4>AICTE Letter of Approval</h4>

                <p>
                    Institute has successfully obtained Letter of Approval (LoA).
                </p>

                <a href="#">Download PDF</a>

            </div>

            <div class="col-md-3">

                <button class="btn btn-outline-success">View More</button>

            </div>

        </div>

    </div>

</section>

@endsection