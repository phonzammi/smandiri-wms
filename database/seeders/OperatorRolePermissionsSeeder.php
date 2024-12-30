<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class OperatorRolePermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        // TODO: add some permissions
        $permissions = [

        ];

        foreach ($permissions as $permission) {
            Permission::createOrFirst([
                'name' => $permission
            ]);
        }

        // create roles and assign created permissions
        // this can be done as separate statements
        $role = Role::create(['name' => 'operator']);

        // TODO: use the correct email
        $user = User::where("email", "operator@smandiri.com")->first();

        $user->assignRole($role);

        foreach ($permissions as $permission) {
            $role->givePermissionTo($permission);
        }
    }
}
