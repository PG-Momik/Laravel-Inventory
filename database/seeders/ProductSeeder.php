<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
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
        for ($i=0; $i<12; $i++){
            $product = new Product;
            $product->name = $faker->firstName;
            $product->price = $faker->numberBetween(500, 3000);
            $product->quantity = $faker->numberBetween(10, 30);
            $product->discount = $faker->randomElement(['5', '10', '15']);
            $product->description = $faker->paragraph(2);
            $product->registered_by = $faker->numberBetween(1, 5);
            $product->category_id = $faker->numberBetween(1, 10);
            $product->save();
        }

    }
}
