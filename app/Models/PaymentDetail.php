<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PaymentDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount',
        'status',
        'paid_at',
        'description',
        'external_id',
        'payer_email',
        'payment_method',
        'payment_channel',
    ];

    public function paidAt()
    {
        return Carbon::parse($this->attributes['paid_at'])->format('d F Y H:i:s');
    }
}
