<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create();
        for ($i=0; $i<5; $i++){
            $user = new User;
            $user->name = $faker->name;
            $user->email = $faker->email;
            $user->role_id = 1;
            $user->password = Hash::make($faker->password);
            $user->save();
        }

    }
}
