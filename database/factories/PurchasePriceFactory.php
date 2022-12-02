<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\PurchasePrice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<PurchasePrice>
 */
class PurchasePriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product_id' => Product::all()->random(),
            'value'      => rand(1100, 1200),
        ];
    }
}
