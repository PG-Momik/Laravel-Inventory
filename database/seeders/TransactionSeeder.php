<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Initial Seed if no data
        if (Transaction::count() < 30) {
            Transaction::factory()->count(30)->sequence(
                fn($sequence) => [
                    'product_id'        => $sequence->index + 1,
                    'type'              => 'Purchase',
                    'purchase_price_id' => $sequence->index + 1,
                    'sales_price_id'    => $sequence->index + 1,
                    'quantity'          => 100,
                    'discount'          => 0,
                    'created_at'        => '2022-08-01'
                ]
            )->create();
        }

        if (Transaction::count() >= 30 && Transaction::count() < 200) {
            Transaction::factory()->count(288)->sequence(
                fn($sequence) => [
                    'created_at' => now()->subHours((288 - $sequence->index) * 8)
                ]
            )->create();
        }
    }
}
