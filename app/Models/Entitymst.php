<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entitymst extends Authenticatable
{
    const ENTITYADMIN = 0;
    const ENTITYMANAGER = 1;
    const ENTITYUSER = 2;
    protected $table = 'entitymst';
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'mobile', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id')->where('status', 1);
    }

    public function scopeNotAdmin($query)
    {
        return $query->where('entity_type', '!=', self::ENTITYADMIN);
    }
    public function scopeRoleUser($query)
    {
        return $query->where('entity_type', self::ENTITYUSER);
    }
}
