<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\V1\CampaignCreateUpdateRequest;
use App\Models\Campaign;
use App\Http\Resources\V1\CampaignResource;
use App\Services\V1\CampaignService;

class CampaignController extends Controller
{
    

    private $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    public function index(Request $request){

        $campaign =  $this->campaignService->index($request) ?? [];
        if (!$campaign['status']) {
            return response()->json($campaign, 401);
        }
        return response()->json($campaign, 200);
    }

    public function show($id){
        $campaignCategory = $this->campaignService->show($id);
        if (!$campaignCategory['status']) {
            return response()->json($campaignCategory, 401);
        }
        return response()->json($campaignCategory, 200);
    }

    public function store(CampaignCreateUpdateRequest $request)
    {
        $campaignCreate  = $this->campaignService->store($request);
        if (!$campaignCreate['status']) {
            return response()->json($campaignCreate, 401);
        }
        return response()->json($campaignCreate, 200);
    }

    public function update(CampaignCreateUpdateRequest $request, $id){
        $campaignUpdate = $this->campaignService->update($request, $id);
        if (!$campaignUpdate['status']) {
            return response()->json($campaignUpdate, 401);
        }
        return response()->json($campaignUpdate, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $campaignDelete = $this->campaignService->destroy($id);
        if (!$campaignDelete['status']) {
            return response()->json($campaignDelete, 401);
        }
        return response()->json($campaignDelete, 200);
    }

}
