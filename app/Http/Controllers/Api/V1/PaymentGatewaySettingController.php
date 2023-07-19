<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PaymentGatewaySetting;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\PaymentGatewaySettingResource;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Cache;

class PaymentGatewaySettingController extends Controller
{
    use CommonTrait;
    const module = 'Payment gateway setting';
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expiry = CommonHelper::getConfigValue('cache_expiry');
        $getPaymentGatewayList =  PaymentGatewaySettingResource::collection(Cache::remember('paymentGatewaySetting',$expiry,function(){
            return PaymentGatewaySetting::latest('id')->get();
        }));
        return $this->successResponse($getPaymentGatewayList, self::module.__('messages.success.list'), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getPaymentGatewayDetails = $this->getPaymentGatewayDetails($id, 0);
        return $this->successResponse(PaymentGatewaySettingResource::collection($getPaymentGatewayDetails), self::module.__('messages.success.details'), 200);
    }

    /**
     * Store a newly created payment gateway setting in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        // Validation section
        $validateSetting = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:200',
                'api_key' => 'required|alpha_num',
                'secret_key' => 'required|alpha_num',
                'image' => 'required|max:2048|mimes:jpg,png,jpeg'
            ],
            [
                'name.required' => __('messages.validation.name'),
                'name.max' => __('messages.validation.max'),
                'api_key.required' => __('messages.validation.api_key'),
                'api_key.alpha_num' => 'Api key'.__('messages.validation.alpha_num'),
                'secret_key.required' => __('messages.validation.secret_key'),
                'secret_key.alpha_num' => 'Secret key'.__('messages.validation.alpha_num'),
                'image.required' => __('messages.validation.image'),
                'image.max' => __('messages.validation.image-max'),
                'image.mimes' => __('messages.validation.image-mimes'),
            ]
        );

        if ($validateSetting->fails()) {
            return $this->errorResponse($validateSetting->errors(), 401);
        }

        // save details 

        // remove blank spaces from string 
        $paymentGatewaySettingName = ucfirst(strtolower(str_replace(' ', '', $request->name)));

        $createSetting = new PaymentGatewaySetting();
        $createSetting->name = $paymentGatewaySettingName;
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
        $getPaymentSettingDetails = $this->getPaymentGatewayDetails($lastId, 0);
        $getSettingDetails = PaymentGatewaySettingResource::collection($getPaymentSettingDetails);
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
        
        // check setting exist or not 
        $checkSetting = $this->getPaymentGatewayDetails($id,1);

        // Validation section
        
        $rules['name'] = 'required|max:200';
        $rules['api_key'] = 'required|alpha_num';
        $rules['secret_key'] = 'required|alpha_num';
        $messages['name.required'] = __('messages.validation.name');
        $messages['name.max'] = __('messages.validation.max');
        $messages['api_key.required'] = __('messages.validation.api_key');
        $messages['api_key.alpha_num'] = 'Api key'.__('messages.validation.alpha_num');
        $messages['secret_key.required'] = __('messages.validation.secret_key');
        $messages['secret_key.alpha_num'] = 'Secret key'.__('messages.validation.alpha_num');
        
        if ($request->has('status')) {
            $rules['status'] = 'required|numeric|lte:1';
            $messages['status.required'] = __('messages.validation.status');
            $messages['status.numeric'] = 'Status'.__('messages.validation.must_numeric');
            $messages['status.lte'] = __('messages.validation.status_lte');
        }

        if ($request->hasFile('image')) {
            $rules['image'] = 'required|max:2048|mimes:jpg,png,jpeg';
            $messages['image.required'] = __('messages.validation.image');
            $messages['image.max'] = __('messages.validation.image-max');
            $messages['image.mimes'] = __('messages.validation.image-mimes');
        }

        $validateSetting = Validator::make($request->all(), $rules, $messages);

        if ($validateSetting->fails()) {
            return $this->errorResponse($validateSetting->errors(), 400);
        }

        // remove blank spaces from string 
        $paymentGatewaySettingName = ucfirst(strtolower(str_replace(' ', '',$request->name)));

        // update details 
        $updatePaymentSetting = $checkSetting;
        $updatePaymentSetting->name = $paymentGatewaySettingName;
        $updatePaymentSetting->api_key = $request->api_key;
        $updatePaymentSetting->secret_key = $request->secret_key;
        $updatePaymentSetting->status = $request->status;
        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();
        if (!empty($getAdminDetails) && !empty($getAdminDetails->id)) {
            $updatePaymentSetting->updated_by = $getAdminDetails->id;
            $updatePaymentSetting->updated_ip = CommonHelper::getUserIp();
        }
        // Update filename & path
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image, 'paymentgateway/' . $id.'/');
            if (!empty($data)) {
                $updatePaymentSetting->file_name = $data['filename'];
                $updatePaymentSetting->path = $data['path'];
            }
        }
        $updatePaymentSetting->update();

        $getPaymentSettingDetails = $this->getPaymentGatewayDetails($id,0);
        $getSettingDetails = PaymentGatewaySettingResource::collection($getPaymentSettingDetails);
        return $this->successResponse($getSettingDetails,self::module.__('messages.success.update') , 200);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){

        // check setting exist or not 
        $checkSetting = $this->getPaymentGatewayDetails($id,1);

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();

        // Delete payment gateway setting
        $checkSettingData = $checkSetting;
        if (!empty($checkSettingData)) {
            $checkSettingData->deleted_by = $getAdminDetails->id;
            $checkSettingData->deleted_ip = CommonHelper::getUserIp();
            $checkSettingData->update();
            $deleteSetting = PaymentGatewaySetting::find($id)->delete();
            if ($deleteSetting) {
                return $this->successResponse([], self::module.__('messages.success.delete'), 200);
            }
        }
    }

    public function getPaymentGatewayDetails($id,$type){
        $getPaymentGatewaySettingData = PaymentGatewaySetting::where('id',$id);
        if($type == 1){
            $getPaymentGatewaySettingData = $getPaymentGatewaySettingData->first();
            if(!empty($getPaymentGatewaySettingData)){
                return $getPaymentGatewaySettingData;
            }else{
                throw new \ErrorException(self::module.__('messages.validation.not_found'));
            }
        }else{
            $getPaymentGatewaySettingData = $getPaymentGatewaySettingData->get();
            if(count($getPaymentGatewaySettingData) > 0){
                return $getPaymentGatewaySettingData;
            }else{
                throw new \ErrorException(self::module.__('messages.validation.not_found'));
            }
        }
    }
}
