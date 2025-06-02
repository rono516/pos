<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = ['supplier_id', 'fulfilled', 'total_amount'];
    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
