<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\CampaignCategory;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\CampaignCategoryResource;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Cache;

class CampaignCategoryService
{
    use CommonTrait;
    const module = 'Campaign Category';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expiry = CommonHelper::getConfigValue('cache_expiry');
        $data =  (Cache::remember('campaignCategory', $expiry, function () {
            return CampaignCategoryResource::collection(CampaignCategory::latest('id')->get());
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
        $createCampaignCat = new CampaignCategory();
        // remove blank spaces from string 
        $campaignCatName = ucfirst(strtolower(str_replace(' ', '', $request->name)));
        $createCampaignCat->name = $campaignCatName;
        $createCampaignCat->description = $request->description;
        $createCampaignCat->created_by = auth()->user()->id;
        $createCampaignCat->created_ip = CommonHelper::getUserIp();
        $createCampaignCat->save();
        $lastId = $createCampaignCat->id;
        // Update filename & path
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image, 'campaigncategory/' . $lastId . '/');
            if (!empty($data)) {
                $updateImageData = CampaignCategory::find($lastId);
                $updateImageData->file_name = $data['filename'];
                $updateImageData->path = $data['path'];
                $updateImageData->update();
            }
        }
        $campaignCategory =  CampaignCategory::where('id', $lastId)->first();
        $getCampaignCategoryDetails = new CampaignCategoryResource($campaignCategory);
        return $this->successResponseArr(self::module . __('messages.success.create'), $getCampaignCategoryDetails);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getCampaignCategoryData = CampaignCategory::where('id', $id)->first();
        if ($getCampaignCategoryData == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $getCampaignCategoryData = new CampaignCategoryResource($getCampaignCategoryData);
        return $this->successResponseArr(self::module . __('messages.success.details'), $getCampaignCategoryData);
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
        $campaignCategory = CampaignCategory::where('id', $id)->first();
        if ($campaignCategory == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        // remove blank spaces from string 
        $campaignCatName = ucfirst(strtolower(str_replace(' ', '', $request->name)));
        $campaignCategory->name = $campaignCatName;
        $campaignCategory->description = $request->description;
        $campaignCategory->status = $request->status;
        // get logged in user details 
        $campaignCategory->updated_by = auth()->user()->id;
        $campaignCategory->updated_ip = CommonHelper::getUserIp();
        // Update filename & path
        if ($request->hasFile('image')) {
            // Unlink old image from storage 
            $pathName = $campaignCategory->path;
            $fileName = $campaignCategory->file_name;
            CommonHelper::removeUploadedImages($pathName, $fileName);
            // Unlink old image from storage 
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image, 'campaigncategory/' . $id . '/');
            if (!empty($data)) {
                $campaignCategory->file_name = $data['filename'];
                $campaignCategory->path = $data['path'];
            }
        }
        $campaignCategory->update();
        $getCampaignCategoryDetails = new CampaignCategoryResource($campaignCategory);
        return $this->successResponseArr(self::module . __('messages.success.update'), $getCampaignCategoryDetails);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campaignCategory =  CampaignCategory::where('id', $id)->first();
        if ($campaignCategory == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        // Delete campaign category
        $campaignCategory->deleted_by = auth()->user()->id;
        $campaignCategory->deleted_ip = CommonHelper::getUserIp();
        $campaignCategory->update();
        $deleteCampaignCategory = $campaignCategory->delete();
        if ($deleteCampaignCategory) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}