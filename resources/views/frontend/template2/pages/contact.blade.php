@extends('frontend.template2.layout.app')

@section('title', 'Contact || Acharya Prafulla Chandra Ray Polytechnic')

@section('content')
<div>
    @if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
    @endif

    @if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
    @endif
</div>
<div class="contianer mt-5 mb-5">
    <section>
        <div>
            <div class="d-flex">
                <h2>Contact Us || </h2>
                <h2>{{$college->name }}</h2>
            </div>
            <p>{{ $page->description }}</p>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Feedback Form</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('feedback.store',$college->slug) }}">
                            @csrf
                            <div class="mb-3">
                                <label>Name</label><br>
                                <input type="text" placeholder="Enter name" class="form-control" name="name">
                            </div>

                            <div class="mb-3">
                                <label>Email</label><br>
                                <input type="email" placeholder="Enter email" class="form-control" name="email">
                            </div>

                            <div class="mb-3">
                                <label>Message</label><br>
                                <textarea class="form-control" placeholder="Enter message" name="message"></textarea>
                            </div>

                            <button type="submit" class="btn btn-success">Submit</button>

                        </form>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" class="input-group" action="{{route('feedback.search',$college->slug) }}">
                            @csrf
                            <input type="text" class="form-control" name="ack_number" placeholder="Enter ACK Number">
                            <button type="submit" class="btn btn-info">Search</button>
                        </form>
                        @if(isset($feedback))
                        <h6 class="mt-2">Chat (Acknowledgement Number: {{ $feedback->ack_number }})</h6>

                        <div style="border:1px solid #ccc; padding:10px; height:300px; overflow-y:scroll">
                            <div style="text-align:right; margin:5px;">
                                <span style="background:#dcf8c6; padding:8px; border-radius:10px;">
                                    {{ $feedback->message }}
                                </span>
                            </div>
                            @foreach($feedback->messages as $msg)

                            @if($msg->sender == 'user')

                            <div class="mt-4" style="text-align:right; margin:5px;">
                                <span class="p-2" style="background:#dcf8c6;  border-radius:10px;">
                                    {{ $msg->message }}
                                </span>
                            </div>

                            @else

                            <div style="text-align:left; margin:5px;">
                                <span style="background:#f1f0f0; padding:8px; border-radius:10px;">
                                    {{ $msg->message }}
                                </span>
                            </div>

                            @endif

                            @endforeach

                        </div>

                        @endif

                        @if(isset($feedback))

                        <form class="input-group mt-2" method="POST"
                            action="{{ route('feedback.send',[$college->slug, $feedback->ack_number]) }}">
                            @csrf

                            <input type="text" class="form-control" name="message" required placeholder="Type message">

                            <button class="btn btn-success" type="submit">Send</button>

                        </form>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection