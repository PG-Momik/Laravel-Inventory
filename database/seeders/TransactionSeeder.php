<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TransactionSeeder extends Seeder
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
        for ($i=0;$i<20;$i++){
            $transaction = new Transaction;
            $transaction->type = $faker->randomElement(['Add', 'Remove']);
            $transaction->user_id = $faker->numberBetween(1,5);
            $transaction->product_id = $faker->numberBetween(1,12);
            $transaction->quantity = $faker->numberBetween(1,5);
            $transaction->save();

        }

    }
}
