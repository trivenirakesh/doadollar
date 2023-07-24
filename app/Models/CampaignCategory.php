<?php

namespace App\Models;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignCategory extends Model
{
    use HasFactory, SoftDeletes;

    const FOLDERNAME = "campaigncategory/";

    public function getImageAttribute($val)
    {
        $linkPath = CommonHelper::getConfigValue('link_path');
        return $val == null ? asset('public/dist/img/no-image.png') : asset($linkPath.self::FOLDERNAME . $val);
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];
    public function entitymst()
    {
        return $this->hasOne(Entitymst::class, 'id', 'created_by');
    }
}
