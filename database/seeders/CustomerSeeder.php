<?php

namespace Database\Seeders;

use App\Models\Customer;
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
            $user = new Customer;
            $user->name = $faker->name;
            $user->email = $faker->email;
            $user->role_id = $faker->numberBetween(1, 2);
            $user->password = Hash::make($faker->password);
            $user->save();
        }

    }
}
