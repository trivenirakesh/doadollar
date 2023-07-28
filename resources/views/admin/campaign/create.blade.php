@extends('admin.layouts.admin')
@section('content')
<div class="p-4">
    <div class="content-header">
        <div class="container-fluid d-flex justify-content-between  align-items-center">
            <h2 class="theme_primary_text d-inline-block">{{$title}}</h2>
            <div class="text-right d-inline-block">
                <a class="theme_primary_btn btn btn-sm float-right  ml-2" href="{{route('admin.campaigns.index')}}"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <form autocomplete="off" class="form-horizontal" id="module_form" action="{{route('admin.campaigns.store')}}" name="module_form" novalidate="novalidate">
                                <div class="modal-body">
                                    <div class="card-body">
                                        <input type="hidden" name="id" id="id" value="0">
                                        <div class="row">
                                            <div class="form-group d-flex align-items:center">
                                                <select class="form-control form-control-sm" name="status" id="status">
                                                    <option value="1" selected>Active</option>
                                                    <option value="0">InActive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Name <span class="red">*</span></label>
                                                    <input type="text" class="form-control" placeholder="Please enter name" id="name" name="name" value="{{old('name')}}">
                                                    <label id="name-error" class="error" for="mobile"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="campaign_category_id">Campaign Category<span class="red">*</span></label>
                                                    <select class="form-control" name="campaign_category_id" id="campaign_category_id">
                                                        <option selected value="">Select option</option>
                                                        @foreach ($campaignCategories as $campaignCategory)
                                                        <option value="{{$campaignCategory->id}}">{{$campaignCategory->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <label id="campaign_category_id-error" class="error" for="campaign_category_id"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="description">Description <span class="red">*</span></label>
                                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                                    <label id="description-error" class="error" for="description"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-4">
                                                <div class="form-group">
                                                    <label for="start_datetime">Start time <span class="red">*</span></label>
                                                    <input type="text" id="start_datetime" name="start_datetime" class="form-control">
                                                    <label id="start_datetime-error" class="error" for="start_datetime"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-4">
                                                <div class="form-group">
                                                    <label for="end_datetime">End time <span class="red">*</span></label>
                                                    <input type="text" id="end_datetime" name="end_datetime" class="form-control">
                                                    <label id="end_datetime-error" class="error" for="end_datetime"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-4">
                                                <div class="form-group">
                                                    <label for="donation_target">Donation Target <span class="red">*</span></label>
                                                    <input type="number" class="form-control" placeholder="Please enter donation target" id="donation_target" name="donation_target" value="{{old('donation_target')}}">
                                                    <label id="donation_target-error" class="error" for="donation_target"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="unique_code">Unique code <span class="red">*</span></label>
                                                    <input type="text" class="form-control" placeholder="Please enter unique code" id="unique_code" name="unique_code" value="{{old('unique_code')}}">
                                                    <label id="unique_code-error" class="error" for="unique_code"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-7 col-lg-8">
                                                        <div class="form-group">
                                                            <label for="image">Image</label>
                                                            <input type="file" name="image" id="image" class="form-control image_input" placeholder="Enter Select Image" onchange="load_preview_image(this);" accept="image/png,image/jpg,image/jpeg">
                                                            <label id="image-error" class="error" style="display: none;" for="image"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-5 col-lg-4">
                                                        <div id="preview_div" style="display: none;">
                                                            <img id="image_preview" style="width: auto;" height="70" class="profile-user-img" src="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Uplaod Images start -->
                                            <div class="col-sm-12 p-3" id="file_uplaod_section">
                                                <div class="mt-4">
                                                    <div class="row">
                                                        <div class="col-12 py-3">
                                                            <h3 class="theme_primary_text ">Uplaod Images</h3>
                                                        </div>
                                                        <div class="form-group col-md-2 col-lg-3">
                                                            <input type="file" name="file" id="file" class="form-control image_input" accept="image/*">
                                                            <label id="file_error" class="error" for=""></label>
                                                        </div>

                                                        <div class="form-group col-md-2 col-lg-3">
                                                            <input id="title" type="text" name="title" class="form-control" placeholder="Please enter title">
                                                            <label id="title_error" class="error" for=""></label>
                                                        </div>

                                                        <div class="form-group col-md-4 col-lg-5">
                                                            <textarea id="image_description" rows="1" name="image_description" class="form-control" placeholder="Please enter description"></textarea>
                                                        </div>
                                                        <div class="col-md-2 col-lg-1 d-flex align-items-start justify-content-end">
                                                            <button type="button" id="add_more_files" class="btn btn-primary">Add</button>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-12">
                                                            <table style="width: 100%;" class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width: 10%">Preview</th>
                                                                        <th style="width: 10%">File</th>
                                                                        <th style="width: 30%">Title</th>
                                                                        <th style="width: 40%">Description</th>
                                                                        <th style="width: 10%">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="fileList">
                                                                    <!-- <tr>
                                                                        <td colspan="5" class="text-center">Add File</td>
                                                                    </tr> -->
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <label id="file-error" class="error" for="file_code"></label>
                                            </div>
                                            <!-- Uplaod Images end -->

                                            <!-- add videos start -->
                                            <div class="col-sm-12 p-3" id="video_add_section">
                                                <div class="mt-4">
                                                    <div class="row">
                                                        <div class="col-12 py-3">
                                                            <h3 class="theme_primary_text ">Add Videos</h3>
                                                        </div>
                                                        <div class="form-group col-md-2 col-lg-3">
                                                            <input type="text" name="url" id="video_url" class="form-control" placeholder="Please enter video url">
                                                            <label id="video_url-error" class="error" for=""></label>
                                                        </div>

                                                        <div class="form-group col-md-2 col-lg-3">
                                                            <input id="video_title" type="text" name="title" class="form-control" placeholder="Please enter title">
                                                            <label id="video_title-error" class="error" for=""></label>
                                                        </div>

                                                        <div class="form-group col-md-4 col-lg-5">
                                                            <textarea id="video_image_description" rows="1" name="image_description" class="form-control" placeholder="Please enter description"></textarea>
                                                        </div>
                                                        <div class="col-md-2 col-lg-1 d-flex align-items-start justify-content-end">
                                                            <button type="button" id="video_add_more_files" class="btn btn-primary">Add</button>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-12">
                                                            <table style="width: 100%;" class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width: 20%">File</th>
                                                                        <th style="width: 30%">Title</th>
                                                                        <th style="width: 40%">Description</th>
                                                                        <th style="width: 10%">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="video_videoList">
                                                                    <!-- <tr>
                                                                        <td colspan="5" class="text-center">Add File</td>
                                                                    </tr> -->
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <label id="video_file-error" class="error" for="file_code"></label>
                                            </div>
                                            <!-- add videos end -->

                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="submit" class="btn btn-primary" id="module_form_btn">Save<span style="display: none" id="users_form_loader"><i class="fa fa-spinner fa-spin"></i></span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- /.content -->

@endsection
@push('style')
<link rel="stylesheet" href="{{asset('public/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('public/plugins/daterangepicker/daterangepicker.css')}}">

<!-- Bootstrap DateTimePicker JS -->
<style>
    /* Set the height of the Select2 container */
    .select2-container .select2-selection {
        height: 40px;
        padding-top: 8px;
    }

    .image_input {
        padding: 8px !important;
    }
</style>
@endpush

@push('script')
<script src="{{asset('public/plugins/select2/js/select2.min.js')}}" defer></script>
<script src="{{asset('public/plugins/daterangepicker/daterangepicker.js')}}" defer></script>
<script>
    $(document).ready(function() {
        $('#campaign_category_id').select2();

        $('input[name="start_datetime"]').daterangepicker({
            timePicker: true,
            singleDatePicker: true,
            startDate: moment().startOf('hour'),
            locale: {
                format: 'Y-MM-DD HH:mm:ss'
            }
        });

        $('input[name="end_datetime"]').daterangepicker({
            timePicker: true,
            singleDatePicker: true,
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(32, 'hour'),
            locale: {
                format: 'Y-MM-DD HH:mm:ss'
            }
        });

        //Add images
        var fileList = [];


        //remove file
        $("#file_uplaod_section  #fileList").on("click", ".btn-remove", function() {
            var index = $(this).data("index");
            fileList.splice(index, 1); // Remove the file object from fileList array
            $("#file_uplaod_section  #fileList tr").eq(index).remove(); // Remove the table row from the HTML table

            // Update the data-index attribute for each Remove button after removal
            $("#file_uplaod_section  #fileList .btn-remove").each(function(i) {
                $(this).data("index", i);
            });
        });


        function validateImageFields() {

            var fileInput = $("#file_uplaod_section  #file")[0];
            var title = $("#file_uplaod_section  #title").val();
            // var description = $("#file_uplaod_section  #image_description").val();

            var isValid = true;
            var errorMessages = [];

            if (fileInput.files.length === 0) {
                errorMessages.push("Please select a file.");
            }

            if (!title.trim()) {
                errorMessages.push("Please enter a title.");
            }

            // if (!description.trim()) {
            //     errorMessages.push("Please enter a description.");
            // }

            // Show or hide error messages below the fields
            $("#file_uplaod_section  #file_error").text(errorMessages.includes("Please select a file.") ? "Please select a file." : "");
            $("#file_uplaod_section  #title_error").text(errorMessages.includes("Please enter a title.") ? "Please enter a title." : "");
            // $("#file_uplaod_section  #image_description_error").text(errorMessages.includes("Please enter a description.") ? "Please enter a description." : "");

            isValid = errorMessages.length === 0;
            return isValid;
        }

        function clearErrorMessages() {
            $("#file_uplaod_section  #file_error").text("");
            $("#file_uplaod_section  #title_error").text("");
            $("#file_uplaod_section  #image_description_error").text("");
        }

        $("#file_uplaod_section #file, #title, #description").on("change", function() {
            clearErrorMessages();
        });


        $("#file_uplaod_section #add_more_files").click(function() {
            clearErrorMessages();
            var fileInput = $("#file_uplaod_section #file")[0]; // Get the file input element
            var title = $("#file_uplaod_section #title").val();
            var description = $("#file_uplaod_section #image_description").val();

            if (validateImageFields()) {
                var file = fileInput.files[0]; // Get the first selected file
                var fileObject = {
                    file: file,
                    title: title,
                    description: description
                };
                fileList.push(fileObject);

                // Create a row in the table for displaying the file details
                var row = `<tr>
                                <td><img src="${URL.createObjectURL(file)}" alt="Preview" style="width: 30px; height: 30px;"></td>
                                <td>${file.name}</td>
                                <td>${title}</td>
                                <td>${description.length>0?description:'-'}</td>
                                <td class="d-flex justify-content-end"><a style="width: 25px; height: 25px;" class="btn-circle btn-danger btn-remove" title="remove" data-index="${fileList.length - 1}"><i class="fa fa-times"></i></a></td>
                            </tr>`;
                $("#file_uplaod_section #fileList").append(row);

                // Clear the input fields after saving
                $("#file_uplaod_section #file").val('');
                $("#file_uplaod_section #title").val('');
                $("#file_uplaod_section #image_description").val('');
            }
        });

        //end Add images


        // Add videos
        var videoList = [];

        //remove v_file
        $("#video_add_section  #video_videoList").on(
            "click",
            ".btn-remove",
            function() {
                var v_index = $(this).data("v_index");
                videoList.splice(v_index, 1); // Remove the v_file object from videoList array
                $("#video_add_section  #video_videoList tr").eq(v_index).remove(); // Remove the table v_row from the HTML table

                // Update the data-v_index attribute for each Remove button after removal
                $("#video_add_section  #video_videoList .btn-remove").each(function(i) {
                    $(this).data("v_index", i);
                });
            }
        );

        function validURL(str) {
            var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
                '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
                '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
                '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
                '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
            return !!pattern.test(str);
        }

        function validateUrlFields() {
            var urlInput = $("#video_add_section  #video_url").val().trim();
            var v_title = $("#video_add_section  #video_title").val().trim();

            var v_isValid = true;
            var v_errorMessages = [];

            if (!urlInput) {
                v_errorMessages.push("Please enter video url.");
            } else if (!validURL(urlInput)) {
                v_errorMessages.push("Please enter a valid video url.");
            }

            if (!v_title) {
                v_errorMessages.push("Please enter a title.");
            }

            // Show or hide error messages below the fields
            $("#video_add_section  #video_url-error").text(
                v_errorMessages.includes("Please enter video url.") || v_errorMessages.includes("Please enter a valid video url.") ?
                "Please enter video url." :
                ""
            );
            $("#video_add_section  #video_title-error").text(
                v_errorMessages.includes("Please enter a title.") ?
                "Please enter a title." :
                ""
            );

            v_isValid = v_errorMessages.length === 0;
            return v_isValid;
        }


        function clearErrorMessages() {
            $("#video_add_section  #video_url-error").text("");
            $("#video_add_section  #video_title-error").text("");
            $("#video_add_section  #video_image_description_error").text("");
        }

        $("#video_add_section #video_url, #video_title, #video_description").on(
            "change",
            function() {
                clearErrorMessages();
            }
        );

        $("#video_add_section #video_add_more_files").click(function() {
            var urlInput = $("#video_add_section #video_url").val();
            var v_title = $("#video_add_section #video_title").val();
            var v_description = $("#video_add_section #video_image_description").val();
            if (validateUrlFields()) {
                var urlInput = urlInput; // Get the first selected v_file
                var fileObject = {
                    url: urlInput,
                    title: v_title,
                    description: v_description,
                };
                if (!validURL(urlInput)) {
                    $("#video_add_section  #video_url-error").text("Please enter valid url.");
                    return false
                }
                clearErrorMessages();
                videoList.push(fileObject);

                // Create a v_row in the table for displaying the v_file details
                var v_row = `<tr>
                        <td>${urlInput}</td>
                        <td>${v_title}</td>
                        <td>${
                          v_description.length > 0 ? v_description : "-"
                        }</td>
                        <td class="d-flex justify-content-end"><a style="width: 25px; height: 25px;" class="btn-circle btn-danger btn-remove" v_title="remove" data-v_index="${
                          videoList.length - 1
                        }"><i class="fa fa-times"></i></a></td>
                    </tr>`;
                $("#video_add_section #video_videoList").append(v_row);

                // Clear the input fields after saving
                $("#video_add_section #video_url").val("");
                $("#video_add_section #video_title").val("");
                $("#video_add_section #video_image_description").val("");
            }
        });

        // end Add videos



        $("#module_form").validate({
            rules: {
                name: {
                    required: true,
                },
                description: {
                    required: true,
                },
                image: {
                    required: true,
                },
                campaign_category_id: {
                    required: true,
                },
                start_datetime: {
                    required: true,
                },
                end_datetime: {
                    required: true,
                },
                unique_code: {
                    required: true,
                },
                donation_target: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter name",
                },
                description: {
                    required: "Please enter description",
                },
                image: {
                    required: "Please select image",
                },
                campaign_category_id: {
                    required: "Please select campaign category.",
                },
                start_datetime: {
                    minlength: "Please select  start time",
                },
                end_datetime: {
                    minlength: "Please select  end time",
                },
                end_datetime: {
                    minlength: "Please select  end time",
                },
                unique_code: {
                    minlength: "Please enter  unique code",
                },
                donation_target: {
                    minlength: "Please enter  donation target",
                },
            },
            submitHandler: function(form, e) {
                e.preventDefault();
                const formbtn = $("#module_form_btn");
                const formloader = $("#module_form_loader");
                let formData = new FormData(form);

                // Append each file in the fileList array individually
                fileList.forEach(function(fileObject, index) {
                    formData.append('files_uplaod[' + index + '][image]', fileObject.file);
                    formData.append('files_uplaod[' + index + '][title]', fileObject.title);
                    formData.append('files_uplaod[' + index + '][description]', fileObject.description);
                });

                videoList.forEach(function(videoObject, v_index) {
                    formData.append('video[' + v_index + '][link]', videoObject.url);
                    formData.append('video[' + v_index + '][title]', videoObject.title);
                    formData.append('video[' + v_index + '][description]', videoObject.description);
                });

                $.ajax({
                    url: form.action,
                    type: "POST",
                    data: formData,
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
                            $('#campaign_category_id').val('').trigger('change');
                            $("#preview_div").css('display', 'none');
                            fileList = [];
                            videoList = [];
                            $("#fileList").html("");
                            $("#video_videoList").html("");
                            toastr.success(result.message);
                        } else {
                            toastr.error(result.message);
                        }
                    },
                    error: function(result) {
                        var errors = result.responseJSON.errors;
                        // Clear previous error messages
                        $(".error-message").text("");
                        // Display validation errors in form fields
                        $.each(errors, function(field, messages) {
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
            },
        });
    });
</script>
@endpush