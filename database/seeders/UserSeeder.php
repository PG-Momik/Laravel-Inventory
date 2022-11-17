<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker        = Faker::create();
        $usr           = new User();
        $usr->name     = 'admin';
        $usr->email    = 'admin@admin.com';
        $usr->password = Hash::make('admin123');
        $usr->save();

        $roleAdmin = Role::findByName('Admin');
        $usr->assignRole($roleAdmin);

        for ($i = 0; $i < 5; $i++) {
            $user           = new User();
            $user->name     = $faker->name;
            $user->email    = $faker->email;
            $user->password = Hash::make("user$i$i$i");
            $user->save();

            $roleUser = Role::findByName('User');
            $user->assignRole($roleUser);
        }
    }
}
