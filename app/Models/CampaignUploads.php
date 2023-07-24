<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\CommonHelper;

class CampaignUploads extends Model
{
    use HasFactory,SoftDeletes;

    const FOLDERNAME = "campaigns/uploads/";

    public function uploadType(){
        $activeStatus = CommonHelper::getConfigValue('status.active');
        return $this->belongsTo(UploadType::class,'upload_type_id')->where('status',$activeStatus);
    }
}
