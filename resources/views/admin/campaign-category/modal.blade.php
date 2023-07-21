<!-- View details -->
<div class="modal fade" id="view_module_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="show_modal_title">View</h4>
                <button type="button" class="close" style="font-size: 20px;" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="card-body align-items-center justify-content-center loader" id="modal_loader1" style="display: none;"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>
                <div class="card-body" id="modal_body_part">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img id="info_image" width="200" height="200" class="profile-img img-circle" src="" alt="picture">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="col-form-label"><b>Name</b></label><br>
                                            <p id="info_name"></p>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="col-form-label"><b>Description</b></label><br>
                                            <p id="info_description"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-secondary float-right">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- add update modal -->
<div class="modal fade" id="modal-add-update" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal_title">Large Modal</h4>
                <button type="button" class="close" style="font-size: 20px;" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <form class="form-horizontal" id="module_form" action="{{route('admin.campaign-category.store')}}" name="module_form" novalidate="novalidate">
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" name="id" id="id" value="">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Name <span class="red">*</span></label>
                                    <input type="text" class="form-control" placeholder="Please enter name" id="name" name="name" value="">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Description <span class="red">*</span></label>
                                    <textarea class="form-control" placeholder="Description" name="description" id="description" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <label>Image</label>
                                    <input type="file" name="image" id="image" class="form-control" style="padding: 0.200rem 0.75rem;" placeholder="Enter Select Image" onchange="load_preview_image(this);" accept="image/x-png,image/jpg,image/jpeg">
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div id="preview_div">
                                    <img id="image_preview" width="70" height="70" class="profile-user-img img-fluid" src="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="module_form_btn">Save<span style="display: none" id="module_form_loader"><i class="fa fa-spinner fa-spin"></i></span></button>
                </div>
            </form>
        </div>
    </div>
</div>