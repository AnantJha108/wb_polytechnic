@extends('backend.layout.app')

@section('title', 'Change Password')

@section('content')
<div class="row">
    @include('backend.partials.side')
    <div class="col d-flex align-items-center py-5 justify-content-center">
        <div class="card shadow-sm py-2" style="max-width: 500px; width: 100%;">
            <div class="card-body p-4">
                <h2 class="h4 mb-4">Change Password</h2>

                <div id="cp-alert-box"></div>

                <form id="changePasswordForm" action="{{ route('admin.change.password.submit') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label>Current Password</label>
                        <input type="password" name="current_password" id="current_password"
                            class="form-control">
                        <div class="invalid-feedback d-block text-danger small" id="err_current_password"></div>
                    </div>

                    <div class="mb-3">
                        <label>New Password</label>
                        <input type="password" name="new_password" id="new_password"
                            class="form-control">
                        <small class="text-muted d-block">Min 8 chars, with uppercase, lowercase, number & special character.</small>
                        <div class="invalid-feedback d-block text-danger small" id="err_new_password"></div>
                    </div>

                    <div class="mb-3">
                        <label>Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                            class="form-control">
                        <div id="match_status" class="small mt-1 fw-bold"></div>
                        <div class="invalid-feedback d-block text-danger small" id="err_new_password_confirmation"></div>
                    </div>

                    <button type="submit" id="submitBtn" class="btn btn-primary">Update Password</button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection