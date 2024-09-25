<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;


class Customer extends Model implements AuthenticatableContract
{
    use HasFactory;
    use Authenticatable;
    
    protected $casts = ['id' => 'string'];
    protected $fillable = [
        'id',
        'name',
        'username',
        'email',
        'password',
        'phone',
        'wa',
        'district_id',
        'address',
        'is_active',
        'email_verified_at',
        'tgl_lahir',
        'jenis_kelamin',
        'verification_token',
        'reset_password_token',
        'created_by',
        'updated_by',
    ];

    public function districtData()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }
}
