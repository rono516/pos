<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Setting extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'app_name','currency_symbol', 'app_description', 'warning_quantity','logo', 'phone', 'email'
    ];

    public function getAuditDisplayName()
    {
        return "Updated POS Settings";
    }
}
