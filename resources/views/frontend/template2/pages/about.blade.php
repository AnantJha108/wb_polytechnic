@extends('frontend.template2.layout.app')

@section('title', 'About || Acharya Prafulla Chandra Ray Polytechnic')

@section('content')
<div class="contianer px-5 mt-5 pb-5 mb-5">
    <section>
        <div>
            <div class="d-flex">
                <h2>About || </h2>
                <h2>{{$college->name }}</h2>
            </div>
            <p class="mt-3">
                Currently the following four courses are offered by the Institute <br>
                1. Diploma in Civil Engineering (Intake 60) <br>
                2. Diploma in Mechanical Engineering (Intake 60) <br>
                3. Diploma in Electrical Engineering (Intake 60) <br>
                4. Electronics and Telecommunication Engineering (Intake 30) <br>
                Admission in three year Diploma Engineering courses are done through JEXPO (West Bengal) in each year.
            </p>
        </div>
    </section>
</div>
@endsection