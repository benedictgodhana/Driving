<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'apply for opportunities']);
        Permission::create(['name' => 'view own applications']);
        Permission::create(['name' => 'manage opportunities']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'view all applications']);
        Permission::create(['name' => 'approve applications']);
        Permission::create(['name' => 'reject applications']);
        Permission::create(['name' => 'send notifications']);

        // Create roles and assign created permissions

        // Student role
        $studentRole = Role::create(['name' => 'student']);
        $studentRole->givePermissionTo(['apply for opportunities', 'view own applications']);

        // Admin role
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'manage opportunities',
            'manage users',
            'view all applications',
            'approve applications',
            'reject applications',
            'send notifications'
        ]);

        // Super-admin role
        $superAdminRole = Role::create(['name' => 'super-admin']);
        $superAdminRole->givePermissionTo(Permission::all()); // Grant all permissions
    }
}
