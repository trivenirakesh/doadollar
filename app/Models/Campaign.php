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
    use HasFactory, SoftDeletes;

    const FOLDERNAME = "campaigns/";

    const CAMPAIGNSTATUSARR = ['Pending', 'OnGoing', 'Completed', 'Cancelled',  'Rejected', 'Approved'];
    const CAMPAIGNSTATUSCLASSARR = ['text-warning', 'text-info', 'text-success', 'text-danger',  'text-danger', 'text-primary'];

    protected $fillable = [
        'name',
        'status',
        'description',
        'campaign_category_id',
        'start_datetime',
        'end_datetime',
        'donation_target',
        'unique_code',
        'files',
        'video',
        'image',
        'created_by',
        'created_ip',
        'created_at',
        'updated_at',
        'updated_by',
        'updated_ip',
        'upload_type',
    ];

    public function getCampaignsList($request)
    {
        $perPageData = CommonHelper::getConfigValue('per_page');
        $campaignId = 0;
        $entityId = 0;
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
        if (!empty($request->entity_id)) {
            $entityId = $request->entity_id;
        }
        $campaignListQuery = "CALL sp_get_campaigns_list('$perPageData','$offset','$searchValue','$orderColumn','$orderBy','$entityId','$campaignId')";
        $getCampaignListDetails = DB::select($campaignListQuery);
        $returnArr['count'] = count($getCampaignListDetails);
        $returnArr['data'] = $getCampaignListDetails;
        return $returnArr;
    }

    public function getCampaignsListCount()
    {
        $totalRecordsData = DB::select("CALL sp_get_campaigns_list('0','0','','','','0','0')");
        return count($totalRecordsData);
    }

    public function uploads(): HasMany
    {
        return $this->hasMany(CampaignUploads::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = preg_replace('/\s+/', ' ', ucfirst(strtolower($value)));
    }

    public function getImageAttribute($val)
    {
        $linkPath = CommonHelper::getConfigValue('link_path');
        return $val == null ? asset('public/dist/img/no-image.png') : asset($linkPath . self::FOLDERNAME . $val);
    }
    public function getQrImageAttribute($val)
    {
        $linkPath = CommonHelper::getConfigValue('link_path');
        return $val == null ? asset('public/dist/img/qrcode_dummy.png') : asset($linkPath . self::FOLDERNAME . $val);
    }

    public function getCreatedAtAttribute($val)
    {
        return  date('d-m-Y H:i A', strtotime($val));
    }

    public function entitymst()
    {
        return $this->hasOne(Entitymst::class, 'id', 'created_by');
    }
    public function donation()
    {
        return $this->hasMany(Donation::class, 'campaign_id', 'id');
    }

    public function category()
    {
        return $this->hasOne(CampaignCategory::class, 'id', 'campaign_category_id');
    }
}
