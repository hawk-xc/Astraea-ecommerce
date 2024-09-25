<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    use Sluggable;
    
    protected $casts = ['id' => 'string'];
    protected $fillable = [
        'id',
        'title',
        'tanggal_acara',
        'slug',
        'description',
        'cover_image',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function creatorBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

}
