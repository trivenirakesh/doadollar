<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CommonHelper;
use App\Traits\CommonTrait;
use App\Models\Campaign;
use App\Models\CampaignUploads;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\V1\CampaignResource;
use App\Http\Resources\V1\CampaignDetailResource;
use DB;

class CampaignController extends Controller
{
    use CommonTrait;
    const module = 'Campaign';

    public function index(Request $request){

        $campaign = new Campaign();
        $campaignListData = $campaign->getCampaignsList($request);
        $getCampaignList =  CampaignResource::collection($campaignListData['data']);
        $responseArr['totalRecords'] = $campaign->getCampaignsListCount();
        $responseArr['filterResults'] = $campaignListData['count'];
        $responseArr['getCampaignList'] = $getCampaignList;

        return $this->successResponse($responseArr,'Campaign List ' , 200);
    }

    public function show($id){
        $getCampaignDetails = $this->getCampaignDetails($id,0);
        return $this->successResponse(CampaignDetailResource::collection($getCampaignDetails),self::module.__('messages.success.details') , 200);
    }

    public function store(Request $request)
    {
        // Validation section
        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'unique_code' => 'required|unique:campaigns,unique_code,NULL,id,deleted_at,NULL',
                'campaign_category_id' => 'required',
                'start_datetime' => 'required',
                'end_datetime' => 'required',
                'donation_target' => 'required|numeric',
                'image' => 'required|max:2048|mimes:jpg,png,jpeg'
            ],
            [
                'name.required' => __('messages.validation.name'),
                'unique_code.required' => __('messages.validation.unique_code'),
                'unique_code.unique' => __('messages.validation.unique_code_unique'),
                'campaign_category_id.required' => __('messages.validation.campaign_category_id'),
                'start_datetime.required' => __('messages.validation.start_datetime'),
                'end_datetime.required' => __('messages.validation.end_datetime'),
                'donation_target.required' => __('messages.validation.donation_target'),
                'donation_target.numeric' => 'Donation target'.__('messages.validation.must_numeric'),
                'image.required' => __('messages.validation.image'),
                'image.max' => __('messages.validation.image-max'),
                'image.mimes' => __('messages.validation.image-mimes'),
            ]
        );

        if ($validateUser->fails()) {
            return $this->errorResponse($validateUser->errors(), 401);
        }

        $campaignCategoryId = $request->campaign_category_id;
        $campaignCategoryDetails = (new CampaignCategoryController)->getCampaignCatDetails($campaignCategoryId,1);

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();

        // save details
        $campaignName = ucfirst(strtolower(str_replace(' ', '', $request->name)));

        $createCampaign = new Campaign();
        $createCampaign->campaign_category_id = $campaignCategoryId;
        $createCampaign->name = $campaignName;
        $createCampaign->description = $request->description;
        $createCampaign->unique_code = $request->unique_code;
        $createCampaign->start_datetime = CommonHelper::getUTCDateTime($request->start_datetime);
        $createCampaign->end_datetime = CommonHelper::getUTCDateTime($request->end_datetime);
        $createCampaign->donation_target = $request->donation_target;
        if (!empty($getAdminDetails)) {
            $createCampaign->created_by = $getAdminDetails->id;
            $createCampaign->created_ip = CommonHelper::getUserIp();
        }
        $createCampaign->save();
        $lastId = $createCampaign->id;
      
        // Update filename & path
        if ($request->hasFile('image')) {

            $uploadPath = 'campaign/'.$lastId.'/';
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image, $uploadPath);
            if (!empty($data)) {
                $updateImageData = Campaign::find($lastId);
                $updateImageData->cover_image = $data['filename'];
                $updateImageData->cover_image_path = $data['path'];
            }

            // generate QR code 
            
            $qrFile = \QrCode::generate(url($request->unique_code));
            $qrFileName = 'qr_'.date('YmdHis') . '.svg';
            $qrFileNameWithPath = $uploadPath.$qrFileName;
            Storage::disk('public')->put($qrFileNameWithPath, $qrFile); 
            $updateImageData->qr_image = $qrFileName;
            $updateImageData->qr_path = 'public/storage/'.$uploadPath;
            $updateImageData->update();
        }

        // save uploads & links 
        if($request->has('upload_types')){
            $uploadTypes = $request->upload_types;
            if(count($uploadTypes) > 0){
                for ($i=0; $i < count($uploadTypes); $i++) { 
                    $saveUploadArr = new CampaignUploads();
                    $saveUploadArr->campaign_id = $lastId;
                    $saveUploadArr->upload_type_id = $request->upload_types[$i];
                    $saveUploadArr->title = $request->upload_title[$i];
                    $saveUploadArr->description = $request->upload_description[$i];
                    $uploadPath = 'campaign/'.$lastId.'/';
                    
                    $uploadImage = $request->file('upload_file')[$i];
                    if(!empty($uploadImage)){
                        $data = CommonHelper::uploadImages($uploadImage, $uploadPath,$i);
                        if (!empty($data)) {
                            $saveUploadArr->file_name = $data['filename'];
                            $saveUploadArr->path = $data['path'];
                        }
                    }
                    if (!empty($getAdminDetails)) {
                        $saveUploadArr->created_by = $getAdminDetails->id;
                        $saveUploadArr->created_ip = CommonHelper::getUserIp();
                    }
                    $saveUploadArr->save();
                }
            }
        }

        if($request->has('link_type')){
            $linkTypes = $request->link_type;
            if(count($linkTypes) > 0){
                for ($i=0; $i < count($linkTypes); $i++) { 
                    $saveLinksArr = new CampaignUploads();
                    $saveLinksArr->campaign_id = $lastId;
                    $saveLinksArr->upload_type_id = $request->link_type[$i];
                    $saveLinksArr->title = $request->link_title[$i];
                    $saveLinksArr->description = $request->link_description[$i];
                    $saveLinksArr->link = $request->link[$i];
                    if (!empty($getAdminDetails)) {
                        $saveUploadArr->created_by = $getAdminDetails->id;
                        $saveUploadArr->created_ip = CommonHelper::getUserIp();
                    }
                    $saveLinksArr->save();
                }
            }
        }
        
        $getCampaignCategoryDetails = $this->getCampaignDetails($lastId, 0);
        $getCategoryDetails = CampaignResource::collection($getCampaignCategoryDetails);
        return $this->successResponse($getCategoryDetails, self::module.__('messages.success.create'), 201);
    }

    public function update(Request $request, $id){

        $checkCampaignData = $this->getCampaignDetails($id,1);
        // Validation section
        $rules = [
            'name' => 'required',
            'unique_code' => 'required|unique:campaigns,unique_code,'.$id.',id,deleted_at,NULL',
            'campaign_category_id' => 'required',
            'start_datetime' => 'required',
            'end_datetime' => 'required',
            'donation_target' => 'required|numeric',
        ];

        $messages = [
            'name.required' => __('messages.validation.name'),
            'unique_code.required' => __('messages.validation.unique_code'),
            'unique_code.unique' => __('messages.validation.unique_code_unique'),
            'campaign_category_id.required' => __('messages.validation.campaign_category_id'),
            'start_datetime.required' => __('messages.validation.start_datetime'),
            'end_datetime.required' => __('messages.validation.end_datetime'),
            'donation_target.required' => __('messages.validation.donation_target'),
            'donation_target.numeric' => 'Donation target'.__('messages.validation.must_numeric'),
        ];

        if ($request->hasFile('image')) {
            $rules['image'] = 'required|max:2048|mimes:jpg,png,jpeg';
            $messages['image.required'] = __('messages.validation.image');
            $messages['image.max'] = __('messages.validation.image-max');
            $messages['image.mimes'] = __('messages.validation.image-mimes');
        }
        $validateUser = Validator::make($request->all(), $rules, $messages);
        if ($validateUser->fails()) {
            return $this->errorResponse($validateUser->errors(), 401);
        }

        $campaignCategoryId = $request->campaign_category_id;
        $campaignCategoryDetails = (new CampaignCategoryController)->getCampaignCatDetails($campaignCategoryId,1);

        // save details
        $campaignName = ucfirst(strtolower(str_replace(' ', '', $request->name)));

        $updateCampaign = $checkCampaignData;
        $updateCampaign->campaign_category_id = $campaignCategoryId;
        $updateCampaign->name = $campaignName;
        $updateCampaign->description = $request->description;
        $updateCampaign->unique_code = $request->unique_code;
        $updateCampaign->start_datetime = CommonHelper::getUTCDateTime($request->start_datetime);
        $updateCampaign->end_datetime = CommonHelper::getUTCDateTime($request->end_datetime);
        $updateCampaign->donation_target = $request->donation_target;

        // Update filename & path
        $uploadPath = 'campaign/'.$id.'/';
        if ($request->hasFile('image')) {

            // Unlink old image from storage 
            if(!empty($checkCampaignData)){
                $pathName = $checkCampaignData->cover_image_path;
                $fileName = $checkCampaignData->cover_image;
               CommonHelper::removeUploadedImages($pathName,$fileName);
            }
            // Unlink old image from storage 

            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image, $uploadPath);
            if (!empty($data)) {
                $updateCampaign->cover_image = $data['filename'];
                $updateCampaign->cover_image_path = $data['path'];
            }

        }
        // generate QR code 
        $getCampaignFullDetails = $this->getCampaignDetails($id,1);
        if($getCampaignFullDetails->unique_code != $request->unique_code){
            // Unlink qr from storage 
            if(!empty($checkCampaignData)){
                $pathName = $getCampaignFullDetails->qr_path;
                $fileName = $getCampaignFullDetails->qr_image;
                CommonHelper::removeUploadedImages($pathName,$fileName);
            }
            // Unlink qr from storage 

            $qrFile = \QrCode::generate(url($request->unique_code));
            $qrFileName = 'qr_'.date('YmdHis') . '.svg';
            $qrFileNameWithPath = $uploadPath.$qrFileName;
            Storage::disk('public')->put($qrFileNameWithPath, $qrFile); 
            $updateCampaign->qr_image = $qrFileName;
            $updateCampaign->qr_path = 'public/storage/'.$uploadPath;
        }

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();

        if (!empty($getAdminDetails)) {
            $updateCampaign->updated_by = $getAdminDetails->id;
            $updateCampaign->updated_ip = CommonHelper::getUserIp();
        }
        $updateCampaign->update();
        // save uploads & links 
        if($request->has('upload_types')){
            $uploadTypes = $request->upload_types;
            if(count($uploadTypes) > 0){
                for ($i=0; $i < count($uploadTypes); $i++) { 
                    if(isset($request->upload_id[$i])){
                        $uploadId = $request->upload_id[$i];
                        $checkCampaignUploads = CampaignUploads::where('id',$uploadId)->first();
                        if(!empty($checkCampaignUploads)){
                            $checkCampaignUploads->upload_type_id = $request->upload_types[$i];
                            $checkCampaignUploads->title = $request->upload_title[$i];
                            $checkCampaignUploads->description = $request->upload_description[$i];
                            if(isset($request->file('upload_file')[$i])){
                                $uploadImage = $request->file('upload_file')[$i];
                                if(!empty($uploadImage)){
                                    // Unlink qr from storage 
                                    if(!empty($checkCampaignData)){
                                        $pathName = $checkCampaignUploads->path;
                                        $fileName = $checkCampaignUploads->file_name;
                                        CommonHelper::removeUploadedImages($pathName,$fileName);
                                    }
                                    // Unlink qr from storage 
                                    $data = CommonHelper::uploadImages($uploadImage, $uploadPath,$i);
                                    if (!empty($data)) {
                                        $checkCampaignUploads->file_name = $data['filename'];
                                        $checkCampaignUploads->path = $data['path'];
                                    }
                                }
                            }
                            if (!empty($getAdminDetails)) {
                                $checkCampaignUploads->updated_by = $getAdminDetails->id;
                                $checkCampaignUploads->updated_ip = CommonHelper::getUserIp();
                            }
                            $checkCampaignUploads->update();
                        }
                    }else{
                        $saveUploadArr = new CampaignUploads();
                        $saveUploadArr->campaign_id = $id;
                        $saveUploadArr->upload_type_id = $request->upload_types[$i];
                        $saveUploadArr->title = $request->upload_title[$i];
                        $saveUploadArr->description = $request->upload_description[$i];
                        if(isset($request->file('upload_file')[$i])){
                            $uploadImage = $request->file('upload_file')[$i];
                            if(!empty($uploadImage)){
                                $data = CommonHelper::uploadImages($uploadImage, $uploadPath,$i);
                                if (!empty($data)) {
                                    $saveUploadArr->file_name = $data['filename'];
                                    $saveUploadArr->path = $data['path'];
                                }
                            }
                        }
                        if (!empty($getAdminDetails)) {
                            $saveUploadArr->created_by = $getAdminDetails->id;
                            $saveUploadArr->created_ip = CommonHelper::getUserIp();
                        }
                        $saveUploadArr->save();
                    }
                }
            }
        }

        if($request->has('link_type')){
            $linkTypes = $request->link_type;
            if(count($linkTypes) > 0){
                for ($i=0; $i < count($linkTypes); $i++) { 
                    if(isset($request->link_id[$i])){
                        $linkId = $request->link_id[$i];
                        $checkCampaignLinks = CampaignUploads::where('id',$linkId)->first();
                        if(!empty($checkCampaignLinks)){
                            $checkCampaignLinks->upload_type_id = $request->link_type[$i];
                            $checkCampaignLinks->title = $request->link_title[$i];
                            $checkCampaignLinks->description = $request->link_description[$i];
                            $checkCampaignLinks->link = $request->link[$i];
                            if (!empty($getAdminDetails)) {
                                $checkCampaignLinks->updated_by = $getAdminDetails->id;
                                $checkCampaignLinks->updated_ip = CommonHelper::getUserIp();
                            }
                            $checkCampaignLinks->update();
                        }
                    }else{
                        $saveLinksArr = new CampaignUploads();
                        $saveLinksArr->campaign_id = $id;
                        $saveLinksArr->upload_type_id = $request->link_type[$i];
                        $saveLinksArr->title = $request->link_title[$i];
                        $saveLinksArr->description = $request->link_description[$i];
                        $saveLinksArr->link = $request->link[$i];
                        if (!empty($getAdminDetails)) {
                            $saveLinksArr->created_by = $getAdminDetails->id;
                            $saveLinksArr->created_ip = CommonHelper::getUserIp();
                        }
                        $saveLinksArr->save();

                    }
                }
            }
        }
        
        $getCampaignCategoryDetails = $this->getCampaignDetails($id, 0);
        $getCategoryDetails = CampaignResource::collection($getCampaignCategoryDetails);
        return $this->successResponse($getCategoryDetails, self::module.__('messages.success.update'), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){

        // check category exist or not 
        $checkCategory = $this->getCampaignDetails($id,1);

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();

        // Delete campaign category
        $checkCampaignData = $checkCategory;
        if (!empty($checkCampaignData)) {
            $checkCampaignData->deleted_by = $getAdminDetails->id;
            $checkCampaignData->deleted_ip = CommonHelper::getUserIp();
            $checkCampaignData->update();
            $deleteCampaign = Campaign::find($id)->delete();
            $updateCampaignUploads = CampaignUploads::where('campaign_id',$id)->update(['deleted_by' => $getAdminDetails->id,'deleted_ip' => CommonHelper::getUserIp()]);
            $deleteCampaignUploads = CampaignUploads::where('campaign_id',$id)->delete();
            if ($deleteCampaign || $deleteCampaignUploads) {
                return $this->successResponse([], self::module.__('messages.success.delete'), 200);
            }
        }
    }

    public function getCampaignDetails($id,$type){
        
        $getCampaignData = Campaign::where('id',$id);
        if($type == 1){
            $getCampaignData = $getCampaignData->first();
            if(!empty($getCampaignData)){
                return $getCampaignData;
            }else{
                throw new \ErrorException(self::module.__('messages.validation.not_found'));
            }
        }else{
            $getCampaignData = $getCampaignData->get();
            if(count($getCampaignData) > 0){
                return $getCampaignData;
            }else{
                throw new \ErrorException(self::module.__('messages.validation.not_found'));
            }
        }
    }

}
