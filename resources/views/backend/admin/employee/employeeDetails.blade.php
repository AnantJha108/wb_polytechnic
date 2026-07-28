@extends('backend.layout.app')
@section('title', $employee->first_name . ' ' . $employee->last_name)
@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-5 mt-3">
        <a href="{{ url('admin/dashboard/employee/index') }}" class="btn btn-sm btn-secondary mb-3">← Back</a>

        <div class="card">
            <div class="card-body">
                <h2 class="h4">{{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}</h2>
                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $employee->status)) }}</span>

                @if ($employee->status === 'rejected' && $employee->reject_reason)
                    <div class="alert alert-danger mt-2"><strong>Rejected:</strong> {{ $employee->reject_reason }}</div>
                @endif
                @if ($employee->status === 'reverted' && $employee->revert_reason)
                    <div class="alert alert-warning mt-2"><strong>Reverted:</strong> {{ $employee->revert_reason }}</div>
                @endif

                <h6 class="mt-4">Employee ID</h6>
                <p>{{ $employee->employee_id }}</p>

                <h6>Date of Birth</h6>
                <p>{{ $employee->date_of_birth }}</p>

                <h6>Contact</h6>
                <p>{{ $employee->mobile_no }} — {{ $employee->email }}</p>

                <h6>Academic Details</h6>
                <table class="table table-sm">
                    <thead><tr><th>Level</th><th>Qualification</th><th>Discipline</th><th>Passing Year</th></tr></thead>
                    <tbody>
                        @foreach ($employee->academicDetails as $ac)
                            <tr><td>{{ $ac->level }}</td><td>{{ $ac->qualification }}</td><td>{{ $ac->discipline_trade }}</td><td>{{ $ac->passing_year }}</td></tr>
                        @endforeach
                    </tbody>
                </table>

                @if ($employee->higherStudies->isNotEmpty())
                    <h6>Higher Studies</h6>
                    <table class="table table-sm">
                        <thead><tr><th>Type</th><th>Session</th><th>Course</th><th>Institute</th></tr></thead>
                        <tbody>
                            @foreach ($employee->higherStudies as $hs)
                                <tr><td>{{ $hs->type }}</td><td>{{ $hs->session }}</td><td>{{ $hs->course }}</td><td>{{ $hs->institute_name }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                @if ($employee->principalIncharges->isNotEmpty())
                    <h6>Principal-in-Charge History</h6>
                    <table class="table table-sm">
                        <thead><tr><th>Polytechnic</th><th>From</th><th>To</th></tr></thead>
                        <tbody>
                            @foreach ($employee->principalIncharges as $pi)
                                <tr><td>{{ $pi->polytechnic_name }}</td><td>{{ $pi->from_date }}</td><td>{{ $pi->to_date }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                @if ($employee->deputations->isNotEmpty())
                    <h6>Deputation History</h6>
                    <table class="table table-sm">
                        <thead><tr><th>Office</th><th>Designation</th><th>From</th><th>To</th></tr></thead>
                        <tbody>
                            @foreach ($employee->deputations as $dep)
                                <tr><td>{{ $dep->office_name }}</td><td>{{ $dep->designation }}</td><td>{{ $dep->from_date }}</td><td>{{ $dep->to_date }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection