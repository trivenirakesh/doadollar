<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PaymentGatewaySetting;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\PaymentGatewaySettingResource;
use App\Helpers\CommonHelper;


class PaymentGatewaySettingController extends Controller
{
    use CommonTrait;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $getRoleDetails = new PaymentGatewaySetting();
        if(!empty($request->search)){
            $getRoleDetails = $getRoleDetails->where('name','like', "%".$request->search."%");
        }
        $orderColumn = 'id';
        $orderType = 'DESC';
        if($request->has('column')){
            $orderColumn = $request->column;
        }
        if($request->has('column')){
            $orderType = $request->type;
        }
        $getRoleDetails = $getRoleDetails->orderBy($orderColumn,$orderType)->paginate(10);
        return PaymentGatewaySettingResource::collection($getRoleDetails); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getPaymentGatewayDetails = $this->getPaymentGatewatDetails($id, 0);
        if(count($$getPaymentGatewayDetails) > 0){
            return $this->successResponse(PaymentGatewaySettingResource::collection($$getPaymentGatewayDetails), 'Payment gateway setting details fetch successfully', 200);
        }else{
            return $this->errorResponse('Payment gateway setting not found', 404);
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
                'api_key' => 'required',
                'secret_key' => 'required',
                'image' => 'required|max:2048|mimes:jpg,png,jpeg'
            ],
            [
                'name.required' => 'Please enter name',
                'api_key.required' => 'Please enter api key',
                'secret_key.required' => 'Please enter secret key',
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
        $paymentGatwaySettingName = ucfirst(strtolower(str_replace(' ', '', $request->name)));

        $createSetting = new PaymentGatewaySetting();
        $createSetting->name = $paymentGatwaySettingName;
        $createSetting->api_key = $request->api_key;
        $createSetting->secret_key = $request->secret_key;
        
        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();
        if (!empty($getAdminDetails) && !empty($getAdminDetails->id)) {
            $createSetting->created_by = $getAdminDetails->id;
            $createSetting->created_ip = CommonHelper::getUserIp();
        }
        $createSetting->save();
        $lastId = $createSetting->id;
        // Update filename & path
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image, 'paymentgateway/' . $lastId.'/');
            if (!empty($data)) {
                $updateImageData = PaymentGatewaySetting::find($lastId);
                $updateImageData->file_name = $data['filename'];
                $updateImageData->path = $data['path'];
                $updateImageData->update();
            }
        }
        $getEntityDetails = $this->getPaymentGatewatDetails($lastId, 0);
        $getUserDetails = PaymentGatewaySettingResource::collection($getEntityDetails);
        return $this->successResponse($getUserDetails, 'Payment gateway setting created successfully', 201);
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
        $checkUser = $this->getPaymentGatewatDetails($id,1);
        if(empty($checkUser)){
            return $this->errorResponse('Campaign category not found', 400);
        }

        // Validation section
        
        $rules['name'] = 'required';
        $rules['api_key'] = 'required';
        $rules['secret_key'] = 'required';
        $messages['name.required'] = 'Please enter name';
        $messages['api_key.required'] = 'Please enter api key';
        $messages['secret_key.required'] = 'Please enter secret key';
        
        if ($request->has('status')) {
            $rules['status'] = 'required|numeric|lte:1';
            $messages['status.required'] = 'Please enter status';
            $messages['status.numeric'] = 'Status value must be numeric';
            $messages['status.lte'] = 'Status should be 0 or 1';
        }

        if ($request->hasFile('image')) {
            $rules['image'] = 'required|max:2048|mimes:jpg,png,jpeg';
            $messages['image.required'] = 'Please select image';
            $messages['image.max'] = 'Please select below 2 MB images';
            $messages['image.mimes'] = 'Please select only jpg, png, jpeg files';
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
        $updateCampaignCat->api_key = $request->api_key;
        $updateCampaignCat->secret_key = $request->secret_key;
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

        $getUserDetails = $this->getPaymentGatewatDetails($id,0);
        $getUserDetails = PaymentGatewaySettingResource::collection($getUserDetails);
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
        $checkUser = $this->getPaymentGatewatDetails($id,1);
        if(empty($checkUser)){
            return $this->errorResponse('Payment gateway setting not found', 400);
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
            $deleteEntity = PaymentGatewaySetting::find($id)->delete();
            if ($deleteEntity) {
                return $this->successResponse([], 'Payment gateway setting deleted successfully', 200);
            }
        }
    }

    public function getPaymentGatewatDetails($id,$type){
        $getPaymentGatewaySettingData = PaymentGatewaySetting::where('id',$id);
        if($type == 1){
            $getPaymentGatewaySettingData = $getPaymentGatewaySettingData->first();
        }else{
            $getPaymentGatewaySettingData = $getPaymentGatewaySettingData->get();
        }
        return $getPaymentGatewaySettingData;
    }
}
