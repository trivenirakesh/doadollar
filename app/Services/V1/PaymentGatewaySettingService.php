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
        $createSetting->name = $request->name;
        $createSetting->api_key = $request->api_key;
        $createSetting->secret_key = $request->secret_key;

        // get logged in user details 
        $createSetting->created_by = auth()->user()->id;
        $createSetting->created_ip = CommonHelper::getUserIp();

        // upload file 
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image,PaymentGatewaySetting::FOLDERNAME,0);
            if (!empty($data)) {
                $createSetting->image = $data['filename'];
            }
        }
        $createSetting->save();
        $getPaymentGatewayDetails = new PaymentGatewaySettingResource($createSetting);
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
        $getPaymentGatewayData = new PaymentGatewaySettingResource($getPaymentGatewayData);
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
        $paymentGatewaySetting = PaymentGatewaySetting::where('id', $id)->first();
        if ($paymentGatewaySetting == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $paymentGatewaySetting->name = $request->name;
        $paymentGatewaySetting->api_key = $request->api_key;
        $paymentGatewaySetting->secret_key = $request->secret_key;
        $paymentGatewaySetting->status = $request->status;
        // get logged in user details 
        $paymentGatewaySetting->updated_by = auth()->user()->id;
        $paymentGatewaySetting->updated_ip = CommonHelper::getUserIp();
        // Update file
        if ($request->hasFile('image')) {
            // Unlink old image from storage 
            $oldImage = $paymentGatewaySetting->getAttributes()['image'] ?? null;
            if ($oldImage != null){
                CommonHelper::removeUploadedImages($oldImage,PaymentGatewaySetting::FOLDERNAME);
            }
            // Unlink old image from storage 

            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image,PaymentGatewaySetting::FOLDERNAME,0);
            if (!empty($data)) {
                $paymentGatewaySetting->image = $data['filename'];
            }
        }
        $paymentGatewaySetting->update();
        $getSettingDetails = new PaymentGatewaySettingResource($paymentGatewaySetting);
        return $this->successResponseArr($getSettingDetails, self::module . __('messages.success.update'));
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
        // Delete payment gateway setting
        $paymentGatewaySetting->deleted_by = auth()->user()->id;
        $paymentGatewaySetting->deleted_ip = CommonHelper::getUserIp();
        $paymentGatewaySetting->update();
        $deleteCampaignCategory = $paymentGatewaySetting->delete();
        if ($deleteCampaignCategory) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}