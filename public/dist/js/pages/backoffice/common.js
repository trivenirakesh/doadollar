let APP_URL = $("#appurl").val();

// common function for open modal
function globalFunctionModal(moduleName = "", operation, id = null) {
    $.ajax({
        url: APP_URL + "/createmodelhtml",
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: { modulename: moduleName, operation: operation },
        success: function (res) {
            $("#globalCrudModal").modal("show");
            $(".createupdatemodal").html(res);
            $("#globalCrudModalTitle").text(operation);
            $(".globalCrudModalSubmitBtn").text(operation);
        },
    });
}

// //
var csrf_token = $("meta[name=csrf-token]").attr("content");
function load_preview_image(
    input,
    div = "preview_div",
    imgDiv = "image_preview"
) {
    let imgParentDiv = `#${div}`;
    let imgPreiwDiv = `#${imgDiv}`;
    console.log(imgParentDiv, imgPreiwDiv);
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(imgParentDiv).show();
            $(imgPreiwDiv).attr("src", e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        $(imgParentDiv).hide();
    }
}

// reload datatables
function reload_data() {
    $("#data_table_main").DataTable().ajax.reload();
}

// delete modal
function deleteRecordModule(id, url) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "DELETE",
                data: {
                    _method: "DELETE",
                },
                url: url,
                headers: {
                    "X-CSRF-TOKEN": csrf_token,
                },
                success: function (response) {
                    if (response.status) {
                        $("#data_table_main").DataTable().ajax.reload();
                        toastr.success(response.message);
                    } else {
                        Swal.fire("error!", response.message, "error");
                    }
                },
                error: function () {
                    toastr.error("Please Reload Page.");
                },
            });
        }
    });
}

function Copy(element) {
    var urlToCopy = element.getAttribute("data-content");
    var tempInput = document.createElement("input");
    tempInput.value = urlToCopy;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);
    toastr.info("info", "copied to clipboard!");
}

$(document).keydown(function (event) {
    if (event.keyCode == 27) {
        $(".modal").modal("hide");
    }
});
