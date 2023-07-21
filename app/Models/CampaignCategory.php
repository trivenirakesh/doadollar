<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];
    public function entitymst()
    {
        return $this->hasOne(Entitymst::class, 'id', 'created_by');
    }
}
