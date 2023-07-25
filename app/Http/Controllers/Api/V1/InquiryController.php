<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\InquiryCreateUpdateRequest;
use App\Services\V1\InquiryService;

class InquiryController extends Controller
{
    private $inquiryService;

    public function __construct(InquiryService $inquiryService)
    {
        $this->inquiryService = $inquiryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $inquiryList =  $this->inquiryService->index() ?? [];
        if (!$inquiryList['status']) {
            return response()->json($inquiryList, 401);
        }
        return response()->json($inquiryList, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InquiryCreateUpdateRequest $request)
    {
        $inquiryCreate  = $this->inquiryService->store($request);
        if (!$inquiryCreate['status']) {
            return response()->json($inquiryCreate, 401);
        }
        return response()->json($inquiryCreate, 200);
    }
    
}
