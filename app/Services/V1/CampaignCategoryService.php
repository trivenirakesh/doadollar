<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\CampaignCategory;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\CampaignCategoryResource;
use App\Http\Resources\V1\CampaignCategoryDetailResource;
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

        // upload file 
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image,CampaignCategory::FOLDERNAME,0);
            if (!empty($data)) {
                $createCampaignCat->image = $data['filename'];
            }
        }
        $createCampaignCat->save();
        $getCampaignCategoryDetails = new CampaignCategoryResource($createCampaignCat);
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
        $getCampaignCategoryData = new CampaignCategoryDetailResource($getCampaignCategoryData);
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
        
        // Update file
        if ($request->hasFile('image')) {
            // Unlink old image from storage 
            $oldImage = $campaignCategory->getAttributes()['image'] ?? null;
            if($oldImage != null){
                CommonHelper::removeUploadedImages($oldImage,CampaignCategory::FOLDERNAME);
            }
            // Unlink old image from storage 

            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image,CampaignCategory::FOLDERNAME,0);
            if (!empty($data)) {
                $campaignCategory->image = $data['filename'];
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