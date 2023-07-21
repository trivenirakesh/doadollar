<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\PaymentGatewaySetting;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\PaymentGatewaySettingResource;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Cache;

class PaymentGatewaySettingService
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
        $data =  (Cache::remember('paymentGatewaySetting', $expiry, function () {
            return PaymentGatewaySettingResource::collection(PaymentGatewaySetting::latest('id')->get());
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
        $createSetting = new PaymentGatewaySetting();
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
                $updateImageData = PaymentGatewaySetting::find($lastId);
                $updateImageData->file_name = $data['filename'];
                $updateImageData->path = $data['path'];
                $updateImageData->update();
            }
        }
        $getPaymentGateway =  PaymentGatewaySetting::where('id', $lastId)->first();
        $getPaymentGatewayDetails = new PaymentGatewaySettingResource($getPaymentGateway);
        return $this->successResponseArr(self::module . __('messages.success.create'), $getPaymentGatewayDetails);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getPaymentGatewayData = PaymentGatewaySetting::where('id', $id)->first();
        if ($getPaymentGatewayData == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $getPaymentGatewayData = new PaymentGatewaySetting($getPaymentGatewayData);
        return $this->successResponseArr(self::module . __('messages.success.details'), $getPaymentGatewayData);
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
        $paymentGatewaySetting = PaymentGatewaySetting::where('id', $id)->first();;
        if ($paymentGatewaySetting == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        // remove blank spaces from string 
        $paymentGatewaySettingName = ucfirst(strtolower(str_replace(' ', '',$request->name)));
        $paymentGatewaySetting->name = $paymentGatewaySettingName;
        $paymentGatewaySetting->api_key = $request->api_key;
        $paymentGatewaySetting->secret_key = $request->secret_key;
        $paymentGatewaySetting->status = $request->status;
        // get logged in user details 
        $paymentGatewaySetting->updated_by = auth()->user()->id;
        $paymentGatewaySetting->updated_ip = CommonHelper::getUserIp();
        // Update filename & path
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image, 'paymentgateway/' . $id.'/');
            if (!empty($data)) {
                $paymentGatewaySetting->file_name = $data['filename'];
                $paymentGatewaySetting->path = $data['path'];
            }
        }
        $paymentGatewaySetting->update();
        $getSettingDetails = new PaymentGatewaySettingResource($paymentGatewaySetting);
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
        $paymentGatewaySetting =  PaymentGatewaySetting::where('id', $id)->first();
        if ($paymentGatewaySetting == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        // Delete entity
        $paymentGatewaySetting->deleted_by = auth()->user()->id;
        $paymentGatewaySetting->deleted_ip = CommonHelper::getUserIp();
        $paymentGatewaySetting->update();
        $deleteCampaignCategory = $paymentGatewaySetting->delete();
        if ($deleteCampaignCategory) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}