<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CampaignCategory;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\CampaignCategoryResource;
use App\Helpers\CommonHelper;

class CampaignCategoryController extends Controller
{
    use CommonTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // get logged in user details 
        $getAuthDetails = auth('sanctum')->user();

        // get super admin & managers list
        $getEntityDetails = new CampaignCategory;

        if (!empty($request->search)) {
            $getEntityDetails = $getEntityDetails->where('name', 'like', "%" . $request->search . "%");
        }
        $orderColumn = 'id';
        $orderType = 'DESC';
        if ($request->has('column')) {
            $orderColumn = $request->column;
        }
        if ($request->has('column')) {
            $orderType = $request->type;
        }
        $getEntityDetails = $getEntityDetails->orderBy($orderColumn, $orderType)->paginate(10);

        return CampaignCategoryResource::collection($getEntityDetails);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getEntityDetails = $this->getCampaignCatDetails($id, 0);
        if (count($getEntityDetails) > 0) {
            return CampaignCategoryResource::collection($getEntityDetails);
        } else {
            return $this->errorResponse('Campaign category not found', 404);
        }
    }

    /**
     * Store a newly created entity in storage.
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
                'name.required' => 'Please enter name',
                'image.required' => 'Please select image',
                'image.max' => 'Please select below 2 MB images',
                'image.mimes' => 'Please select only jpg, png, jpeg files',
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
        $getEntityDetails = $this->getCampaignCatDetails($lastId, 0);
        $getUserDetails = CampaignCategoryResource::collection($getEntityDetails);
        return $this->successResponse($getUserDetails, 'Campaign category created successfully', 201);
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
        $checkUser = $this->getCampaignCatDetails($id,1);
        if(empty($checkUser)){
            return $this->errorResponse('Campaign category not found', 400);
        }

        // Validation section
        $rules = [];
        $messages = [];
        if ($request->has('name')) {
            $rules['name'] = 'required';
            $messages['name.required'] = 'Please enter name';
        }
        if ($request->hasFile('image')) {
            $rules['image'] = 'required|max:2048|mimes:jpg,png,jpeg';
            $messages['image.required'] = 'Please select image';
            $messages['image.max'] = 'Please select below 2 MB images';
            $messages['image.mimes'] = 'Please select only jpg, png, jpeg files';
        }
        if ($request->has('status')) {
            $rules['status'] = 'required';
            $messages['status.required'] = 'Please enter status';
        }

        $validateUser = Validator::make($request->all(), $rules, $messages);

        if ($validateUser->fails()) {
            return $this->errorResponse($validateUser->errors(), 400);
        }

        // remove blank spaces from string 
        $campaignCatName = ucfirst(strtolower(str_replace(' ', '',$request->name)));

        // update details 
        $updateCampaignCat = $checkUser;
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
        return $this->successResponse($getUserDetails, 'Campaign category updated successfully', 201);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){

        // check user exist or not 
        $checkUser = $this->getCampaignCatDetails($id,1);
        if(empty($checkUser)){
            return $this->errorResponse('Campaign category not found', 400);
        }

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();

        // Delete entity
        $checkUserData = $checkUser;
        if (!empty($checkUserData)) {
            $checkUserData->deleted_by = $getAdminDetails->id;
            // $checkUserData->deleted_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
            $checkUserData->deleted_ip = CommonHelper::getUserIp();
            $checkUserData->update();
            $deleteEntity = CampaignCategory::find($id)->delete();
            if ($deleteEntity) {
                return $this->successResponse([], 'Campaign category deleted successfully', 200);
            }
        }
    }

    public function getCampaignCatDetails($id,$type){
        
        $getCampaignCatData = CampaignCategory::where('id',$id);
        if($type == 1){
            $getCampaignCatData = $getCampaignCatData->first();
        }else{
            $getCampaignCatData = $getCampaignCatData->get();
        }
        return $getCampaignCatData;
    }
}
