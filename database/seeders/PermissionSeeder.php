<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create([
            'name' => 'Superadmin',
            'guard_name' => 'web'
        ]);

        $id = [];

        $permissions = ['Dashboard Access', 'Master Access', 'User Access', 'User Create', 'User Edit', 'User Delete', 'Company Access', 'Company Create', 'Company Edit', 'company-delete', 'device access', 'device create', 'device edit', 'device delete', 'server access', 'role access', 'role create', 'role edit', 'role delete'];

        foreach ($permissions as $permission) {
            $id[] = Permission::create([
                'name' => Str::slug($permission),
                'guard_name' => 'web'
            ])->id;
        }

        $role->syncPermissions($id);
    }
}
