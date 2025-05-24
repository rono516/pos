<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Customer extends Model implements AuditableContract
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'avatar',
        'user_id',
    ];

    public function getAvatarUrl()
    {
        return Storage::url($this->avatar);
    }
    public function getAuditDisplayName()
    {
        return "Customer: {$this->first_name}";
    }

}
