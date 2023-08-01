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
        $createSetting->name = $request->name;
        $createSetting->api_key = $request->api_key;
        $createSetting->secret_key = $request->secret_key;

        // get logged in user details 
        $createSetting->created_by = auth()->user()->id;
        $createSetting->created_ip = CommonHelper::getUserIp();

        // upload file 
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image, SocialPlatformSetting::FOLDERNAME, 0);
            if (!empty($data)) {
                $createSetting->image = $data['filename'];
            }
        }
        $createSetting->save();
        $getSocialPlatformSettingDetails = new SocialPlatformSettingResource($createSetting);
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
        $getSocialPlatformData = new SocialPlatformSettingResource($getSocialPlatformData);
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
        $socialPlatformSetting = SocialPlatformSetting::where('id', $id)->first();
        if ($socialPlatformSetting == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $socialPlatformSetting->name = $request->name;
        $socialPlatformSetting->api_key = $request->api_key;
        $socialPlatformSetting->secret_key = $request->secret_key;
        $socialPlatformSetting->status = $request->status;
        // get logged in user details 
        $socialPlatformSetting->updated_by = auth()->user()->id;
        $socialPlatformSetting->updated_ip = CommonHelper::getUserIp();
        // Update file
        if ($request->hasFile('image')) {
            // Unlink old image from storage 
            $oldImage = $socialPlatformSetting->getAttributes()['image'] ?? null;
            if ($oldImage != null) {
                CommonHelper::removeUploadedImages($oldImage, SocialPlatformSetting::FOLDERNAME);
            }
            // Unlink old image from storage 

            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image, SocialPlatformSetting::FOLDERNAME, 0);
            if (!empty($data)) {
                $socialPlatformSetting->image = $data['filename'];
            }
        }
        $socialPlatformSetting->update();
        $getSettingDetails = new SocialPlatformSettingResource($socialPlatformSetting);
        return $this->successResponseArr(self::module . __('messages.success.update'), $getSettingDetails);
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
        // Delete social platform setting
        $socialPlatformSetting->deleted_by = auth()->user()->id;
        $socialPlatformSetting->deleted_ip = CommonHelper::getUserIp();
        $socialPlatformSetting->update();
        $deleteCampaignCategory = $socialPlatformSetting->delete();
        if ($deleteCampaignCategory) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}
