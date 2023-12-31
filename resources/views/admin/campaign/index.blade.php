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
                                        <th>Donation Target</th>
                                        <th>Created By</th>
                                        <th>Start Datetime</th>
                                        <th>End Datetime</th>
                                        <th>Created At</th>
                                        <th>Unique Url</th>
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
<script src="{{asset('public/asset/js/campaign/index.js')}}"></script>
@endpush