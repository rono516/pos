<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class OrderItem extends Model implements AuditableContract
{
    use  Auditable;
    protected $fillable = [
        'price',
        'quantity',
        'product_id',
        'order_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getAuditDisplayName()
    {
        return "OrderItem: {$this->product->name}";
    }
}
