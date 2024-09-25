<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;
    protected $casts = ['id' => 'string'];
    protected $fillable = [
        'name',
        'description',
        'image',
        'created_by',
        'updated_by'
    ];
}
