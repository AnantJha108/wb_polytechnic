@extends('backend.layout.app')
@section('title', 'Register Employee')
@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col px-5 mt-3">
        <div class="card">
            <div class="card-body">
                <h2 class="h4 mb-4">Register Employee</h2>

                @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ url('admin/dashboard/employee/store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- ============ BASIC PROFILE ============ --}}
                    <div class="bg-dark text-white p-2 mb-3">Basic Profile</div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label>First Name *</label>
                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}">
                        </div>
                        <div class="col-md-4">
                            <label>Middle Name</label>
                            <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name') }}">
                        </div>
                        <div class="col-md-4">
                            <label>Last Name *</label>
                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}">
                        </div>

                        <div class="col-md-4">
                            <label>Father's First Name *</label>
                            <input type="text" name="father_first_name" class="form-control"
                                value="{{ old('father_first_name') }}">
                        </div>
                        <div class="col-md-4">
                            <label>Father's Middle Name</label>
                            <input type="text" name="father_middle_name" class="form-control"
                                value="{{ old('father_middle_name') }}">
                        </div>
                        <div class="col-md-4">
                            <label>Father's Last Name *</label>
                            <input type="text" name="father_last_name" class="form-control"
                                value="{{ old('father_last_name') }}">
                        </div>

                        <div class="col-md-4">
                            <label>Employee ID *</label>
                            <input type="text" name="employee_id" class="form-control" value="{{ old('employee_id') }}">
                        </div>
                        <div class="col-md-4">
                            <label>Date of Birth *</label>
                            <input type="date" name="date_of_birth" class="form-control"
                                value="{{ old('date_of_birth') }}">
                        </div>
                        <div class="col-md-4">
                            <label>Gender *</label>
                            <select name="gender" class="form-control">
                                <option value="">-- Select --</option>
                                <option value="MALE" {{ old('gender')=='MALE' ? 'selected' : '' }}>Male</option>
                                <option value="FEMALE" {{ old('gender')=='FEMALE' ? 'selected' : '' }}>Female</option>
                                <option value="OTHER" {{ old('gender')=='OTHER' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Religion *</label>
                            <input type="text" name="religion" class="form-control" value="{{ old('religion') }}">
                        </div>
                        <div class="col-md-4">
                            <label>Caste *</label>
                            <input type="text" name="caste" class="form-control" value="{{ old('caste') }}">
                        </div>
                        <div class="col-md-4">
                            <label>Aadhaar No</label>
                            <input type="text" name="aadhaar_no" maxlength="12" class="form-control"
                                value="{{ old('aadhaar_no') }}">
                        </div>

                        <div class="col-md-4">
                            <label>PAN No</label>
                            <input type="text" name="pan_no" class="form-control" value="{{ old('pan_no') }}">
                        </div>
                        <div class="col-md-4">
                            <label>EPIC No</label>
                            <input type="text" name="epic_no" class="form-control" value="{{ old('epic_no') }}">
                        </div>
                        <div class="col-md-4">
                            <label>PWD (Person with Disabilities) *</label>
                            <select name="pwd_status" class="form-control">
                                <option value="no" {{ old('pwd_status', 'no' )=='no' ? 'selected' : '' }}>No</option>
                                <option value="yes" {{ old('pwd_status')=='yes' ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Availed continuous 3 months leave on medical ground (last 1 year) for critical
                                illness?</label>
                            <select name="medical_leave_three_months" class="form-control">
                                <option value="no" {{ old('medical_leave_three_months', 'no' )=='no' ? 'selected' : ''
                                    }}>No</option>
                                <option value="yes" {{ old('medical_leave_three_months')=='yes' ? 'selected' : '' }}>Yes
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- ============ CONTACT DETAILS ============ --}}
                    <div class="bg-primary text-white p-2 mb-3">Contact Details — Permanent Address</div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label>House No. / Flat No.</label>
                            <input type="text" name="house_no" class="form-control" value="{{ old('house_no') }}">
                        </div>
                        <div class="col-md-4">
                            <label>Street/Village</label>
                            <input type="text" name="street_village" class="form-control"
                                value="{{ old('street_village') }}">
                        </div>
                        <div class="col-md-4">
                            <label>Post Office</label>
                            <input type="text" name="post_office" class="form-control" value="{{ old('post_office') }}">
                        </div>

                        <div class="col-md-4">
                            <label>State *</label>
                            <select name="state" id="stateSelect" class="form-control">
                                <option value="">-- Select --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>District *</label>
                            <select name="district" id="districtSelect" class="form-control">
                                <option value="">-- Select State First --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Sub Division</label>
                            <select name="sub_division" id="subDivisionSelect" class="form-control">
                                <option value="">-- Select District First --</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Block / Municipality Corporation</label>
                            <select name="block_municipality" id="blockSelect" class="form-control">
                                <option value="">-- Select Sub Division First --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Police Station</label>
                            <input type="text" name="police_station" class="form-control"
                                value="{{ old('police_station') }}">
                        </div>
                        <div class="col-md-4">
                            <label>PIN *</label>
                            <input type="text" name="pin" maxlength="6" class="form-control" value="{{ old('pin') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="d-block">Present Address (same as Permanent)</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="present_same_as_permanent"
                                    value="yes" {{ old('present_same_as_permanent', 'yes' )=='yes' ? 'checked' : '' }}>
                                <label class="form-check-label">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="present_same_as_permanent" value="no"
                                    {{ old('present_same_as_permanent')=='no' ? 'checked' : '' }}>
                                <label class="form-check-label">No</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>Mobile No</label>
                            <input type="text" name="mobile_no" maxlength="10" class="form-control"
                                value="{{ old('mobile_no') }}">
                        </div>
                        <div class="col-md-4">
                            <label>Email *</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        </div>
                    </div>

                    {{-- ============ ACADEMIC DETAILS — fixed 4 levels ============ --}}
                    <div class="bg-dark text-white p-2 mb-3">Academic Details (Insert in Descending Order)</div>

                    @foreach (['1st Level (Highest)', '2nd Level', '3rd Level', '4th Level'] as $i => $levelLabel)
                    @php $lvl = $i + 1; @endphp
                    <div class="border rounded p-3 mb-2">
                        <strong>{{ $levelLabel }}</strong>
                        <div class="row g-3 mt-1">
                            <div class="col-md-4">
                                <label>Qualification {{ $lvl == 1 ? '*' : '' }}</label>
                                <input type="text" name="academic[{{ $lvl }}][qualification]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Discipline Trade {{ $lvl == 1 ? '*' : '' }}</label>
                                <input type="text" name="academic[{{ $lvl }}][discipline_trade]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Passing Year {{ $lvl == 1 ? '*' : '' }}</label>
                                <input type="text" name="academic[{{ $lvl }}][passing_year]" class="form-control">
                            </div>
                        </div>
                    </div>
                    @endforeach

                    {{-- ============ EMPLOYMENT ============ --}}
                    <div class="bg-dark text-white p-2 mb-3 mt-4">Employment</div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label>Date of Initial Joining *</label>
                            <input type="date" name="date_of_initial_joining" class="form-control"
                                value="{{ old('date_of_initial_joining') }}">
                        </div>
                        <div class="col-md-4">
                            <label>Date of Retirement</label>
                            <input type="date" name="date_of_retirement" class="form-control"
                                value="{{ old('date_of_retirement') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="d-block">Whether Confirmed?</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="whether_confirmed" value="yes" {{
                                    old('whether_confirmed')=='yes' ? 'checked' : '' }}>
                                <label class="form-check-label">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="whether_confirmed" value="no" {{
                                    old('whether_confirmed', 'no' )=='no' ? 'checked' : '' }}>
                                <label class="form-check-label">No</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="d-block">Whether any disciplinary proceeding/vigilance case
                                pending/contemplated against the employee?</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="disciplinary_proceeding" value="yes"
                                    {{ old('disciplinary_proceeding')=='yes' ? 'checked' : '' }}>
                                <label class="form-check-label">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="disciplinary_proceeding" value="no"
                                    {{ old('disciplinary_proceeding', 'no' )=='no' ? 'checked' : '' }}>
                                <label class="form-check-label">No</label>
                            </div>
                        </div>
                    </div>

                    {{-- ---- Higher Study QIP (dynamic, max 4) ---- --}}
                    @include('backend.admin.employee.partials.dynamicYesNoSection', [
                    'sectionId' => 'qip',
                    'toggleName' => 'higher_study_qip',
                    'rowsName' => 'qip_rows',
                    'label' => 'Whether availed higher study on Full Time (QIP) basis with study leave or not?',
                    'fields' => [
                    ['name' => 'session', 'label' => 'Session', 'type' => 'text'],
                    ['name' => 'course', 'label' => 'Course', 'type' => 'text'],
                    ['name' => 'institute_name', 'label' => 'Name of the Institute', 'type' => 'text'],
                    ['name' => 'start_date', 'label' => 'Start Date', 'type' => 'date'],
                    ['name' => 'end_date', 'label' => 'End Date (optional)', 'type' => 'date'],
                    ],
                    'existingRows' => [],
                    'defaultYes' => old('higher_study_qip', 'no'),
                    ])

                    {{-- ---- Higher Study Non-QIP (dynamic, max 4) ---- --}}
                    @include('backend.admin.employee.partials.dynamicYesNoSection', [
                    'sectionId' => 'non_qip',
                    'toggleName' => 'higher_study_non_qip',
                    'rowsName' => 'non_qip_rows',
                    'label' => 'Whether availed higher study on Full Time (Non-QIP) basis with study leave or not?',
                    'fields' => [
                    ['name' => 'session', 'label' => 'Session', 'type' => 'text'],
                    ['name' => 'course', 'label' => 'Course', 'type' => 'text'],
                    ['name' => 'institute_name', 'label' => 'Name of the Institute', 'type' => 'text'],
                    ['name' => 'start_date', 'label' => 'Start Date', 'type' => 'date'],
                    ['name' => 'end_date', 'label' => 'End Date (optional)', 'type' => 'date'],
                    ],
                    'existingRows' => [],
                    'defaultYes' => old('higher_study_non_qip', 'no'),
                    ])

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="d-block">Whether availed higher study under Modular Programme or not?</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="higher_study_modular" value="yes" {{
                                    old('higher_study_modular')=='yes' ? 'checked' : '' }}>
                                <label class="form-check-label">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="higher_study_modular" value="no" {{
                                    old('higher_study_modular', 'no' )=='no' ? 'checked' : '' }}>
                                <label class="form-check-label">No</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="d-block">Whether availed higher study on Part Time basis or not?</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="higher_study_part_time" value="yes"
                                    {{ old('higher_study_part_time')=='yes' ? 'checked' : '' }}>
                                <label class="form-check-label">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="higher_study_part_time" value="no" {{
                                    old('higher_study_part_time', 'no' )=='no' ? 'checked' : '' }}>
                                <label class="form-check-label">No</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="d-block">Whether prayee for transfer or not?</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="prayee_for_transfer" value="yes" {{
                                    old('prayee_for_transfer')=='yes' ? 'checked' : '' }}>
                                <label class="form-check-label">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="prayee_for_transfer" value="no" {{
                                    old('prayee_for_transfer', 'no' )=='no' ? 'checked' : '' }}>
                                <label class="form-check-label">No</label>
                            </div>
                        </div>
                    </div>

                    {{-- ============ SPOUSE DETAILS ============ --}}
                    <div class="bg-dark text-white p-2 mb-3">Spouse Details</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="d-block">Spouse working in Government Sector?</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="spouse_govt_sector" value="yes" {{
                                    old('spouse_govt_sector')=='yes' ? 'checked' : '' }}>
                                <label class="form-check-label">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="spouse_govt_sector" value="no" {{
                                    old('spouse_govt_sector', 'no' )=='no' ? 'checked' : '' }}>
                                <label class="form-check-label">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="bg-dark text-white p-2 mb-3">Posting History (Insert in Descending Order)</div>

                    <div id="postingHistoryContainer" data-max="4">
                        <div class="posting-history-block mb-4" data-posting-index="0">

                            {{-- Principal-in-Charge --}}
                            @include('backend.admin.employee.partials.dynamicYesNoSection', [
                            'sectionId' => 'principal_incharge_0',
                            'toggleName' => 'posting_rows[0][principal_incharge]',
                            'rowsName' => 'posting_rows[0][principal_incharge_rows]',
                            'label' => 'Whether acting as Principal-in-Charge or not?',
                            'fields' => [
                            ['name' => 'polytechnic_name', 'label' => 'Name of the Polytechnic as Principal-in-Charge',
                            'type' => 'text'],
                            ['name' => 'from_date', 'label' => 'From Date', 'type' => 'date'],
                            ['name' => 'to_date', 'label' => 'To Date', 'type' => 'date'],
                            ],
                            'existingRows' => [],
                            'defaultYes' => 'no',
                            ])

                            {{-- Deputation --}}
                            @include('backend.admin.employee.partials.dynamicYesNoSection', [
                            'sectionId' => 'deputation_0',
                            'toggleName' => 'posting_rows[0][deputation]',
                            'rowsName' => 'posting_rows[0][deputation_rows]',
                            'label' => 'Whether on Deputation or not?',
                            'fields' => [
                            ['name' => 'office_name', 'label' => 'Name of the Office on Deputation', 'type' => 'text'],
                            ['name' => 'designation', 'label' => 'Designation on Deputation', 'type' => 'text'],
                            ['name' => 'from_date', 'label' => 'From Date', 'type' => 'date'],
                            ['name' => 'to_date', 'label' => 'To Date', 'type' => 'date'],
                            ],
                            'existingRows' => [],
                            'defaultYes' => 'no',
                            ])

                            {{-- Working Arrangement --}}
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="d-block">Whether on Working Arrangement or not?</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input wa-toggle" type="radio"
                                            name="posting_rows[0][working_arrangement]" value="yes">
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input wa-toggle" type="radio"
                                            name="posting_rows[0][working_arrangement]" value="no" checked>
                                        <label class="form-check-label">No</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label>Discipline Trade</label>
                                    <input type="text" name="posting_rows[0][discipline_trade]" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Employment Status</label>
                                    <input type="text" name="posting_rows[0][employment_status]" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label>Categories of Service</label>
                                    <input type="text" name="posting_rows[0][categories_of_service]"
                                        class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label>From Date</label>
                                    <input type="date" name="posting_rows[0][wa_from_date]" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label>To Date</label>
                                    <input type="date" name="posting_rows[0][wa_to_date]" class="form-control">
                                </div>
                            </div>

                            <button type="button" class="btn btn-sm btn-outline-danger remove-posting-history-block"
                                style="display:none;">Remove This Posting History</button>
                            <hr>
                        </div>
                    </div>

                    <button type="button" id="addPostingHistoryBtn" class="btn btn-sm btn-outline-primary mb-4">+ Add
                        New Posting History</button>

                    {{-- ============ PROFILE PHOTO ============ --}}
                    <div class="bg-dark text-white p-2 mb-3">Profile Photo & Documents</div>

                    <div class="mb-3">
                        <label>Upload Recent Photo (* Only JPEG/JPG files, max 300 KB)</label>
                        <input type="file" name="photo" id="photoInput"
                            class="form-control @error('photo') is-invalid @enderror" accept="image/jpeg">
                        @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror

                        <div class="mt-2">
                            <p class="mb-1 text-muted small">Preview:</p>
                            <img id="photoPreview" @if(isset($employee) && $employee->photo_path)
                            src="{{ url('admin/dashboard/employee/photo/' . $employee->id) }}"
                            @endif
                            width="120" height="120" style="object-fit:cover; border:1px solid #ccc; {{
                            (isset($employee) && $employee->photo_path) ? '' : 'display:none;' }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection