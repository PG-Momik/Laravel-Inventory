<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = new Role;
        $role1->name = "Admin";
        $role1->save();
        $role2 = new Role;
        $role2->name = "User";
        $role2->save();

    }
}
