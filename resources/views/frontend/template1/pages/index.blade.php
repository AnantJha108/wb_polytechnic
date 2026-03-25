@extends('frontend.template1.layout.app')

@section('title', 'Acharya Jagadish Chandra Bose Polytechnic')

@section('content')
<div class="contianer mt-5 mb-5">
    <section class="hero">
        <div>
            <h2>WELCOME TO</h2>
            <h1>ACHARYA JAGADISH CHANDRA BOSE POLYTECHNIC</h1>
        </div>
    </section>
    <!-- WELCOME SECTION -->
    <section class="container py-5">
        <div class="row">
            <div class="col-lg-6">

                <h3 class="text-success">
                    Welcome to Acharya Jagadish Chandra Bose Polytechnic
                </h3>
                <p>
                    To meet up the demand of Technical Education in the district of North 24 Parganas, Deganga Block,
                    Government Polytechnic was established in 1962, by the Department of Technical Education and
                    Training, Government of West Bengal. Currently the following four courses are offered by the
                    Institute 1.Diploma in Civil Engineering 2.Diploma in Mechanical Engineering 3.Diploma in Electrical
                    Engineering 4.Electronics and Telecommunication Engineering . All these courses are approved by the
                    AICTE and are affiliated to the West Bengal State Council of Technical and Vocational Education and
                    Skill Development. It is a co-educational institute. The institute has well qualified and dedicated
                    teaching faculty that educates students by an enjoyable and easy learning process. For any kind of
                    Training and Placement activities kindly contact to our TPO-
                    tpo(underscore)ajcbp(at)wbscte(dot)ac(dot)in / tpo(dot)ajcbp(at)rediffmail(dot)com
                </p>

            </div>


            <div class="col-lg-6">

                <div class="row g-3">

                    <div class="col-6">

                        <div class="card position-relative">

                            <img src="course.jpg" class="card-img">

                            <div class="card-overlay">
                                Courses Offered
                            </div>

                        </div>

                    </div>


                    <div class="col-6">

                        <div class="card position-relative">

                            <img src="facility.jpg" class="card-img">

                            <div class="card-overlay">
                                Facilities
                            </div>

                        </div>

                    </div>


                    <div class="col-6">

                        <div class="card position-relative">

                            <img src="admission.jpg" class="card-img">

                            <div class="card-overlay">
                                Admission
                            </div>

                        </div>

                    </div>


                    <div class="col-6">

                        <div class="card position-relative">

                            <img src="institute.jpg" class="card-img">

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
                <img src="principal.jpg" class="img-fluid">
            </div>

            <div class="col-md-6 bg-success text-white p-4">

                <h5>Principal Message</h5>

                <p>
                    Dear All, I am proud of being the Officer-in-Charge of an institute which is dedicated to enable
                    youth empowerment through technical education which will help students to unchain barriers to reach
                    greater heights. On behalf of the students, faculty, staff and administration, I heartily welcome
                    you to Acharya Jagadish Chandra Bose Polytechnic, established in 1962 and it is affiliated to West
                    Bengal State Council of Technical & Vocational Education & Skill Development and approved by AICTE.
                    We provide perfect environment for learning. The institute boasts upon having a large campus
                    equipped with state-of-the-art infrastructure. We ensure friendly, supportive and safe environment
                    through various measures to make the campus safe and ragging-free. I am sincere to motivate and
                    rejuvenate every Department of this Institute to be shining as Outstanding Units Diploma in
                    Engineering Education.

                    Thanks
                    Principal
                    Acharya Jagadish Chandra Bose Polytechnic
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
@endsection