<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\PurchasePrice;
use App\Models\SalesPrice;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $product         = Product::all()->random();
        $purchasePriceId = PurchasePrice::find($product->id)->latest('created_at')->first();
        $salesPriceId    = SalesPrice::find($product->id)->latest('created_at')->first();
        $discount        = 0;
        $type            = fake()->randomElement(['Purchase', 'Sale']);

        if ( Transaction::count() < 200 ) {

            if ( $type == "Purchase" ) {
                $purchasePriceId = PurchasePrice::factory()->create()->id;
            }

            if ( $type == "Sale" ) {
                $salesPriceId = SalesPrice::factory()->create()->id;
                $discount     = 5;
            }

        }

        return [
            'user_id'           => User::all()->random()->id,
            'product_id'        => $product->id ?? '',
            'purchase_price_id' => $purchasePriceId->id ?? $purchasePriceId,
            'sales_price_id'    => $salesPriceId->id ?? $salesPriceId,
            'type'              => $type,
            'discount'          => $discount,
            'quantity'          => fake()->numberBetween(1, 20),
        ];
    }

}
