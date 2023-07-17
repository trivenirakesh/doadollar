<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CommonHelper;
use App\Traits\CommonTrait;
use App\Models\Campaign;
use App\Models\CampaignUploads;
use App\Models\CampaignCategory;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\V1\CampaignResource;

class CampaignController extends Controller
{
    use CommonTrait;
    const module = 'Campaign';

    public function index(){
       // get super admin & managers list
       $getCampaignDetails = new Campaign();
       $getCampaignDetails = $getCampaignDetails->orderBy('id','DESC')->paginate(10);

       return CampaignResource::collection($getCampaignDetails);
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

        
        $getCampaignCategoryDetails = $this->getCampaignDetails($lastId, 0);
        $getCategoryDetails = CampaignResource::collection($getCampaignCategoryDetails);
        return $this->successResponse($getCategoryDetails, self::module.__('messages.success.create'), 201);
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
        $checkCategoryData = $checkCategory;
        if (!empty($checkCategoryData)) {
            $checkCategoryData->deleted_by = $getAdminDetails->id;
            // $checkCategoryData->deleted_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
            $checkCategoryData->deleted_ip = CommonHelper::getUserIp();
            $checkCategoryData->update();
            $deleteCampaignCategory = Campaign::find($id)->delete();
            if ($deleteCampaignCategory) {
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
