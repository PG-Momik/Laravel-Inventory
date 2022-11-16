<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'add users']);
        Permission::create(['name' => 'add products']);
        Permission::create(['name' => 'add categories']);
        Permission::create(['name' => 'make transactions']);

        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'edit products']);
        Permission::create(['name' => 'edit categories']);
        Permission::create(['name' => 'edit roles']);


        Permission::create(['name' => 'trash users']);
        Permission::create(['name' => 'trash products']);
        Permission::create(['name' => 'trash categories']);
        Permission::create(['name' => 'trash roles']);


        Permission::create(['name' => 'restore users']);
        Permission::create(['name' => 'restore products']);
        Permission::create(['name' => 'restore categories']);
        Permission::create(['name' => 'restore roles']);


        Permission::create(['name' => 'delete users']);
        Permission::create(['name' => 'delete products']);
        Permission::create(['name' => 'delete categories']);
        Permission::create(['name' => 'delete roles']);

        $role = Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'User', 'guard_name' => 'web']);
        $role->givePermissionTo(
            [
                'add users',
                'add products',
                'add categories',
                'make transactions',
                'edit users',
                'edit products'
            ]
        );
    }
}
