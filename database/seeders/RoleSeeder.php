<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ( Role::count() < 2 ) {
            $admin       = new Role;
            $admin->name = "Admin";
            $admin->save();

            $user       = new Role;
            $user->name = "User";
            $user->save();
        }

    }

}
