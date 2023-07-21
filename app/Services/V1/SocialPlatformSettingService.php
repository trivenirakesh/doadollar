<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\SocialPlatformSetting;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\SocialPlatformSettingResource;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Cache;

class SocialPlatformSettingService
{
    use CommonTrait;
    const module = 'Social platform setting';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expiry = CommonHelper::getConfigValue('cache_expiry');
        $data =  (Cache::remember('socialPlatformSetting', $expiry, function () {
            return SocialPlatformSettingResource::collection(SocialPlatformSetting::latest('id')->get());
        }));
        return $this->successResponseArr(self::module . __('messages.success.list'), $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // save details 
        $createSetting = new SocialPlatformSetting();
        // remove blank spaces from string 
        $paymentGatewaySettingName = ucfirst(strtolower(str_replace(' ', '', $request->name)));
        $createSetting->name = $paymentGatewaySettingName;
        $createSetting->api_key = $request->api_key;
        $createSetting->secret_key = $request->secret_key;
        
        // get logged in user details 
        $createSetting->created_by = auth()->user()->id;
        $createSetting->created_ip = CommonHelper::getUserIp();
        $createSetting->save();
        $lastId = $createSetting->id;
        // Update filename & path
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image, 'paymentgateway/' . $lastId.'/');
            if (!empty($data)) {
                $updateImageData = SocialPlatformSetting::find($lastId);
                $updateImageData->file_name = $data['filename'];
                $updateImageData->path = $data['path'];
                $updateImageData->update();
            }
        }
        $getSocialSetting =  SocialPlatformSetting::where('id', $lastId)->first();
        $getSocialPlatformSettingDetails = new SocialPlatformSettingResource($getSocialSetting);
        return $this->successResponseArr(self::module . __('messages.success.create'), $getSocialPlatformSettingDetails);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getSocialPlatformData = SocialPlatformSetting::where('id', $id)->first();
        if ($getSocialPlatformData == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $getSocialPlatformData = new SocialPlatformSetting($getSocialPlatformData);
        return $this->successResponseArr(self::module . __('messages.success.details'), $getSocialPlatformData);
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
        // update details 
        $socialPlatformSetting = SocialPlatformSetting::where('id', $id)->first();;
        if ($socialPlatformSetting == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        // remove blank spaces from string 
        $socialPlatformSettingName = ucfirst(strtolower(str_replace(' ', '',$request->name)));
        $socialPlatformSetting->name = $socialPlatformSettingName;
        $socialPlatformSetting->api_key = $request->api_key;
        $socialPlatformSetting->secret_key = $request->secret_key;
        $socialPlatformSetting->status = $request->status;
        // get logged in user details 
        $socialPlatformSetting->updated_by = auth()->user()->id;
        $socialPlatformSetting->updated_ip = CommonHelper::getUserIp();
        // Update filename & path
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image, 'paymentgateway/' . $id.'/');
            if (!empty($data)) {
                $socialPlatformSetting->file_name = $data['filename'];
                $socialPlatformSetting->path = $data['path'];
            }
        }
        $socialPlatformSetting->update();
        $getSettingDetails = new SocialPlatformSettingResource($socialPlatformSetting);
        return $this->successResponseArr($getSettingDetails,self::module.__('messages.success.update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $socialPlatformSetting =  SocialPlatformSetting::where('id', $id)->first();
        if ($socialPlatformSetting == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        // Delete entity
        $socialPlatformSetting->deleted_by = auth()->user()->id;
        $socialPlatformSetting->deleted_ip = CommonHelper::getUserIp();
        $socialPlatformSetting->update();
        $deleteCampaignCategory = $socialPlatformSetting->delete();
        if ($deleteCampaignCategory) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}