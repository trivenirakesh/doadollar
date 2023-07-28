@extends('admin.layouts.admin')
@section('content')
<div class="p-4">
    <div class="content-header">
        <div class="container-fluid d-flex justify-content-between  align-items-center">
            <h2 class="theme_primary_text d-inline-block">{{$title}}</h2>
            <div class="text-right d-inline-block">
                <a class="theme_primary_btn btn btn-sm float-right  ml-2" href="{{route('admin.campaigns.create')}}"><i class="fa fa-plus" aria-hidden="true"></i> Add {{$title}}</a>
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
                                        <th class="text-center"></th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Unique Code</th>
                                        <th>Created By</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                        <th></th>
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
    <input type="hidden" id="module_index_url" value="{{ route('admin.campaigns.index') }}">
</div>
@include('admin.campaign-category.modal')
<!-- /.content -->

@endsection
@push('script')
<script>
    let module = $("#page_module").val();
    let module_index_url = $("#module_index_url").val();
    var table = $("#data_table_main").DataTable({
        processing: true,
        serverSide: true,
        dom: '<f<t><"cm-dataTables-footer d-flex align-items-center float-right"lip>>',
        oLanguage: {
            "sInfo": "_START_-_END_ of _TOTAL_", // text you want show for info section
            "sLengthMenu": "_MENU_"
        },
        buttons: [],
        ajax: module_index_url,
        order: [],
        select: {
            style: "multi",
        },
        columns: [{
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
                data: "unique_code",
                name: "unique_code",
            },
            {
                data: "entitymst.first_name",
                name: "entitymst.first_name",
                searchable: true,
                orderable: false,
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

    // delete resource
    $(document).on("click", ".module_delete_record", function() {
        const id = $(this).parent().data("id");
        const url = $(this).parent().data("url");
        deleteRecordModule(id, `${url}/${id}`);
    });
</script>
@endpush