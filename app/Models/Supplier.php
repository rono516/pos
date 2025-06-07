<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Supplier extends Model implements AuditableContract
{
    use Auditable;
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'avatar',
    ];

   public function getAuditDisplayName()
    {
        return "Supplier: {$this->first_name} {$this->last_name}";
    }

    public function purchaseOrders(){
        return $this->hasMany(PurchaseOrder::class);
    }
}
