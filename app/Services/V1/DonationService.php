<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Traits\CommonTrait;
use App\Helpers\CommonHelper;
use App\Http\Resources\V1\DonationResource;
use App\Models\Donation;

class DonationService
{
    use CommonTrait;
    const module = 'Donation';

    public function getDonationsList(Request $request){
        $donation = new Donation();
        $donationListData = $donation->getDonation($request);
        $getDonationList =  DonationResource::collection($donationListData['data']);
        return $this->successResponseArr($getDonationList);
    }

    public function store(Request $request){
        
        // save donation 
        $createDonation = new Donation();
        $createDonation->campaign_id = $request->campaign_id;
        $createDonation->payment_type_id = $request->payment_type_id;
        if($request->entity_id){
            $createDonation->entity_id = $request->entity_id;
        }
        $createDonation->donation_amount = $request->donation_amount;
        if($request->tip){
            $createDonation->tip = $request->tip;
        }
        if($request->longitude){
            $createDonation->longitude = $request->longitude;
        }
        if($request->latitude){
            $createDonation->latitude = $request->latitude;
        }
        $createDonation->created_by = auth()->user()->id;
        $createDonation->created_ip = CommonHelper::getUserIp();
        $createDonation->save();
        $getDonationDetails = new DonationResource($createDonation);
        return $this->successResponseArr(self::module . __('messages.success.create'),$getDonationDetails);
    }
}