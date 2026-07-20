@extends('backend.layout.app')
@section('title', 'Feedback Chat — ' . $feedback->ack_number)
@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-5 mt-3">

        <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary mb-3">← Back</a>

        <h5 class="mb-3">Chat — {{ $college->name }} (ACK: {{ $feedback->ack_number }})</h5>

        <div class="row">
            <div class="col-8">
                <div class="mb-3 small text-muted">
                    From: {{ $feedback->name }} ({{ $feedback->email }})
                </div>

                <div style="border:1px solid #ccc; padding:10px; height:400px; overflow-y:scroll;">
                    <div style="text-align:left; margin:10px 5px;">
                        <div class="small text-muted mb-1">{{ $feedback->name }}</div>
                        <span style="background:#f1f0f0; padding:8px; border-radius:10px; display:inline-block;">
                            {{ $feedback->message }}
                        </span>
                    </div>

                    @foreach ($feedback->messages as $msg)
                        @if ($msg->sender === 'user')
                            <div style="text-align:left; margin:10px 5px;">
                                <div class="small text-muted mb-1">{{ $feedback->name }}</div>
                                <span style="background:#f1f0f0; padding:8px; border-radius:10px; display:inline-block;">
                                    {{ $msg->message }}
                                </span>
                            </div>
                        @else
                            <div style="text-align:right; margin:10px 5px;">
                                <div class="small text-muted mb-1">{{ $msg->performer->username ?? 'Admin' }}</div>
                                <span style="background:#dcf8c6; padding:8px; border-radius:10px; display:inline-block;">
                                    {{ $msg->message }}
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>

                @if (session('success'))
                    <div class="alert alert-success mt-2">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger mt-2">{{ session('error') }}</div>
                @endif

                @if ($adminReplyCount < 5)
                    <form class="input-group mt-2" method="POST"
                          action="{{ url('admin/dashboard/feedbackManagement/reply/' . $feedback->id) }}">
                        @csrf
                        <input type="text" class="form-control" name="message" required placeholder="Type your reply">
                        <button class="btn btn-success" type="submit">Send</button>
                    </form>
                @else
                    <div class="alert alert-warning mt-2">
                        You have reached the maximum limit of 5 replies for this feedback.
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection