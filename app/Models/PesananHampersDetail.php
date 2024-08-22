<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananHampersDetail extends Model
{
    use HasFactory;
    protected $casts = ['id' => 'string'];
    protected $fillable = [
        'id',
        'order_id',
        'hampers_id',
        'quantity',
        'price',
        'sub_total_price',
        'created_by',
        'updated_by',
    ];

    public function hampersData()
    {
        return $this->belongsTo(HampersProduct::class, 'hampers_id', 'id');
    }

    public function orderData()
    {
        return $this->belongsTo(PesananHampers::class, 'order_id', 'id');
    }
}
