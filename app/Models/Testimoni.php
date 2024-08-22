<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    use HasFactory;

    protected $casts = ['id' => 'string'];
    protected $fillable = [
        'customer_id',
        'rating',
        'testimonial',
        'is_delete',
    ];

    public function customerData()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
