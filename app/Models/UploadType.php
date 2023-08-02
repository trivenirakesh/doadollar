<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UploadType extends Model
{
    use HasFactory, SoftDeletes;

    public function campaignUploads()
    {
        return $this->hasOne(CampaignUploads::class);
    }

    public function getCreatedAtAttribute($val)
    {
        return  date('d-m-Y H:i A', strtotime($val));
    }
}
