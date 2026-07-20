<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>

<body>

    @include('backend.partials.header')

    <main class="container-fluid">
        @yield('content')
    </main>


    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script src="https://jquery.com"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#reload').click(function () {
        $.ajax({
            type: 'GET',
            url: '{{ route('refresh_captcha') }}', // Define this route in web.php
            success: function (data) {
                $('.captcha-img').attr('src', data.captcha);
            }
        });
    });
</script>


<script>
$(function () {
    $('.delete-file-btn').on('click', function () {
        if (!confirm('Remove this file?')) return;
        const id = $(this).data('id');
        const form = $('<form>', { method: 'POST', action: '{{ url("admin/dashboard/newsEvent/deleteFile") }}/' + id });
        form.append('<input type="hidden" name="_token" value="{{ csrf_token() }}">');
        form.append('<input type="hidden" name="_method" value="DELETE">');
        $('body').append(form);
        form.submit();
    });
});
</script>

{{-- approve/reject/revert for News and event or notice and announcement --}}
<script>
$(function () {
    const itemId = $('#itemCard').data('item-id');
    function post(url, data, cb) {
        $.ajax({
            url: url, method: 'POST',
            data: Object.assign({ _token: '{{ csrf_token() }}' }, data),
            success: cb,
            error: xhr => alert(xhr.responseJSON?.message || 'Something went wrong.')
        });
    }
    $('.approve-btn').on('click', () => post('{{ url("admin/dashboard/newsEventReview/approve") }}/' + itemId, {}, r => r.success && location.reload()));
    $('.reject-submit-btn').on('click', () => post('{{ url("admin/dashboard/newsEventReview/reject") }}/' + itemId, { reason: $('#rejectReason').val() }, r => r.success && location.reload()));
    $('.revert-submit-btn').on('click', () => post('{{ url("admin/dashboard/newsEventReview/revert") }}/' + itemId, { reason: $('#revertReason').val() }, r => r.success && location.reload()));
});
</script>


{{-- forward News and event or notice and announcement --}}
<script>
$(function () {
    $('.forward-btn').on('click', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("admin/dashboard/newsEvent/forward") }}/' + id,
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
                if (res.success) location.reload();
                else alert(res.message);
            }
        });
    });
});
</script>


{{-- forward about page verification to principle --}}
<script>
$(function () {
    $('.forward-btn').on('click', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("admin/dashboard/aboutPage/forward") }}/' + id,
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
                if (res.success) location.reload();
                else alert(res.message);
            }
        });
    });

    $('.delete-btn').on('click', function () {
        if (!confirm('Delete this About page? This cannot be undone.')) return;

        const id = $(this).data('id');
        const form = $('<form>', {
            method: 'POST',
            action: '{{ url("admin/dashboard/aboutPage/destroy") }}/' + id
        }).append('@csrf').append('@method("DELETE")');

        $('body').append(form);
        form.submit();
    });
});
</script>

{{-- approve/reject/revert the About PAGE --}}
<script>
$(function () {
    const pageId = $('#pageCard').data('page-id');

    function post(url, data, cb) {
        $.ajax({
            url: url,
            method: 'POST',
            data: Object.assign({ _token: '{{ csrf_token() }}' }, data),
            success: cb,
            error: function (xhr) {
                alert(xhr.responseJSON?.message || 'Something went wrong.');
            }
        });
    }

    $('.approve-btn').on('click', function () {
        post('{{ url("admin/dashboard/aboutPageReview/approve") }}/' + pageId, {}, function (res) {
            if (res.success) location.reload();
        });
    });

    $('.reject-submit-btn').on('click', function () {
        const reason = $('#rejectReason').val();
        post('{{ url("admin/dashboard/aboutPageReview/reject") }}/' + pageId, { reason }, function (res) {
            if (res.success) location.reload();
        });
    });

    $('.revert-submit-btn').on('click', function () {
        const reason = $('#revertReason').val();
        post('{{ url("admin/dashboard/aboutPageReview/revert") }}/' + pageId, { reason }, function (res) {
            if (res.success) location.reload();
        });
    });
});
</script>

{{-- college page approval logic --}}
<script>
    $(document).ready(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
    var pageId = $('#pageCard').data('page-id');

    function setStatusBadge(status) {
        var badge = $('.status-badge');
        badge.removeClass('bg-secondary bg-warning text-dark bg-success bg-danger bg-info');
        if (status === 'draft') badge.addClass('bg-secondary');
        else if (status === 'forwarded') badge.addClass('bg-warning text-dark');
        else if (status === 'approved') badge.addClass('bg-success');
        else if (status === 'rejected') badge.addClass('bg-danger');
        else badge.addClass('bg-info');
        badge.text(status.charAt(0).toUpperCase() + status.slice(1));
    }

    $('.approve-btn').on('click', function () {
        if (!confirm('Approve this page?')) return;
        $.post('{{ url("admin/dashboard/college/approve") }}/' + pageId, {})
            .done(function (res) {
                if (res.success) {
                    setStatusBadge('approved');
                    $('#actionButtons').empty();
                    $('#statusMessage').html('<div class="alert alert-success mb-3">This page has been approved and is live.</div>');
                    $('#alertBox').html('<div class="alert alert-success">' + res.message + '</div>');
                }
            })
            .fail(function (xhr) {
                $('#alertBox').html('<div class="alert alert-danger">' + (xhr.responseJSON?.message || 'Error') + '</div>');
            });
    });

    $('.reject-submit-btn').on('click', function () {
        var reason = $('#rejectReason').val().trim();
        if (!reason) { alert('Please enter a reason.'); return; }

        $.post('{{ url("admin/dashboard/college/reject") }}/' + pageId, { reason: reason })
            .done(function (res) {
                if (res.success) {
                    setStatusBadge('rejected');
                    $('#actionButtons').empty();
                    $('#statusMessage').html('<div class="alert alert-danger mb-3"><strong>Rejected — Reason:</strong> ' + res.reason + '</div>');
                    $('#rejectModal').modal('hide');
                    $('#alertBox').html('<div class="alert alert-success">' + res.message + '</div>');
                }
            })
            .fail(function (xhr) {
                $('#alertBox').html('<div class="alert alert-danger">' + (xhr.responseJSON?.message || 'Error') + '</div>');
            });
    });

    $('.revert-submit-btn').on('click', function () {
        var reason = $('#revertReason').val().trim();
        if (!reason) { alert('Please enter a reason.'); return; }

        $.post('{{ url("admin/dashboard/college/revert") }}/' + pageId, { reason: reason })
            .done(function (res) {
                if (res.success) {
                    setStatusBadge('reverted');
                    $('#actionButtons').empty();
                    $('#statusMessage').html('<div class="alert alert-warning mb-3"><strong>Reverted — Reason:</strong> ' + res.reason + '</div>');
                    $('#revertModal').modal('hide');
                    $('#alertBox').html('<div class="alert alert-success">' + res.message + '</div>');
                }
            })
            .fail(function (xhr) {
                $('#alertBox').html('<div class="alert alert-danger">' + (xhr.responseJSON?.message || 'Error') + '</div>');
            });
    });
});
</script>

{{-- in change password matching in new password and confirm new password  --}}
<script>
$(function () {

    // Live match check between New Password and Confirm Password
    function checkMatch() {
        const newPass = $('#new_password').val();
        const confirmPass = $('#new_password_confirmation').val();
        const $status = $('#match_status');

        if (confirmPass.length === 0) {
            $status.text('').removeClass('text-success text-danger');
            return;
        }

        if (newPass === confirmPass) {
            $status.text('✔ Passwords match').removeClass('text-danger').addClass('text-success');
        } else {
            $status.text('✘ Passwords do not match').removeClass('text-success').addClass('text-danger');
        }
    }

    $('#new_password, #new_password_confirmation').on('keyup', checkMatch);

    // AJAX form submit
    $('#changePasswordForm').on('submit', function (e) {
        e.preventDefault();

        // Clear old errors
        $('.invalid-feedback').text('');
        $('#cp-alert-box').empty();

        const $btn = $('#submitBtn');
        $btn.prop('disabled', true).text('Updating...');

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                $('#cp-alert-box').html(
                    '<div class="alert alert-success">' + res.message + '</div>'
                );
                $('#changePasswordForm')[0].reset();
                $('#match_status').text('').removeClass('text-success text-danger');
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function (field, messages) {
                        $('#err_' + field).text(messages[0]);
                    });
                } else if (xhr.status === 400) {
                    $('#cp-alert-box').html(
                        '<div class="alert alert-danger">' + xhr.responseJSON.message + '</div>'
                    );
                } else {
                    $('#cp-alert-box').html(
                        '<div class="alert alert-danger">Something went wrong. Please try again.</div>'
                    );
                }
            },
            complete: function () {
                $btn.prop('disabled', false).text('Update Password');
            }
        });
    });

});
</script>

{{-- image preview for banner and principle image uploaded by operator  --}}
<script>
$(document).ready(function () {

    function previewImage(inputId, thumbId) {
        $('#' + inputId).on('change', function (e) {
            var file = e.target.files[0];

            if (!file) {
                $('#' + thumbId).hide();
                return;
            }

            if (!file.type.startsWith('image/')) {
                alert('Please select a valid image file.');
                $(this).val('');
                $('#' + thumbId).hide();
                return;
            }

            var reader = new FileReader();

            reader.onload = function (event) {
                var dataUrl = event.target.result;

                // Show inline thumbnail
                $('#' + thumbId).attr('src', dataUrl).show();

                // Auto-open full-screen preview immediately
                $('#fullScreenPreviewImage').attr('src', dataUrl);
                $('#fullScreenPreview').css('display', 'flex');
            };

            reader.readAsDataURL(file);
        });

        // Clicking the thumbnail later also reopens full preview
        $('#' + thumbId).on('click', function () {
            var src = $(this).attr('src');
            $('#fullScreenPreviewImage').attr('src', src);
            $('#fullScreenPreview').css('display', 'flex');
        });
    }

    previewImage('bannerInput', 'bannerThumb');
    previewImage('principleImageInput', 'principleImageThumb');

    // Close on × click
    $('#closePreview').on('click', function () {
        $('#fullScreenPreview').hide();
    });

    // Close on clicking outside the image (on the dark backdrop)
    $('#fullScreenPreview').on('click', function (e) {
        if (e.target.id === 'fullScreenPreview') {
            $(this).hide();
        }
    });

    // Close on Escape key
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') {
            $('#fullScreenPreview').hide();
        }
    });
});
</script>

{{--in editCollegePage image preview for banner and principle image uploaded by operator change in realtime  --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    function setupPreview(inputId, previewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);

        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            if (preview.dataset.blob === 'true') {
                URL.revokeObjectURL(preview.src);
            }

            preview.src = URL.createObjectURL(file);
            preview.dataset.blob = 'true';
            preview.style.display = 'inline-block';
        });
    }

    setupPreview('bannerInput', 'bannerPreview');
    setupPreview('principalInput', 'principalPreview');
});
</script>

{{-- college page forward/reject/revert logic --}}
<script>
    $(document).ready(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

    $(document).on('click', '.forward-btn', function () {
        var btn = $(this), id = btn.data('id'), row = btn.closest('tr');
        btn.prop('disabled', true).text('Forwarding...');

        $.post('{{ url("admin/dashboard/collegepage/forward") }}/' + id, {})
            .done(function (res) {
                if (res.success) {
                    row.find('.status-cell').html('<span class="badge bg-warning text-dark">Forwarded</span>');
                    row.find('.action-cell').html(
                        '<a href="{{ url("admin/dashboard/collegepage/show") }}/' + id + '" class="btn btn-sm btn-info">View</a> ' +
                        '<button class="btn btn-sm btn-success" disabled>Forwarded</button>'
                    );
                    $('#addPageBtn').hide();
                    $('#alertBox').html('<div class="alert alert-success">' + res.message + '</div>');
                }
            })
            .fail(function (xhr) {
                var msg = xhr.responseJSON?.message || 'Something went wrong.';
                $('#alertBox').html('<div class="alert alert-danger">' + msg + '</div>');
                btn.prop('disabled', false).text('Forward');
            });
    });
});
</script>

{{-- logo preview logic --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const logoInput  = document.getElementById('logo');
    const previewBtn = document.getElementById('previewLogoBtn');
    const previewImg = document.getElementById('logoPreviewImg');
    let selectedFileURL = null;

    logoInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const file = this.files[0];

            if (!file.type.startsWith('image/')) {
                previewBtn.disabled = true;
                alert('Please select a valid image file.');
                this.value = '';
                return;
            }

            if (selectedFileURL) {
                URL.revokeObjectURL(selectedFileURL);
            }

            selectedFileURL = URL.createObjectURL(file);
            previewImg.src = selectedFileURL;
            previewBtn.disabled = false;
        } else {
            previewBtn.disabled = true;
            previewImg.src = '';
        }
    });
});
</script>

<script>
    $(document).ready(function(){

    // SEND OTP
    $('#sendOtp').click(function(){

        let login = $('#login').val();

        $.post('/admin/send-otp', {
            login: login,
            _token: '{{ csrf_token() }}'
        }, function(res){

            if(res.status){
                $('#message').html('<div class="alert alert-success">'+res.message+' | OTP: '+res.otp+'</div>');
                $('#loginDiv').hide();
                $('#otpDiv').show();
            } else {
                $('#message').html('<div class="alert alert-danger">'+res.message+'</div>');
            }

        });
    });

    // VERIFY OTP
    $('#verifyOtp').click(function(){

        $.post('/admin/verify-otp', {
            login: $('#login').val(),
            otp: $('#otp').val(),
            _token: '{{ csrf_token() }}'
        }, function(res){

            if(res.status){
                $('#message').html('<div class="alert alert-success">OTP Verified</div>');
                $('#otpDiv').hide();
                $('#passwordDiv').show();
            } else {
                $('#message').html('<div class="alert alert-danger">'+res.message+'</div>');
            }

        });
    });

    // RESEND OTP
    $('#resendOtp').click(function(){

        $('#sendOtp').click();

    });

    // RESET PASSWORD
    $('#resetPassword').click(function(){

        $.post('/admin/reset-password', {
            login: $('#login').val(),
            password: $('#password').val(),
            password_confirmation: $('#confirmPassword').val(),
            _token: '{{ csrf_token() }}'
        }, function(res){

            if(res.status){
                $('#message').html('<div class="alert alert-success">'+res.message+'</div>');
                $('#passwordDiv').hide();
                 setTimeout(function(){
                window.location.href = res.redirect;
               }, 2000);
            } else {
                $('#message').html('<div class="alert alert-danger">Error</div>');
            }

        });

    });

});
</script>

</html>