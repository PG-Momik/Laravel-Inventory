<?php

namespace Database\Seeders;

use App\Models\SalesPrice;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class SalesPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //Initial Seed if no data
        if (SalesPrice::count() < 30) {
            SalesPrice::factory()
                ->count(30)
                ->sequence(
                    fn($sequence) => [
                        'created_at' => '2022-08-01',
                        'value'      => 2000,
                        'product_id' => $sequence->index + 1
                    ]
                )
                ->create();
        }
        if (Transaction::count() >= 30 && Transaction::count() < 200) {
            SalesPrice::factory()
                ->count(288)
                ->sequence(
                    fn($sequence) => [
                        'created_at' => now()->subHours((288 - $sequence->index) * 8),
                        'value'      => fake()->randomElement([2000, 2200, 2300, 2400, 2500, 2600, 2800, 2100, 2550]),
                        'product_id' => random_int(1, 30)
                    ]
                )
                ->create();
        }
    }
}
