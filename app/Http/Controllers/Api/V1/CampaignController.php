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
use DB;

class CampaignController extends Controller
{
    use CommonTrait;
    const module = 'Campaign';

    public function index(Request $request){

    $perPageData = CommonHelper::getConfigValue('per_page');
    $campaignId = 0;
    $searchValue = '';
    $orderColumn = 'cam.id';
    $orderBy = 'DESC';
    $offset = 0;
    if(!empty($request->campaign_id)){
        $campaignId = $request->campaign_id;
    }
    if(!empty($request->search)){
        $searchValue = $request->search;
    }
    if(!empty($request->order_column)){
        $orderColumn = $request->order_column;
    }
    if(!empty($request->order_by)){
        $orderBy = $request->order_by;
    }
    if(!empty($request->offset)){
        $offset = $request->offset;
    }
    $campaignListQuery = "CALL sp_get_campaigns_list('$perPageData','$offset','$searchValue','$orderColumn','$orderBy','$campaignId')";
    $campaignListData = DB::select($campaignListQuery);
    $getCampaignList =  CampaignResource::collection($campaignListData);
    $totalRecordsData = DB::select("CALL sp_get_campaigns_list('0','0','','','','0')");
    $total = count($totalRecordsData);
    $responseArr['totalRecords'] = $total;
    $responseArr['getCampaignList'] = $getCampaignList;

    return $this->successResponse($responseArr,'Campaign List ' , 200);
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
                    $saveLinksArr->save();
                }
            }
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
        $checkCampaignData = $checkCategory;
        if (!empty($checkCampaignData)) {
            $checkCampaignData->deleted_by = $getAdminDetails->id;
            // $checkCampaignData->deleted_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
            $checkCampaignData->deleted_ip = CommonHelper::getUserIp();
            $checkCampaignData->update();
            $deleteCampaign = Campaign::find($id)->delete();
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
