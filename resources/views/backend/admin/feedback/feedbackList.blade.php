@extends('backend.layout.app')
@section('title', 'Feedback — ' . $college->name)
@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-5 mt-3">

        @if (Auth::user()->master && Auth::user()->master->name === 'director')
            <a href="{{ url('admin/dashboard/feedbackManagement/index') }}" class="btn btn-sm btn-secondary mb-3">
                ← Back to College List
            </a>
        @endif

        <h2 class="h4 mb-4">Feedback — {{ $college->name }}</h2>

        @if ($feedbacks->isEmpty())
            <p class="text-muted">No feedback submitted yet.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>ACK Number</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>User Replies</th>
                        <th>Submitted</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($feedbacks as $fb)
                        <tr>
                            <td>{{ $fb->ack_number }}</td>
                            <td>{{ $fb->name }}</td>
                            <td>{{ $fb->email }}</td>
                            <td>{{ Str::limit($fb->message, 50) }}</td>
                            <td>{{ $fb->user_reply_count }} / 5</td>
                            <td>{{ $fb->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <a href="{{ url('admin/dashboard/feedbackManagement/show/' . $fb->id) }}"
                                   class="btn btn-sm btn-primary">Open Chat</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </div>
</div>
@endsection