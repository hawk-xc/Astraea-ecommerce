<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $casts = ['id' => 'string'];

    protected $fillable = [
        'id',
        'role_id',
        'name',
        'email',
        'username',
        'password',
        'address',
        'email_verified_at',
        'created_by',
        'updated_by'
    ];

    protected $hidden = [
        'password'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class,'role_id','id');
    }
}
