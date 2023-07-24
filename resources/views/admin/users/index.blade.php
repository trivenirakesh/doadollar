@extends('admin.layouts.admin')
@section('content')
<div class="p-4">
    <div class="content-header">
        <div class="container-fluid d-flex justify-content-between  align-items-center">
            <h2 class="theme_primary_text d-inline-block">{{$title}}</h2>
            <div class="text-right d-inline-block">
                <button class="theme_primary_btn btn btn-sm float-right  ml-2" onclick="addModel()"><i class="fa fa-plus" aria-hidden="true"></i> Add {{$title}}</button>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body py-4 px-2">
                            <table id="data_table_main" class="table w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <input type="hidden" id="page_module" value="{{$title}}">
    <input type="hidden" id="module_index_url" value="{{ route('admin.users.index') }}">
</div>
@include('admin.users.modal')
<!-- /.content -->

@endsection
@push('script')
<!-- <script src="{{asset('public/asset/js/campaign-category.js')}}"></script> -->
<script>
    let module = $("#page_module").val();
    let module_index_url = $("#module_index_url").val();

    function addModel() {
        var $alertas = $("#module_form");
        $alertas.validate().resetForm();
        $alertas.find(".error").removeClass("error");
        $("#module_form")[0].reset();
        $("#modal-add-update").modal("show");
        $("#id").val(0);
        $("#modal_title").text(`Add ${module}`);
        $("#preview_div").hide();
        $("#project_btn").html(
            'Add <span style="display: none" id="loader"><i class="fa fa-spinner fa-spin"></i></span>'
        );
    }
    $(document).ready(function() {
        // View resource
        $(document).on("click", ".module_view_record", function() {
            const id = $(this).parent().data("id");
            const url = $(this).parent().data("url");
            $("#show_modal_title").text(`${module} Detail`);
            $("#view_module_modal").modal("show");
            $("#view_module_modal .loader").addClass("d-flex");
            $.ajax({
                type: "GET",
                data: {
                    id: id,
                    _method: "SHOW",
                },
                url: `${url}/${id}`,
                headers: {
                    "X-CSRF-TOKEN": csrf_token,
                },
                success: function(response) {
                    $("#view_module_modal .loader").removeClass("d-flex");
                    if (response.status) {
                        $.each(response.data, function(key, value) {
                            $(`#info_${key}`).text(value);
                            if (key == "image") {
                                $(`#info_${key}`).attr("src", value);
                            }
                        });
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    $("#view_module_modal .loader").removeClass("d-flex");
                    toastr.error("Please Reload Page.");
                },
            });
        });

        // delete resource
        $(document).on("click", ".module_delete_record", function() {
            const id = $(this).parent().data("id");
            const url = $(this).parent().data("url");
            deleteRecordModule(id, `${url}/${id}`);
        });

        // edit resource
        $(document).on("click", ".module_edit_record", function() {
            const id = $(this).parent().data("id");
            const url = $(this).parent().data("url");
            $("#modal_title").text(`Edit ${module}`);
            $("#modal-add-update").modal("show");
            $("#image_preview").attr("");
            $.ajax({
                type: "GET",
                data: {
                    id: id,
                    _method: "SHOW",
                },
                url: `${url}/${id}`,
                headers: {
                    "X-CSRF-TOKEN": csrf_token,
                },
                success: function(response) {
                    if (response.status) {
                        console.log(response.data);
                        $.each(response.data, function(key, value) {
                            if (key == "image") {
                                $("#image_preview").attr("src", value);
                            } else {
                                $(`#${key}`).val(value);
                            }
                        });
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error("Please Reload Page.");
                },
            });
            $("#module_form_btn").html(
                'Update <span style="display: none" id="module_form_loader"><i class="fa fa-spinner fa-spin"></i></span>'
            );
        });

        $("#module_form").validate({
            rules: {
                first_name: {
                    required: true,
                    lettersonly: true
                },
                last_name: {
                    required: true,
                    lettersonly: true
                },
                mobile: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 10
                },
                email: {
                    required: true,
                    email: true,
                },
                image: {
                    accept: "image/jpg,image/jpeg,image/png"
                },
                password: {
                    minlength: 6,
                },
                password_confirmation: {
                    equalTo: "#password"
                },
            },
            messages: {
                first_name: {
                    required: "Please enter firstname",
                    lettersonly: "Please enter valid firstname"
                },
                last_name: {
                    required: "Please enter lastname",
                    lettersonly: "Please enter valid lastname"
                },
                email: {
                    required: "Please enter email",
                    email: "Please enter valid email",
                },
                mobile: {
                    required: "Please enter your mobile number.",
                    digits: "Please enter a valid mobile number.",
                    minlength: "Mobile number must be at least 10 digits.",
                    maxlength: "Mobile number must not exceed 10 digits."
                },
                name: {
                    required: "Please enter name",
                },
                image: {
                    accept: 'Only allow image!'
                },
                password: {
                    minlength: "Please enter password atleast 6 character!"
                },
                password_confirmation: {
                    equalTo: "password and confirm password not match"
                },

            },
            submitHandler: function(form, e) {
                e.preventDefault();
                const formbtn = $("#module_form_btn");
                const formloader = $("#module_form_loader");
                console.log(formloader);
                $.ajax({
                    url: form.action,
                    type: "POST",
                    data: new FormData(form),
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    headers: {
                        "X-CSRF-TOKEN": csrf_token,
                    },
                    beforeSend: function() {
                        formloader.show();
                        formbtn.prop("disabled", true);
                    },
                    success: function(result) {
                        formloader.hide();
                        formbtn.prop("disabled", false);
                        if (result.status) {
                            $("#module_form")[0].reset();
                            $("#modal-add-update").modal("hide");
                            $("#data_table_main").DataTable().ajax.reload();
                            toastr.success(result.message);
                        } else {
                            toastr.error(result.message);
                        }
                    },
                    error: function(result) {
                        var errors = result.responseJSON.errors;;
                        console.log('result', errors);
                        // Clear previous error messages
                        $('.error-message').text('');
                        // Display validation errors in form fields
                        $.each(errors, function(field, messages) {
                            console.log(field, messages);
                            var inputField = $('[name="' + field + '"]');
                            $(".form-group .error").css("display", "block");
                            inputField.closest('.form-group').find('.error').text(messages[0]);
                        });
                        // toastr.error("Please Reload Page.");
                        formloader.hide();
                        formbtn.prop("disabled", false);
                    },
                });
                return false;
            },
        });


        var table = $("#data_table_main").DataTable({
            processing: true,
            serverSide: true,
            // dom: 'Bfrtip',
            buttons: [],
            ajax: module_index_url,
            order: [],
            select: {
                style: "multi",
            },
            columns: [{
                    data: "DT_RowIndex",
                    name: "",
                    orderable: false,
                    searchable: false,
                },
                {
                    data: "first_name",
                    name: "first_name",
                },
                {
                    data: "last_name",
                    name: "last_name",
                },
                {
                    data: "email",
                    name: "email",
                },
                {
                    data: "role.name",
                    name: "role.name",
                },
                {
                    data: "created_at",
                    name: "created_at",
                },
                {
                    data: "status",
                    name: "status",
                },
                {
                    data: "actions",
                    name: "actions",
                    searchable: false,
                    orderable: false,
                },
            ],
        });
    });
</script>
@endpush