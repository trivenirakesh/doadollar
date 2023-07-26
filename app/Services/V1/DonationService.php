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
}