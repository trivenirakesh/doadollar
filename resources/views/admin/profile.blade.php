@extends('admin.layouts.admin')
@section('content')
<div class="p-4">
    <div class="content-header">
        <div class="container-fluid d-flex justify-content-between  align-items-center">
            <h2 class="theme_primary_text d-inline-block">{{$title}}</h2>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- <div class="col-md-3 col-sm-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <label id="profile_img">
                                    <div id="preview_div">
                                        <img id="image_preview" class="image_preview profile-user-img img-fluid img-circle admin_profile" height=150 width=150 src="">
                                    </div>
                                </label>
                            </div>
                            <h3 class="profile-username text-center">-</h3>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Followers</b> <a class="float-right">-</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Following</b> <a class="float-right">-</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Friends</b> <a class="float-right">-</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header px-4 py-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab">Profile</a></li>
                                <li class="nav-item"><a class="nav-link" href="#password" data-toggle="tab">Password</a></li>
                            </ul>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="profile">
                                        <form id="profile_frm" form_name="profile_frm" method="post" action="{{ route('admin.profile-update') }}">
                                            <div class="card-body">
                                                <input type="hidden" name="id" id="id" value="{{$user->id}}">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Firstname <span class="red">*</span></label>
                                                            <input type="text" class="form-control" placeholder="Please enter firstname" id="first_name" name="first_name" value="{{$user->first_name}}">
                                                            <label id="first_name-error" class="error" style="display: none;" for="first_name"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Lastname <span class="red">*</span></label>
                                                            <input type="text" class="form-control" placeholder="Please enter lastname" id="last_name" name="last_name" value="{{$user->last_name}}">
                                                            <label id="last_name-error" class="error" style="display: none;" for="last_name"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Email <span class="red">*</span></label>
                                                            <input name="email" type="email" class="form-control" placeholder="Please enter email" value="{{$user->email}}">
                                                            <label id="email-error" class="error" style="display: none;" for="email"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Mobile <span class="red">*</span></label>
                                                            <input type="number" class="form-control" placeholder="Please enter mobile" id="mobile" name="mobile" value="{{$user->mobile}}">
                                                            <label id="mobile-error" class="error" style="display: none;" for="mobile"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary btn-flat float-right" id="profile_frm_btn">Update Profile <span style="display: none" id="profile_frm_loader"><i class="fa fa-spinner fa-spin"></i></span></button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="password">
                                        <form id="password_frm" form_name="password_frm" method="post" action="{{ route('admin.update-password') }}">
                                            <div class="card-body">
                                                <input type="hidden" name="id" id="id" value="{{$user->id}}">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Old Password</label>
                                                            <input type="password" class="form-control" placeholder="Please enter old password" id="old_password" name="old_password">
                                                            <label id="old_password-error" class="error" style="display: none;" for="old_password"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Password</label>
                                                            <input type="password" class="form-control" placeholder="Please enter password" id="password" name="password">
                                                            <label id="password-error" class="error" style="display: none;" for="password"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Confirm Password</label>
                                                            <input type="password" class="form-control" placeholder="Please enter confirm password" id="password_confirmation" name="password_confirmation">
                                                            <label id="password_confirmation-error" class="error" style="display: none;" for="password_confirmation"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary btn-flat float-right" id="password_frm_btn">Update Password <span style="display: none" id="password_frm_loader"><i class="fa fa-spinner fa-spin"></i></span></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<!-- /.content -->

@endsection
@push('script')
<script>
    $("#profile_frm").validate({
        rules: {
            first_name: {
                required: true,
            },
            last_name: {
                required: true,
            },
            mobile: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10,
            },
            email: {
                required: true,
                email: true,
            },
        },
        messages: {
            first_name: {
                required: "Please enter firstname",
            },
            last_name: {
                required: "Please enter lastname",
            },
            email: {
                required: "Please enter email",
                email: "Please enter valid email",
            },
            mobile: {
                required: "Please enter your mobile number.",
                digits: "Please enter a valid mobile number.",
                minlength: "Mobile number must be at least 10 digits.",
                maxlength: "Mobile number must not exceed 10 digits.",
            },
        },
        submitHandler: function(form, e) {
            e.preventDefault();
            console.log(form)
            const formbtn = $('#profile_frm_btn');
            const formloader = $('#profile_frm_loader');
            $.ajax({
                url: form.action,
                type: "POST",
                data: new FormData(form),
                dataType: 'json',
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                beforeSend: function() {
                    formloader.show();
                    formbtn.prop('disabled', true);
                },
                success: function(result) {
                    formloader.hide();
                    formbtn.prop('disabled', false);
                    if (result.status) {
                        toastr.success(result.message);
                    } else {
                        toastr.error(result.message);
                    }
                },
                error: function(result) {
                    var errors = result.responseJSON.errors;
                    console.log("result", errors);
                    // Clear previous error messages
                    $(".error-message").text("");
                    // Display validation errors in form fields
                    $.each(errors, function(field, messages) {
                        console.log(field, messages);
                        var inputField = $('[name="' + field + '"]');
                        $(".form-group .error").css("display", "block");
                        inputField
                            .closest(".form-group")
                            .find(".error")
                            .text(messages[0]);
                    });
                    // toastr.error("Please Reload Page.");
                    formloader.hide();
                    formbtn.prop("disabled", false);
                },
            });
            return false;
        }
    });

    $("#password_frm").validate({
        rules: {
            old_password: {
                required: true,
            },
            password: {
                minlength: 6,
            },
            password_confirmation: {
                minlength: 6,
                equalTo: '[name="password"]'
            },
        },
        messages: {

            old_password: {
                required: "Please enter old password",
                minlength: "Please enter old password atleast 6 character!"
            },
            password: {
                required: "Please enter password",
                minlength: "Please enter password atleast 6 character!"
            },
            password_confirmation: {
                required: "Please enter confirm password"
            },

        },
        submitHandler: function(form, e) {
            e.preventDefault();
            const formbtn = $('#password_frm_btn');
            const formloader = $('#password_frm_loader');
            $.ajax({
                url: form.action,
                type: "POST",
                data: new FormData(form),
                dataType: 'json',
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                beforeSend: function() {
                    $(formloader).show();
                    $(formbtn).prop('disabled', true);
                },
                success: function(result) {
                    $(formloader).hide();
                    $(formbtn).prop('disabled', false);
                    if (result.status) {
                        $("#password,#password_confirmation,#old_password").val('');
                        toastr.success(result.message);
                    } else {
                        toastr.error(result.message);
                    }
                },
                error: function(result) {
                    var errors = result.responseJSON.errors;
                    console.log("result", errors);
                    // Clear previous error messages
                    $(".error-message").text("");
                    // Display validation errors in form fields
                    $.each(errors, function(field, messages) {
                        console.log(field, messages);
                        var inputField = $('[name="' + field + '"]');
                        $(".form-group .error").css("display", "block");
                        inputField
                            .closest(".form-group")
                            .find(".error")
                            .text(messages[0]);
                    });
                    // toastr.error("Please Reload Page.");
                    formloader.hide();
                    formbtn.prop("disabled", false);
                },
            });
            return false;
        }
    });
</script>
@endpush