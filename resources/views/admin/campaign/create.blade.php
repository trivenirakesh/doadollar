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
                                                <label id="files_uplaod_error" class="error"></label>
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
                                                <label id="video_error" class="error"></label>
                                            </div>
                                            <!-- add videos end -->

                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="submit" class="btn btn-primary" id="module_form_btn">Save<span style="display: none" id="module_form_loader"><i class="fa fa-spinner fa-spin"></i></span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <input type="hidden" id="index_page_route" value="{{route('admin.campaigns.index')}}">
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
<script src="{{asset('public/asset/js/campaign/create.js')}}"></script>
@endpush