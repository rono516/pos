<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesapalPayment extends Model
{
    protected $fillable = [
        'order_id', 'order_tracking_id', 'merchant_reference', 'redirect_url', 'paid_at', 'status'
    ];
}
