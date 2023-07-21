<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CampaignCategoryCreateUpdateRequest;
use App\Services\V1\CampaignCategoryService;

class CampaignCategoryController extends Controller
{

    private $campaignCategoryService;

    public function __construct(CampaignCategoryService $campaignCategoryService)
    {
        $this->campaignCategoryService = $campaignCategoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaignCategories =  $this->campaignCategoryService->index() ?? [];
        if (!$campaignCategories['status']) {
            return response()->json($campaignCategories, 401);
        }
        return response()->json($campaignCategories, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CampaignCategoryCreateUpdateRequest $request)
    {
        $campaignCategory  = $this->campaignCategoryService->store($request);
        if (!$campaignCategory['status']) {
            return response()->json($campaignCategory, 401);
        }
        return response()->json($campaignCategory, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $campaignCategory = $this->campaignCategoryService->show($id);
        if (!$campaignCategory['status']) {
            return response()->json($campaignCategory, 401);
        }
        return response()->json($campaignCategory, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CampaignCategoryCreateUpdateRequest $request, $id)
    {
        $campaignCategory = $this->campaignCategoryService->update($request, $id);
        if (!$campaignCategory['status']) {
            return response()->json($campaignCategory, 401);
        }
        return response()->json($campaignCategory, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campaignCategory = $this->campaignCategoryService->destroy($id);
        if (!$campaignCategory['status']) {
            return response()->json($campaignCategory, 401);
        }
        return response()->json($campaignCategory, 200);
    }
}
