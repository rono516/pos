<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Payment extends Model implements AuditableContract
{
    use  Auditable;
    protected $fillable = [
        'amount',
        'order_id',
        'user_id',
    ];

    public function getAuditDisplayName()
    {
        return "Payment Amount: {$this->amount}";
    }
}
