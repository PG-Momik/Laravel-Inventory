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
            Transaction::factory()->count(4480)->sequence(
                fn($sequence) => [
                    'created_at' => now()->subHours((4480 - $sequence->index) * 2)
                ]
            )->create();
    }
}
