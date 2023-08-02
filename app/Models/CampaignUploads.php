<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\CommonHelper;

class CampaignUploads extends Model
{
    use HasFactory, SoftDeletes;

    const FOLDERNAME = "campaigns/uploads/";

    protected $fillable = [
        'campaign_id',
        'title',
        'description',
        'image',
        'link',
        'created_by',
        'created_ip',
        'created_at',
        'updated_at',
        'updated_by',
        'updated_ip',
        'upload_type',
    ];

    public function uploadType()
    {
        $activeStatus = CommonHelper::getConfigValue('status.active');
        return $this->belongsTo(UploadType::class, 'upload_type_id')->where('status', $activeStatus);
    }

    public function getCreatedAtAttribute($val)
    {
        return  date('d-m-Y H:i A', strtotime($val));
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = preg_replace('/\s+/', ' ', ucfirst(strtolower($value)));
    }

    public function getImageAttribute($val)
    {
        $linkPath = CommonHelper::getConfigValue('link_path');
        return $val == null ? asset('public/dist/img/no-image.png') : asset($linkPath . self::FOLDERNAME . $val);
    }
}
