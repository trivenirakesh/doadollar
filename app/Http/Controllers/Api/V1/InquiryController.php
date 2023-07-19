<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Inquiry;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\InquiryResource;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Cache;

class InquiryController extends Controller
{
    use CommonTrait;
    const module = 'Inquiry';

    public function index(){
        $expiry = CommonHelper::getConfigValue('cache_expiry');
        $getRoleList = InquiryResource::collection(Cache::remember('inquiry',$expiry,function(){
            return Inquiry::latest('id')->get();
        })); 
        return $this->successResponse($getRoleList, self::module.__('messages.success.list'), 200);
    }

    public function store(Request $request){
        
        // Validation section
        $validateUser = Validator::make($request->all(),
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'mobile' => 'required|numeric|digits:10',
            ],
            [
                'first_name.required' => __('messages.validation.first_name'),
                'last_name.required' => __('messages.validation.last_name'),
                'email.required' => __('messages.validation.email'),
                'email.email' => __('messages.validation.email_email'),
                'mobile.required' => __('messages.validation.mobile'),
                'mobile.numeric' => 'Mobile'.__('messages.validation.must_numeric'),
                'mobile.digits' => __('messages.validation.mobile_digits'),
            ]
        );

        if ($validateUser->fails()) {
            return $this->errorResponse($validateUser->errors(), 401);
        }

        
        // Save entity section
        $inquiryFirstName = preg_replace('/\s+/', ' ', ucfirst(strtolower($request->first_name)));
        $inquiryLastName = preg_replace('/\s+/', ' ', ucfirst(strtolower($request->last_name)));
        $saveInquiry = new Inquiry();
        $saveInquiry->first_name = $inquiryFirstName;
        $saveInquiry->last_name = $inquiryLastName;
        $saveInquiry->email = str_replace(' ', '',$request->email);
        $saveInquiry->mobile = $request->mobile;
        $saveInquiry->message = $request->message;
        $saveInquiry->created_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
        $saveInquiry->save();

        $lastId = $saveInquiry->id;
        $getInquiryData = $this->getInquiryDetails($lastId,0);
        $getInquiryDetails = InquiryResource::collection($getInquiryData);
        Cache::forget('inquiry');
        return $this->successResponse($getInquiryDetails, self::module.__('messages.success.create'), 201);
    }

    public function getInquiryDetails($id,$type){
        $getInquiryData = Inquiry::where('id',$id);
        if($type == 1){
            $getInquiryData = $getInquiryData->first();
            if(!empty($getInquiryData)){
                return $getInquiryData;
            }else{
                throw new \ErrorException(self::module.__('messages.validation.not_found'));
            }
        }else{
            $getInquiryData = $getInquiryData->get();
            if(count($getInquiryData) > 0){
                return $getInquiryData;
            }else{
                throw new \ErrorException(self::module.__('messages.validation.not_found'));
            }
        }
    }
}
