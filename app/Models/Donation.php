<?php

namespace App\Models;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Donation extends Model
{
    use HasFactory;

    public function getCreatedAtAttribute($val)
    {
        return  date('d-m-Y H:i A', strtotime($val));
    }

    public function getDonation($request)
    {
        $perPageData = CommonHelper::getConfigValue('per_page');
        $entityId = 0;
        $donationId = 0;
        $campaignId = 0;
        $searchValue = '';
        $orderColumn = 'don.id';
        $orderBy = 'DESC';
        $offset = 0;
        if (!empty($request->entity_id)) {
            $entityId = $request->entity_id;
        }
        if (!empty($request->donation_id)) {
            $donationId = $request->donation_id;
        }
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
        $campaignListQuery = "CALL sp_get_donations_list('$perPageData','$offset','$searchValue','$orderColumn','$orderBy','$entityId','$campaignId','$donationId')";
        $getCampaignListDetails = DB::select($campaignListQuery);
        $returnArr['count'] = count($getCampaignListDetails);
        $returnArr['data'] = $getCampaignListDetails;
        return $returnArr;
    }
}
