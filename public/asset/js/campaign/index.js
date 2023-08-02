let module = $("#page_module").val();
let module_index_url = $("#module_index_url").val();
var table = $("#data_table_main").DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
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
    columnDefs: [
        {
            className: "align-middle",
            targets: "_all",
        },
    ],
    columns: [
        {
            data: "action_edit",
            name: "action_edit",
            searchable: false,
            orderable: false,
        },
        {
            data: "image",
            name: "image",
            searchable: false,
            orderable: false,
        },
        {
            data: "name",
            name: "name",
        },
        {
            data: "donation_target",
            name: "donation_target",
        },
        {
            data: "created_by",
            name: "entitymst.first_name",
        },
        {
            data: "start_datetime",
            name: "start_datetime",
        },
        {
            data: "end_datetime",
            name: "end_datetime",
        },
        {
            data: "created_at",
            name: "created_at",
        },
        {
            data: "unique_code",
            name: "unique_code",
        },
        {
            data: "campaign_status_text",
            name: "campaign_status",
        },
        {
            data: "action_delete",
            name: "action_delete",
            searchable: false,
            orderable: false,
        },
    ],
});

// delete resource
$(document).on("click", ".module_delete_record", function () {
    const id = $(this).parent().data("id");
    const url = $(this).parent().data("url");
    deleteRecordModule(id, `${url}/${id}`);
});
