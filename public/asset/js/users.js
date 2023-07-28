let module = $("#page_module").val();
let module_index_url = $("#module_index_url").val();

function addModel() {
    $("#password_note").addClass("d-none");
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
$(document).ready(function () {
    // View resource
    $(document).on("click", ".module_view_record", function () {
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
            success: function (response) {
                $("#view_module_modal .loader").removeClass("d-flex");
                if (response.status) {
                    $.each(response.data, function (key, value) {
                        $(`#info_${key}`).text(value);
                        if (key == "image") {
                            $(`#info_${key}`).attr("src", value);
                        }
                    });
                } else {
                    toastr.error(response.message);
                }
            },
            error: function () {
                $("#view_module_modal .loader").removeClass("d-flex");
                toastr.error("Please Reload Page.");
            },
        });
    });

    // delete resource
    $(document).on("click", ".module_delete_record", function () {
        const id = $(this).parent().data("id");
        const url = $(this).parent().data("url");
        deleteRecordModule(id, `${url}/${id}`);
    });

    // edit resource
    $(document).on("click", ".module_edit_record", function () {
        $("#password_note").removeClass("d-none");
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
            success: function (response) {
                if (response.status) {
                    console.log(response.data);
                    $.each(response.data, function (key, value) {
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
            error: function () {
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
                lettersonly: true,
            },
            last_name: {
                required: true,
                lettersonly: true,
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
            password: {
                minlength: 6,
            },
        },
        messages: {
            first_name: {
                required: "Please enter firstname",
                lettersonly: "Please enter valid firstname",
            },
            last_name: {
                required: "Please enter lastname",
                lettersonly: "Please enter valid lastname",
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
            password: {
                minlength: "Please enter password atleast 6 character!",
            },
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            const formbtn = $("#module_form_btn");
            const formloader = $("#module_form_loader");
            console.log(formloader);
            let formData = new FormData(form);

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
                error: function (result) {
                    var errors = result.responseJSON.errors;
                    // Clear previous error messages
                    $(".error-message").text("");
                    // Display validation errors in form fields
                    $.each(errors, function (field, messages) {
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
        },
    });

    var table = $("#data_table_main").DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        columnDefs: [
            {
                className: "align-middle",
                targets: "_all",
            },
        ],
        // dom: 'Bfrtip',
        dom: '<f<t><"cm-dataTables-footer d-flex align-items-center float-right"lip>>',
        oLanguage: {
            sInfo: "_START_-_END_ of _TOTAL_", // text you want show for info section
            sLengthMenu: "_MENU_",
        },
        buttons: [],
        ajax: module_index_url,
        order: [],
        select: {
            style: "multi",
        },
        columns: [
            {
                data: "action_edit",
                name: "action_edit",
                searchable: false,
                orderable: false,
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
                data: "status_text",
                name: "status",
            },
            {
                data: "action_delete",
                name: "action_delete",
                searchable: false,
                orderable: false,
            },
        ],
    });
});
