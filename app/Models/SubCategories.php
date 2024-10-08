<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategories extends Model
{
    use HasFactory;
    protected $casts = ['id' => 'string'];
    protected $fillable = [
        'id',
        'id_category',
        'name',
        'description',
        'created_by',
        'updated_by'
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'id_category', 'id');
    }

    public function products()
    {
        return $this->hasMany(Products::class, 'id_subcategory', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'id', 'subcategory_id');
    }
}
