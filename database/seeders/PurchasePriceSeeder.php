<?php

namespace Database\Seeders;

use App\Models\PurchasePrice;
use App\Models\SalesPrice;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class PurchasePriceSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //Initial Seed if no data
        if ( PurchasePrice::count() < 30 ) {
            PurchasePrice::factory()
                ->count(30)
                ->sequence(fn($sequence) => ['created_at'=>'2022-08-01', 'value' => 1200, 'product_id' => $sequence->index + 1])
                ->create();
        }

        if ( Transaction::count()>=30 && Transaction::count()<200){
            SalesPrice::factory()
                ->count(288)
                ->sequence(
                    fn($sequence) => [
                        'created_at' => now()->subHours((288 - $sequence->index) * 8),
                        'value'      => fake()->randomElement([1000, 1100, 1200, 1300, 1400, 1500, 1550, 1700, 1850]),
                        'product_id' => random_int(1, 30)
                    ]
                )
                ->create();
        }

    }

}
