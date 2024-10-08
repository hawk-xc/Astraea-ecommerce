<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PesananDetail extends Model
{
    use HasFactory;
    protected $casts = ['id' => 'string'];
    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'quantity',
        'color',
        'price',
        'sub_total_price',
        'created_by',
        'updated_by',
    ];

    public function productData()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }

    public function orderData()
    {
        return $this->belongsTo(Pesanan::class, 'order_id', 'id');
    }

    public function color(): HasOne
    {
        return $this->hasOne(Color::class, 'id', 'color');


    }
}
