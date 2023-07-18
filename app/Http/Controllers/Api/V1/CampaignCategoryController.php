<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CampaignCategory;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\CampaignCategoryResource;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Cache;

class CampaignCategoryController extends Controller
{
    use CommonTrait;
    const module = 'Campaign category';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CampaignCategoryResource::collection(Cache::remember('campaignCategory',60*60*24,function(){
            return CampaignCategory::latest('id')->get();
        })); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getCampaignCategoryDetails = $this->getCampaignCatDetails($id, 0);
        return $this->successResponse(CampaignCategoryResource::collection($getCampaignCategoryDetails),self::module.__('messages.success.details'), 200);
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        // Validation section
        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'image' => 'required|max:2048|mimes:jpg,png,jpeg'
            ],
            [
                'name.required' => __('messages.validation.name'),
                'image.required' => __('messages.validation.image'),
                'image.max' => __('messages.validation.image-max'),
                'image.mimes' => __('messages.validation.image-mimes'),
            ]
        );

        if ($validateUser->fails()) {
            return $this->errorResponse($validateUser->errors(), 401);
        }

        // save details 

        // remove blank spaces from string 
        $campaignCatName = ucfirst(strtolower(str_replace(' ', '', $request->name)));

        $createCampaignCat = new CampaignCategory();
        $createCampaignCat->name = $campaignCatName;
        $createCampaignCat->description = $request->description;
        $createCampaignCat->status = 1;
        
        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();
        if (!empty($getAdminDetails) && !empty($getAdminDetails->id)) {
            $createCampaignCat->created_by = $getAdminDetails->id;
            $createCampaignCat->created_ip = CommonHelper::getUserIp();
        }
        $createCampaignCat->save();
        $lastId = $createCampaignCat->id;
        // Update filename & path
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image, 'campaigncategory/' . $lastId.'/');
            if (!empty($data)) {
                $updateImageData = CampaignCategory::find($lastId);
                $updateImageData->file_name = $data['filename'];
                $updateImageData->path = $data['path'];
                $updateImageData->update();
            }
        }
        $getCampaignCategoryDetails = $this->getCampaignCatDetails($lastId, 0);
        $getCategoryDetails = CampaignCategoryResource::collection($getCampaignCategoryDetails);
        return $this->successResponse($getCategoryDetails, self::module.__('messages.success.create'), 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id){
        
        // check category exist or not 
        $checkCategory = $this->getCampaignCatDetails($id,1);

        // Validation section
        $rules = [];
        $messages = [];
        if ($request->has('name')) {
            $rules['name'] = 'required';
            $messages['name.required'] = __('messages.validation.name');
        }
        if ($request->hasFile('image')) {
            $rules['image'] = 'required|max:2048|mimes:jpg,png,jpeg';
            $messages['image.required'] = __('messages.validation.image');
            $messages['image.max'] = __('messages.validation.image-max');
            $messages['image.mimes'] = __('messages.validation.image-mimes');
        }
        if ($request->has('status')) {
            $rules['status'] = 'required|numeric|lte:1';
            $messages['status.required'] = __('messages.validation.status');
            $messages['status.numeric'] = 'Status'.__('messages.validation.must_numeric');
            $messages['status.lte'] = __('messages.validation.status_lte');
        }

        $validateUser = Validator::make($request->all(), $rules, $messages);

        if ($validateUser->fails()) {
            return $this->errorResponse($validateUser->errors(), 400);
        }

        // remove blank spaces from string 
        $campaignCatName = ucfirst(strtolower(str_replace(' ', '',$request->name)));

        // update details 
        $updateCampaignCat = $checkCategory;
        $updateCampaignCat->name = $campaignCatName;
        $updateCampaignCat->description = $request->description;
        $updateCampaignCat->status = $request->status;
        $updateCampaignCat->updated_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();
        if (!empty($getAdminDetails) && !empty($getAdminDetails->id)) {
            $updateCampaignCat->updated_by = $getAdminDetails->id;
            $updateCampaignCat->updated_ip = CommonHelper::getUserIp();
        }
        // Update filename & path
        if ($request->hasFile('image')) {
            // Unlink old image from storage 
            $getSocialPlatformData = $this->getCampaignCatDetails($id,1);
            if(!empty($getSocialPlatformData)){
                $pathName = $getSocialPlatformData->path;
                $fileName = $getSocialPlatformData->file_name;
               CommonHelper::removeUploadedImages($pathName,$fileName);
            }
            // Unlink old image from storage 
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image, 'campaigncategory/' . $id.'/');
            if (!empty($data)) {
                $updateCampaignCat->file_name = $data['filename'];
                $updateCampaignCat->path = $data['path'];
            }
        }
        $updateCampaignCat->update();

        $getUserDetails = $this->getCampaignCatDetails($id,0);
        $getUserDetails = CampaignCategoryResource::collection($getUserDetails);
        return $this->successResponse($getUserDetails, self::module.__('messages.success.update'), 200);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){

        // check category exist or not 
        $checkCategory = $this->getCampaignCatDetails($id,1);

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();

        // Delete campaign category
        $checkCategoryData = $checkCategory;
        if (!empty($checkCategoryData)) {
            $checkCategoryData->deleted_by = $getAdminDetails->id;
            // $checkCategoryData->deleted_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
            $checkCategoryData->deleted_ip = CommonHelper::getUserIp();
            $checkCategoryData->update();
            $deleteCampaignCategory = CampaignCategory::find($id)->delete();
            if ($deleteCampaignCategory) {
                return $this->successResponse([], self::module.__('messages.success.delete'), 200);
            }
        }
    }

    public function getCampaignCatDetails($id,$type){
        
        $getCampaignCatData = CampaignCategory::where('id',$id);
        if($type == 1){
            $getCampaignCatData = $getCampaignCatData->first();
            if(!empty($getCampaignCatData)){
                return $getCampaignCatData;
            }else{
                throw new \ErrorException(self::module.__('messages.validation.not_found'));
            }
        }else{
            $getCampaignCatData = $getCampaignCatData->get();
            if(count($getCampaignCatData) > 0){
                return $getCampaignCatData;
            }else{
                throw new \ErrorException(self::module.__('messages.validation.not_found'));
            }
        }
    }
}
