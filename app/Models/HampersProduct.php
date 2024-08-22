<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Helper;

class HampersProduct extends Model
{
    use HasFactory;
    use Sluggable;

    protected $casts = ['id' => 'string'];
    protected $fillable = [
        'id',
        'name',
        'slug',
        'category_id',
        'subcategory_id',
        'weight',
        'color',
        'price',
        'stock',
        'hpp',
        'margin',
        'b_layanan',
        'description',
        'created_by',
        'updated_by',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function categories()
    {
        return $this->belongsTo(Categories::class,'category_id','id');
    }

    public function subCategories()
    {
        return $this->belongsTo(SubCategories::class,'subcategory_id','id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class,'color','id');
    }

    public function colorAtr()
    {
        return $this->belongsTo(Color::class,'color','id');
    }

    public function images()
    {
        return $this->hasMany(HampersProductImages::class, 'product_id', 'id');
    }

    public function fPrice()
    {
        return Helper::to_rupiah($this->price);
    }
}
