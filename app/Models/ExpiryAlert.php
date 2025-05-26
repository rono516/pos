<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpiryAlert extends Model
{
    protected $fillable = ['product_id', 'days_to_expiry', 'alerted_at'];
}
