<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Receipt extends Model implements AuditableContract
{
    use Auditable;
    protected $fillable = [
        'order_id',
        'serving_user'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
