<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    use HasFactory;


    protected $fillable = ['color_id', 'count'];

    public function products()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }
}
