<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'whatsapp',
        'email',
        'id_province',
        'id_distric',
        'id_sub_distric',
        'address',
        'maps',
        'created_by',
        'updated_by'
    ];

    public function districtData()
    {
        return $this->belongsTo(District::class, 'id_distric', 'id');
    }
}
