<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\DonationRequest;
use App\Services\V1\DonationService;
use Illuminate\Http\Request;

class DonationsController extends Controller
{
    protected $donationService;
    
    public function __construct(DonationService $donationService)
    {
        $this->donationService = $donationService;
    }

    public function index(Request $request){
        
        $getDonationList = $this->donationService->getDonationsList($request);
        if (!$getDonationList['status']) {
            return response()->json($getDonationList, 401);
        }
        return response()->json($getDonationList, 200);
    }

    public function store(DonationRequest $request){
        $createDonation = $this->donationService->store($request);
        if (!$createDonation['status']) {
            return response()->json($createDonation, 401);
        }
        return response()->json($createDonation, 200);
    }
}
