<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\ResetPasswordNotification;

class Entitymst extends Authenticatable
{
    const ENTITYADMIN = 0;
    const ENTITYMANAGER = 1;
    const ENTITYUSER = 2;
    const ENTITYTYPES = [
        2 => 'User',
        1 => 'Manager',
    ];

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

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = preg_replace('/\s+/', ' ', ucfirst(strtolower($value)));    
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = preg_replace('/\s+/', ' ', ucfirst(strtolower($value)));    
    }
    
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
