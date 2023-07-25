@extends('admin.layouts.admin')
@push('style')
<style>
    .ck-editor__editable_inline {
        min-height: 300px;
    }
</style>
@endpush
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
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card p-4">
                        <form method="post" id="static-page-form" name="static_page_form">
                            @csrf
                            <input type="hidden" name="title" value="{{$slug}}" id="page_slug">
                            <div class="card-body">
                                <textarea id="editor" name="static_page_form_editor">{{$content}}</textarea>
                                <div class="card-footer text-center">
                                    <button type="button" class="btn btn-primary btn-flat" id="static_page_form_btn" onclick="saveStaticPage(`{{route('admin.static_page_update')}}`)">Save<span style="display: none" id="static_page_form_loader"><i class="fa fa-spinner fa-spin"></i></span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Main content -->
</div>

<!-- /.content -->

@endsection
@push('script')
<!-- <script src="{{asset('public/plugins/ckeditor/ckeditor.js')}}"></script> -->
<script src="{{asset('public/plugins/ckeditor5/package/build/ckeditor.js')}}"></script>
<script src="{{asset('public/asset/js/staticpage.js')}}"></script>
@endpush