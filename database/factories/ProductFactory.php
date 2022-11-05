<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [

            'registered_by'=>User::all()->random(),
            'quantity'=>100,
            'discount'=>5,
            'name'=>fake()->firstName(),
            'description'=>fake()->text(200),
        ];
    }

}

//Product::factory()->count(10)->state(new Sequence(fn($sequence)=>['category_id'=>Category::all()->random()]))


