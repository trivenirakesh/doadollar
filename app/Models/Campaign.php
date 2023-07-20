<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory,SoftDeletes;

    public function getCampaignsList($request){
        $perPageData = CommonHelper::getConfigValue('per_page');
        $campaignId = 0;
        $searchValue = '';
        $orderColumn = 'cam.id';
        $orderBy = 'DESC';
        $offset = 0;
        if (!empty($request->campaign_id)) {
            $campaignId = $request->campaign_id;
        }
        if (!empty($request->search)) {
            $searchValue = $request->search;
        }
        if (!empty($request->order_column)) {
            $orderColumn = $request->order_column;
        }
        if (!empty($request->order_by)) {
            $orderBy = $request->order_by;
        }
        if (!empty($request->offset)) {
            $offset = $request->offset;
        }
        $campaignListQuery = "CALL sp_get_campaigns_list('$perPageData','$offset','$searchValue','$orderColumn','$orderBy','$campaignId')";
        $getCampaignListDetails = DB::select($campaignListQuery);
        $returnArr['count'] = count($getCampaignListDetails);
        $returnArr['data'] = $getCampaignListDetails;
        return $returnArr;
    }

    public function getCampaignsListCount(){
        $totalRecordsData = DB::select("CALL sp_get_campaigns_list('0','0','','','','0')");
        return count($totalRecordsData);
    }

    public function uploads(): HasMany
    {
        return $this->hasMany(CampaignUploads::class);
    }
    
}
