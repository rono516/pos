<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        $permissions = [
            'delete products',
            'modify invoices',
            'view invoices',
            'create sales',
            'create orders',
            'view orders',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $roles = [
            'admin'        => Permission::all(),
            'storemanager' => ['delete products', 'modify invoices', 'view orders', 'create orders'],
            'pharmacist'   => ['view invoices', 'create sales', 'view orders', 'create orders'],
            'cashier'      => ['create orders'],
        ];

        foreach ($roles as $role => $perms) {
            $roleObj = Role::firstOrCreate(['name' => $role]);

            if (is_iterable($perms)) {
                $roleObj->syncPermissions($perms);
            } else {
                $roleObj->syncPermissions(Permission::all());
            }
        }

        $adminUser = User::where("email", "admin@gmail.com")->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }
    }
}
