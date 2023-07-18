<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SocialPlatformSetting;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\SocialPlatformSettingResource;
use App\Helpers\CommonHelper;

class SocialPlatformSettingController extends Controller
{
    use CommonTrait;
    const module = 'Social platform setting';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $getSocialPlatformDetails = new SocialPlatformSetting();
        if(!empty($request->search)){
            $getSocialPlatformDetails = $getSocialPlatformDetails->where('name','like', "%".$request->search."%");
        }
        $orderColumn = 'id';
        $orderType = 'DESC';
        if($request->has('column')){
            $orderColumn = $request->column;
        }
        if($request->has('column')){
            $orderType = $request->type;
        }
        $getSocialPlatformDetails = $getSocialPlatformDetails->orderBy($orderColumn,$orderType)->paginate(10);
        return SocialPlatformSettingResource::collection($getSocialPlatformDetails); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getSocialPlatformDetails = $this->getSocialPlatformDetails($id, 0);
        return $this->successResponse(SocialPlatformSettingResource::collection($getSocialPlatformDetails), self::module.__('messages.success.details'), 200);
    }

    /**
     * Store a newly created social platform setting in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        // Validation section
        $validatePlatform = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'api_key' => 'required|alpha_num',
                'secret_key' => 'required|alpha_num',
                'image' => 'required|max:2048|mimes:jpg,png,jpeg'
            ],
            [
                'name.required' => __('messages.validation.name'),
                'api_key.required' => __('messages.validation.api_key'),
                'api_key.alpha_num' => 'Api key'.__('messages.validation.alpha_num'),
                'secret_key.required' => __('messages.validation.secret_key'),
                'secret_key.alpha_num' => 'Secret key'.__('messages.validation.alpha_num'),
                'image.required' => __('messages.validation.image'),
                'image.max' => __('messages.validation.image-max'),
                'image.mimes' => __('messages.validation.image-mimes'),
            ]
        );

        if ($validatePlatform->fails()) {
            return $this->errorResponse($validatePlatform->errors(), 401);
        }

        // save details 

        // remove blank spaces from string 
        $socialPlatformName = ucfirst(strtolower(str_replace(' ', '', $request->name)));

        $createPlatform = new SocialPlatformSetting();
        $createPlatform->name = $socialPlatformName;
        $createPlatform->api_key = $request->api_key;
        $createPlatform->secret_key = $request->secret_key;
        
        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();
        if (!empty($getAdminDetails) && !empty($getAdminDetails->id)) {
            $createPlatform->created_by = $getAdminDetails->id;
            $createPlatform->created_ip = CommonHelper::getUserIp();
        }
        $createPlatform->save();
        $lastId = $createPlatform->id;
        // Update filename & path
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image, 'socialplatform/' . $lastId.'/');
            if (!empty($data)) {
                $updateImageData = SocialPlatformSetting::find($lastId);
                $updateImageData->file_name = $data['filename'];
                $updateImageData->path = $data['path'];
                $updateImageData->update();
            }
        }
        $getPlatformDetails = $this->getSocialPlatformDetails($lastId, 0);
        $getSettingDetails = SocialPlatformSettingResource::collection($getPlatformDetails);
        return $this->successResponse($getSettingDetails, self::module.__('messages.success.create'), 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id){
        
        // check user exist or not 
        $checkPlatform = $this->getSocialPlatformDetails($id,1);

        // Validation section
        
        $rules['name'] = 'required';
        $rules['api_key'] = 'required|alpha_num';
        $rules['secret_key'] = 'required|alpha_num';
        $messages['name.required'] = __('messages.validation.name');
        $messages['api_key.required'] = __('messages.validation.api_key');
        $messages['api_key.alpha_num'] = 'Api key'.__('messages.validation.alpha_num');
        $messages['secret_key.required'] = __('messages.validation.secret_key');
        $messages['secret_key.alpha_num'] = 'Secret key'.__('messages.validation.alpha_num');
        
        if ($request->has('status')) {
            $rules['status'] = 'required|numeric|lte:1';
            $messages['status.required'] = __('messages.validation.status');
            $messages['status.numeric'] = __('messages.validation.status_numeric');
            $messages['status.lte'] = __('messages.validation.status_lte');
        }

        if ($request->hasFile('image')) {
            $rules['image'] = 'required|max:2048|mimes:jpg,png,jpeg';
            $messages['image.required'] = __('messages.validation.image');
            $messages['image.max'] = __('messages.validation.image-max');
            $messages['image.mimes'] = __('messages.validation.image-mimes');
        }

        $validatePlatform = Validator::make($request->all(), $rules, $messages);

        if ($validatePlatform->fails()) {
            return $this->errorResponse($validatePlatform->errors(), 400);
        }

        // remove blank spaces from string 
        $socialPlatformName = ucfirst(strtolower(str_replace(' ', '',$request->name)));

        // update details 
        $updatePlatformSetting = $checkPlatform;
        $updatePlatformSetting->name = $socialPlatformName;
        $updatePlatformSetting->api_key = $request->api_key;
        $updatePlatformSetting->secret_key = $request->secret_key;
        $updatePlatformSetting->status = $request->status;
        $updatePlatformSetting->updated_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();
        if (!empty($getAdminDetails) && !empty($getAdminDetails->id)) {
            $updatePlatformSetting->updated_by = $getAdminDetails->id;
            $updatePlatformSetting->updated_ip = CommonHelper::getUserIp();
        }
        // Update filename & path
        if ($request->hasFile('image')) {
            // Unlink old image from storage 
            $getSocialPlatformData = $this->getSocialPlatformDetails($id,1);
            if(!empty($getSocialPlatformData)){
                $pathName = $getSocialPlatformData->path;
                $fileName = $getSocialPlatformData->file_name;
               CommonHelper::removeUploadedImages($pathName,$fileName);
            }
            // Unlink old image from storage 
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image, 'socialplatform/' . $id.'/');
            if (!empty($data)) {
                $updatePlatformSetting->file_name = $data['filename'];
                $updatePlatformSetting->path = $data['path'];
            }
        }
        $updatePlatformSetting->update();

        $getPlatformDetails = $this->getSocialPlatformDetails($id,0);
        $getSettingDetails = SocialPlatformSettingResource::collection($getPlatformDetails);
        return $this->successResponse($getSettingDetails,self::module.__('messages.success.update') , 200);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){

        // check user exist or not 
        $checkPlatform = $this->getSocialPlatformDetails($id,1);

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();

        // Delete social platform setting
        $checkPlatformData = $checkPlatform;
        if (!empty($checkPlatformData)) {
            $checkPlatformData->deleted_by = $getAdminDetails->id;
            // $checkPlatformData->deleted_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
            $checkPlatformData->deleted_ip = CommonHelper::getUserIp();
            $checkPlatformData->update();
            $deletePlatformSetting = SocialPlatformSetting::find($id)->delete();
            if ($deletePlatformSetting) {
                return $this->successResponse([],self::module.__('messages.success.delete'), 200);
            }
        }
    }

    public function getSocialPlatformDetails($id,$type){
        $getSocialPlatformSettingData = SocialPlatformSetting::where('id',$id);
        if($type == 1){
            $getSocialPlatformSettingData = $getSocialPlatformSettingData->first();
            if(!empty($getSocialPlatformSettingData)){
                return $getSocialPlatformSettingData;
            }else{
                throw new \ErrorException(self::module.__('messages.validation.not_found'));
            }
        }else{
            $getSocialPlatformSettingData = $getSocialPlatformSettingData->get();
            if(count($getSocialPlatformSettingData) > 0){
                return $getSocialPlatformSettingData;
            }else{
                throw new \ErrorException(self::module.__('messages.validation.not_found'));
            }
        }
        
    }
}
