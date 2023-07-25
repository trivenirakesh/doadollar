<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTemplate extends Model
{
    use HasFactory,SoftDeletes;

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = preg_replace('/\s+/', ' ', ucfirst(strtolower($value)));    
    }

    public function setSubjectAttribute($value)
    {
        $this->attributes['subject'] = preg_replace('/\s+/', ' ', ucfirst(strtolower($value)));    
    }
}
