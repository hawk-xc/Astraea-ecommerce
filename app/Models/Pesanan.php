<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Helper;

class Pesanan extends Model
{
    use HasFactory;
    protected $casts = ['id' => 'string'];
    protected $fillable = [
        'id',
        'order_date',
        'costumer_id',
        'code_discount',
        'discount_amount',
        'status',
        'no_nota',
        'shipping',
        'shipping_status',
        'app_admin',
        'sub_total_price',
        'total_price',
        'address',
        'payment_link',
        'created_by',
        'updated_by',
    ];

    public function customerData()
    {
        return $this->belongsTo(Customer::class, 'costumer_id', 'id');
    }

    public function shippingData()
    {
        return $this->belongsTo(DetailShipingOrder::class, 'id', 'id_order');
    }

    public function paymentData()
    {
        return $this->belongsTo(PaymentDetail::class, 'no_nota', 'external_id');
    }

    public function fSTPrice()
    {
        return Helper::to_rupiah($this->sub_total_price);
    }

    public function fShipping()
    {
        return Helper::to_rupiah($this->shipping);
    }

    public function fTPrice()
    {
        return Helper::to_rupiah($this->total_price);
    }

    public function fAdmin()
    {
        return Helper::to_rupiah($this->app_admin);
    }

}
