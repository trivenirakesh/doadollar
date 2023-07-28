$(document).ready(function () {
    const index_page_route = $("#index_page_route").val();
    $("#campaign_category_id").select2();

    $('input[name="start_datetime"]').daterangepicker({
        timePicker: true,
        singleDatePicker: true,
        startDate: moment().startOf("hour"),
        locale: {
            format: "Y-MM-DD HH:mm:ss",
        },
    });

    $('input[name="end_datetime"]').daterangepicker({
        timePicker: true,
        singleDatePicker: true,
        startDate: moment().startOf("hour"),
        endDate: moment().startOf("hour").add(32, "hour"),
        locale: {
            format: "Y-MM-DD HH:mm:ss",
        },
    });

    //Add images
    var fileList = [];

    //remove file
    $("#file_uplaod_section  #fileList").on(
        "click",
        ".btn-remove",
        function () {
            var index = $(this).data("index");
            fileList.splice(index, 1); // Remove the file object from fileList array
            $("#file_uplaod_section  #fileList tr").eq(index).remove(); // Remove the table row from the HTML table

            // Update the data-index attribute for each Remove button after removal
            $("#file_uplaod_section  #fileList .btn-remove").each(function (i) {
                $(this).data("index", i);
            });
        }
    );

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
        $("#file_uplaod_section  #file_error").text(
            errorMessages.includes("Please select a file.")
                ? "Please select a file."
                : ""
        );
        $("#file_uplaod_section  #title_error").text(
            errorMessages.includes("Please enter a title.")
                ? "Please enter a title."
                : ""
        );
        // $("#file_uplaod_section  #image_description_error").text(errorMessages.includes("Please enter a description.") ? "Please enter a description." : "");

        isValid = errorMessages.length === 0;
        return isValid;
    }

    function clearErrorMessages() {
        $("#file_uplaod_section  #file_error").text("");
        $("#file_uplaod_section  #title_error").text("");
        $("#file_uplaod_section  #image_description_error").text("");
    }

    $("#file_uplaod_section #file, #title, #description").on(
        "change",
        function () {
            clearErrorMessages();
        }
    );

    $("#file_uplaod_section #add_more_files").click(function () {
        clearErrorMessages();
        var fileInput = $("#file_uplaod_section #file")[0]; // Get the file input element
        var title = $("#file_uplaod_section #title").val();
        var description = $("#file_uplaod_section #image_description").val();

        if (validateImageFields()) {
            var file = fileInput.files[0]; // Get the first selected file
            var fileObject = {
                file: file,
                title: title,
                description: description,
            };
            fileList.push(fileObject);

            // Create a row in the table for displaying the file details
            var row = `<tr>
                            <td><img src="${URL.createObjectURL(
                                file
                            )}" alt="Preview" style="width: 30px; height: 30px;"></td>
                            <td>${file.name}</td>
                            <td>${title}</td>
                            <td>${
                                description.length > 0 ? description : "-"
                            }</td>
                            <td class="d-flex justify-content-end"><a style="width: 25px; height: 25px;" class="btn-circle btn-danger btn-remove" title="remove" data-index="${
                                fileList.length - 1
                            }"><i class="fa fa-times"></i></a></td>
                        </tr>`;
            $("#file_uplaod_section #fileList").append(row);

            // Clear the input fields after saving
            $("#file_uplaod_section #file").val("");
            $("#file_uplaod_section #title").val("");
            $("#file_uplaod_section #image_description").val("");
        }
    });

    //end Add images

    // Add videos
    var videoList = [];

    //remove v_file
    $("#video_add_section  #video_videoList").on(
        "click",
        ".btn-remove",
        function () {
            var v_index = $(this).data("v_index");
            videoList.splice(v_index, 1); // Remove the v_file object from videoList array
            $("#video_add_section  #video_videoList tr").eq(v_index).remove(); // Remove the table v_row from the HTML table

            // Update the data-v_index attribute for each Remove button after removal
            $("#video_add_section  #video_videoList .btn-remove").each(
                function (i) {
                    $(this).data("v_index", i);
                }
            );
        }
    );

    function validURL(str) {
        var pattern = new RegExp(
            "^(https?:\\/\\/)?" + // protocol
                "((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|" + // domain name
                "((\\d{1,3}\\.){3}\\d{1,3}))" + // OR ip (v4) address
                "(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*" + // port and path
                "(\\?[;&a-z\\d%_.~+=-]*)?" + // query string
                "(\\#[-a-z\\d_]*)?$",
            "i"
        ); // fragment locator
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
            v_errorMessages.includes("Please enter video url.") ||
                v_errorMessages.includes("Please enter a valid video url.")
                ? "Please enter video url."
                : ""
        );
        $("#video_add_section  #video_title-error").text(
            v_errorMessages.includes("Please enter a title.")
                ? "Please enter a title."
                : ""
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
        function () {
            clearErrorMessages();
        }
    );

    $("#video_add_section #video_add_more_files").click(function () {
        var urlInput = $("#video_add_section #video_url").val();
        var v_title = $("#video_add_section #video_title").val();
        var v_description = $(
            "#video_add_section #video_image_description"
        ).val();
        if (validateUrlFields()) {
            var urlInput = urlInput; // Get the first selected v_file
            var fileObject = {
                url: urlInput,
                title: v_title,
                description: v_description,
            };
            if (!validURL(urlInput)) {
                $("#video_add_section  #video_url-error").text(
                    "Please enter valid url."
                );
                return false;
            }
            clearErrorMessages();
            videoList.push(fileObject);

            // Create a v_row in the table for displaying the v_file details
            var v_row = `<tr>
                    <td>${urlInput}</td>
                    <td>${v_title}</td>
                    <td>${v_description.length > 0 ? v_description : "-"}</td>
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
                greaterThan: '[name="start_datetime"]',
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
                required: "Please select  start time",
            },
            end_datetime: {
                required: "Please select  end time",
                greaterThan:
                    "End datetime must be greater than start datetime.",
            },
            unique_code: {
                required: "Please enter  unique code",
            },
            donation_target: {
                required: "Please enter  donation target",
            },
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            const formbtn = $("#module_form_btn");
            const formloader = $("#module_form_loader");
            let formData = new FormData(form);

            // Append each file in the fileList array individually
            fileList.forEach(function (fileObject, index) {
                formData.append(
                    "files_uplaod[" + index + "][image]",
                    fileObject.file
                );
                formData.append(
                    "files_uplaod[" + index + "][title]",
                    fileObject.title
                );
                formData.append(
                    "files_uplaod[" + index + "][description]",
                    fileObject.description
                );
            });

            videoList.forEach(function (videoObject, v_index) {
                formData.append(
                    "video[" + v_index + "][link]",
                    videoObject.url
                );
                formData.append(
                    "video[" + v_index + "][title]",
                    videoObject.title
                );
                formData.append(
                    "video[" + v_index + "][description]",
                    videoObject.description
                );
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
                beforeSend: function () {
                    formloader.show();
                    formbtn.prop("disabled", true);
                },
                success: function (result) {
                    if (result.status) {
                        $("#module_form")[0].reset();
                        $("#campaign_category_id").val("").trigger("change");
                        $("#preview_div").css("display", "none");
                        fileList = [];
                        videoList = [];
                        $("#fileList").html("");
                        $("#video_videoList").html("");
                        toastr.success(result.message);
                        setTimeout(() => {
                            formloader.hide();
                            formbtn.prop("disabled", false);
                            window.location.href = index_page_route;
                        }, 2000);
                    } else {
                        formloader.hide();
                        formbtn.prop("disabled", false);
                        toastr.error(result.message);
                    }
                },
                error: function (result) {
                    formloader.hide();
                    formbtn.prop("disabled", false);
                    var errors = result.responseJSON.errors;
                    // Clear previous error messages
                    $(".error").text("");
                    // Display validation errors in form fields
                    $.each(errors, function (field, messages) {
                        var inputField = $('[name="' + field + '"]');
                        $(".form-group .error").css("display", "block");
                        if (field.startsWith("files_uplaod.")) {
                            $("#files_uplaod_error")
                                .text(messages[0])
                                .css("display", "block");
                        } else if (field.startsWith("video.")) {
                            $("#video_error")
                                .text(messages[0])
                                .css("display", "block");
                        } else {
                            inputField
                                .closest(".form-group")
                                .find(".error")
                                .text(messages[0]);
                        }
                    });
                },
            });
            return false;
        },
    });
});
