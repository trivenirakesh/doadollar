<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\InquiryResource;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Cache;

class InquiryService
{
    use CommonTrait;
    const module = 'Inquiry';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expiry = CommonHelper::getConfigValue('cache_expiry');
        $data =  (Cache::remember('inquiry', $expiry, function () {
            return InquiryResource::collection(Inquiry::latest('id')->get());
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
        // Save inquiry section
        $saveInquiry = new Inquiry();
        $saveInquiry->first_name = $request->first_name;
        $saveInquiry->last_name = $request->last_name;
        $saveInquiry->email = str_replace(' ', '',$request->email);
        $saveInquiry->mobile = $request->mobile;
        $saveInquiry->message = $request->message;
        $saveInquiry->save();

        $getInquiryDetails = new InquiryResource($saveInquiry);
        Cache::forget('inquiry');
        return $this->successResponseArr(self::module.__('messages.success.create'), $getInquiryDetails);
    }
}
