<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $casts = ['id' => 'string'];
    protected $fillable = [
        'id',
        'image_banner',
        'title',
        'discount_amount',
        'code_discount',
        'description_discount',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'category_discount',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function dHampers()
    {
        return $this->hasMany(DiscountHampers::class, 'discount_id', 'id');
    }

    public function dProducts()
    {
        return $this->hasMany(DiscountProduct::class, 'discount_id', 'id');
    }

    public function dCustomer()
    {
        return $this->hasMany(DiscountCostumer::class, 'discount_id', 'id');
    }
}
