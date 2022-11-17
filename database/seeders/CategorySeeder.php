<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //Initial Seed if no data.
        Category::factory()->count(10)->has(Product::factory()->count(3), 'products')->create();
    }
}
