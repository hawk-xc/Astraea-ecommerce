<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountUser extends Model
{
    use HasFactory;

    protected $casts = ['id' => 'string'];
    protected $fillable = [
        'id',
        'user_id',
        'discount_id',
        'created_by',
        'updated_by',
    ];
}
