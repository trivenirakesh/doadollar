<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class StaticPage extends Model
{
    use HasFactory;

    const PAGES = ['about_us' => "About-Us", 'terms_and_condition' => 'Terms & Condition', 'privacy_policy' => 'Privacy-Policy'];
    public function getCreatedAtAttribute($val)
    {
        return  date('d-m-Y H:i A', strtotime($val));
    }
}
