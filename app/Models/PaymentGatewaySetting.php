<?php

namespace App\Models;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentGatewaySetting extends Model
{
    use HasFactory,SoftDeletes;
    const FOLDERNAME = "paymentgateway/";

    public function getImageAttribute($val)
    {
        $linkPath = CommonHelper::getConfigValue('link_path');
        return $val == null ? asset('public/dist/img/no-image.png') : asset($linkPath.self::FOLDERNAME . $val);
    }
}
