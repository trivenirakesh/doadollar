<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Models\CampaignUploads;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\CampaignResource;
use App\Helpers\CommonHelper;
use App\Http\Resources\V1\CampaignDetailResource;
use Illuminate\Support\Facades\Storage;

class CampaignService
{
    use CommonTrait;
    const module = 'Campaign';

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $campaignCategoryId = $request->campaign_category_id;
        $activeStatus = CommonHelper::getConfigValue('status.active');
        $checkCampaignCategory = CampaignCategory::where('id', $campaignCategoryId)->where('status', $activeStatus)->first();
        if (empty($checkCampaignCategory)) {
            return $this->errorResponseArr('Campaign Category' . __('messages.validation.not_found'));
        }
        // save details
        $createCampaign = new Campaign();
        // remove blank space from string   
        $campaignName = ucfirst(strtolower(str_replace(' ', '', $request->name)));

        $createCampaign->campaign_category_id = $campaignCategoryId;
        $createCampaign->name = $campaignName;
        $createCampaign->description = $request->description;
        $createCampaign->unique_code = $request->unique_code;
        $createCampaign->start_datetime = CommonHelper::getUTCDateTime($request->start_datetime);
        $createCampaign->end_datetime = CommonHelper::getUTCDateTime($request->end_datetime);
        $createCampaign->donation_target = $request->donation_target;
        $createCampaign->created_by = auth()->user()->id;
        $createCampaign->created_ip = CommonHelper::getUserIp();
        $createCampaign->save();
        $lastId = $createCampaign->id;

        // Update filename & path
        $uploadPath = 'campaign/' . $lastId . '/';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image, $uploadPath);
            if (!empty($data)) {
                $updateImageData = Campaign::find($lastId);
                $updateImageData->cover_image = $data['filename'];
                $updateImageData->cover_image_path = $data['path'];
                $updateImageData->update();
            }
        }

        // generate QR code 
        $qrFile = \QrCode::generate(url($request->unique_code));
        $qrFileName = 'qr_' . date('YmdHis') . '.svg';
        $qrFileNameWithPath = $uploadPath . $qrFileName;
        Storage::disk('public')->put($qrFileNameWithPath, $qrFile);
        $updateQrData = Campaign::find($lastId);
        $updateQrData->qr_image = $qrFileName;
        $updateQrData->qr_path = 'public/storage/' . $uploadPath;
        $updateImageData->update();
        // generate QR code 

        // save uploads & links 
        if ($request->has('upload_types')) {
            $uploadTypes = $request->upload_types;
            if (count($uploadTypes) > 0) {
                for ($i = 0; $i < count($uploadTypes); $i++) {
                    $saveUploadArr = new CampaignUploads();
                    $saveUploadArr->campaign_id = $lastId;
                    $saveUploadArr->upload_type_id = $request->upload_types[$i];
                    $saveUploadArr->title = $request->upload_title[$i];
                    $saveUploadArr->description = $request->upload_description[$i];
                    $saveUploadArr->created_by = auth()->user()->id;
                    $saveUploadArr->created_ip = CommonHelper::getUserIp();
                    if (isset($request->file('upload_file')[$i])) {
                        $uploadImage = $request->file('upload_file')[$i];
                        if (!empty($uploadImage)) {
                            $data = CommonHelper::uploadImages($uploadImage, $uploadPath, $i);
                            if (!empty($data)) {
                                $saveUploadArr->file_name = $data['filename'];
                                $saveUploadArr->path = $data['path'];
                            }
                        }
                    }
                    $saveUploadArr->save();
                }
            }
        }

        if ($request->has('link_type')) {
            $linkTypes = $request->link_type;
            if (count($linkTypes) > 0) {
                for ($i = 0; $i < count($linkTypes); $i++) {
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

        $getCampaignCategoryDetails = Campaign::where('id', $lastId)->first();
        $getCategoryDetails = new CampaignResource($getCampaignCategoryDetails);
        return $this->successResponseArr(self::module . __('messages.success.create'), $getCategoryDetails);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getCampaignDetails = Campaign::where('id', $id)->first();
        if ($getCampaignDetails == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $getCampaignDetails = new CampaignDetailResource($getCampaignDetails);
        return $this->successResponseArr(self::module . __('messages.success.details'), $getCampaignDetails);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $campaignCategoryId = $request->campaign_category_id;
        $activeStatus = CommonHelper::getConfigValue('status.active');
        $checkCampaignCategory = CampaignCategory::where('id', $campaignCategoryId)->where('status', $activeStatus)->first();
        if (empty($checkCampaignCategory)) {
            return $this->errorResponseArr('Campaign Category' . __('messages.validation.not_found'));
        }
        $updateCampaign = Campaign::where('id',$id)->first();
        if (empty($updateCampaign)) {
            return $this->errorResponseArr('Campaign' . __('messages.validation.not_found'));
        }
        // save details
        $campaignName = ucfirst(strtolower(str_replace(' ', '', $request->name)));
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
        if($updateCampaign->unique_code != $request->unique_code){
            // Unlink qr from storage 
            if(!empty($checkCampaignData)){
                $pathName = $updateCampaign->qr_path;
                $fileName = $updateCampaign->qr_image;
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
        $updateCampaign->updated_by = auth()->user()->id;
        $updateCampaign->updated_ip = CommonHelper::getUserIp();
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
                            $checkCampaignUploads->updated_by = auth()->user()->id;;
                            $checkCampaignUploads->updated_ip = CommonHelper::getUserIp();
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
                        $saveUploadArr->created_by = auth()->user()->id;;
                        $saveUploadArr->created_ip = CommonHelper::getUserIp();
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
                            $checkCampaignLinks->updated_by = auth()->user()->id;;
                            $checkCampaignLinks->updated_ip = CommonHelper::getUserIp();
                            $checkCampaignLinks->update();
                        }
                    }else{
                        $saveLinksArr = new CampaignUploads();
                        $saveLinksArr->campaign_id = $id;
                        $saveLinksArr->upload_type_id = $request->link_type[$i];
                        $saveLinksArr->title = $request->link_title[$i];
                        $saveLinksArr->description = $request->link_description[$i];
                        $saveLinksArr->link = $request->link[$i];
                        $saveLinksArr->created_by = auth()->user()->id;;
                        $saveLinksArr->created_ip = CommonHelper::getUserIp();
                        $saveLinksArr->save();

                    }
                }
            }
        }

        $getCampaignCategoryDetails = Campaign::where('id', $id)->first();
        $getCategoryDetails = new CampaignResource($getCampaignCategoryDetails);
        return $this->successResponseArr(self::module . __('messages.success.update'), $getCategoryDetails);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campaign =  Campaign::where('id', $id)->first();
        if ($campaign == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        // Delete campaign category
        $campaign->deleted_by = auth()->user()->id;
        $campaign->deleted_ip = CommonHelper::getUserIp();
        $campaign->update();
        $deleteCampaign = $campaign->delete();
        $updateCampaignUploads = CampaignUploads::where('campaign_id',$id)->update(['deleted_by' => auth()->user()->id,'deleted_ip' => CommonHelper::getUserIp()]);
        $deleteCampaignUploads = CampaignUploads::where('campaign_id',$id)->delete();
        if ($deleteCampaign || $deleteCampaignUploads) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}
