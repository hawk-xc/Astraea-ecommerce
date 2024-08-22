<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailShipingOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_order',
        'name',
        'destination',
        'weight',
        'service',
        'price',
        'days',
    ];

    public function districtData()
    {
        return $this->belongsTo(District::class, 'destination', 'id');
    }
}
