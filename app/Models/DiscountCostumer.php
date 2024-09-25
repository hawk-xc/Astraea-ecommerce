<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCostumer extends Model
{
    use HasFactory;

    protected $casts = ['id' => 'string'];
    protected $fillable = [
        'id',
        'costumer_id',
        'code_discount',
        'discount_id',
        'is_used',
        'created_by',
        'updated_by',
    ];

    public function discountData()
    {
        return $this->belongsTo(Discount::class, 'discount_id', 'id');
    }
}
