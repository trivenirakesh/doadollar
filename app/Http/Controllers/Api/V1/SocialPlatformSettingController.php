<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\SocialPlatformCreateUpdateRequest;
use App\Services\V1\SocialPlatformSettingService;

class SocialPlatformSettingController extends Controller
{

    private $socialPlatformSettingService;

    public function __construct(SocialPlatformSettingService $socialPlatformSettingService)
    {
        $this->socialPlatformSettingService = $socialPlatformSettingService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $socialPlatformSettings =  $this->socialPlatformSettingService->index() ?? [];
        if (!$socialPlatformSettings['status']) {
            return response()->json($socialPlatformSettings, 401);
        }
        return response()->json($socialPlatformSettings, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SocialPlatformCreateUpdateRequest $request)
    {
        $socialPlatformSetting  = $this->socialPlatformSettingService->store($request);
        if (!$socialPlatformSetting['status']) {
            return response()->json($socialPlatformSetting, 401);
        }
        return response()->json($socialPlatformSetting, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $socialPlatformSetting = $this->socialPlatformSettingService->show($id);
        if (!$socialPlatformSetting['status']) {
            return response()->json($socialPlatformSetting, 401);
        }
        return response()->json($socialPlatformSetting, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SocialPlatformCreateUpdateRequest $request, $id)
    {
        $socialPlatformSetting = $this->socialPlatformSettingService->update($request, $id);
        if (!$socialPlatformSetting['status']) {
            return response()->json($socialPlatformSetting, 401);
        }
        return response()->json($socialPlatformSetting, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $socialPlatformSetting = $this->socialPlatformSettingService->destroy($id);
        if (!$socialPlatformSetting['status']) {
            return response()->json($socialPlatformSetting, 401);
        }
        return response()->json($socialPlatformSetting, 200);
    }
}
