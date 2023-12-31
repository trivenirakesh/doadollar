<?php

namespace App\Models;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialPlatformSetting extends Model
{
    use HasFactory, SoftDeletes;
    const FOLDERNAME = "socialplatform/";

    public function getImageAttribute($val)
    {
        $linkPath = CommonHelper::getConfigValue('link_path');
        return ($val != null && CommonHelper::checkFileExists($val, self::FOLDERNAME) ? asset($linkPath . self::FOLDERNAME . $val)  : asset('public/dist/img/no-image.png'));
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = preg_replace('/\s+/', ' ', ucfirst(strtolower($value)));
    }

    public function entitymst()
    {
        return $this->hasOne(Entitymst::class, 'id', 'created_by');
    }

    public function getCreatedAtAttribute($val)
    {
        return  date('d-m-Y H:i A', strtotime($val));
    }
}
