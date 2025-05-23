<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    OwenIt\Auditing\AuditingServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    Barryvdh\DomPDF\ServiceProvider::class,
];
