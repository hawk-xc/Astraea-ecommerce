<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sku extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'created_by',
        'updated_by'
    ];


    public function product()
    {
        return $this->hasOne(Products::class, 'id', 'sku_id');
    }
}
