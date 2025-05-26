<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Product extends Model implements AuditableContract
{
    use Auditable;
    protected $fillable = [
        'name',
        'description',
        'image',
        'barcode',
        'price',
        'quantity',
        'status',
        'batchno',
        'expiry',
        'totalprice',
        'shelf',
        'deleted',
    ];

    protected $casts = [
        'expiry' => 'datetime',  
        'deleted' => 'boolean',
    ];

    public function getImageUrl()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }
        return asset('images/img-placeholder.jpg');
    }

    public function getAuditDisplayName()
    {
        return "Product: {$this->name}";
    }

    public function expiryAlerts(){
        return $this->hasMany(ExpiryAlert::class);
    }
}
