<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Helper;
use SebastianBergmann\CodeCoverage\Report\Html\Colors;

class Products extends Model
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

    public $incrementing = false;

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
        return $this->belongsTo(Categories::class, 'category_id', 'id');
    }

    public function subCategories()
    {
        return $this->belongsTo(SubCategories::class, 'subcategory_id', 'id');
    }

    public function subcategory()
    {
        return $this->hasMany(SubCategories::class, 'product_id', 'id');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_colors', 'product_id', 'color_id');
    }

    // public function color()
    // {
    //     return $this->belongsToMany(Color::class,'color_product','product_id','color_id');
    // }

    public function colorAtr()
    {
        return $this->belongsTo(Color::class, 'color', 'id');
    }

    public function colorFo()
    {
        return $this->hasMany(Color::class, 'id', 'color');
    }

    public function images()
    {
        return $this->hasMany(ProductImages::class, 'product_id', 'id');
    }

    public function fPrice()
    {
        return Helper::to_rupiah($this->price);
    }

    public function sku()
    {
        return $this->belongsTo(Sku::class, 'sku_id', 'id');
    }
}
