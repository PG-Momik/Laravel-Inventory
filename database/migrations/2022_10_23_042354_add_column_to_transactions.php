<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('cost_id')->nullable()->after('id');
            $table->foreign('cost_id')->references('id')->on('costs')->nullOnDelete();

            $table->unsignedBigInteger('price_id')->nullable()->after('id');
            $table->foreign('price_id')->references('id')->on('prices')->nullOnDelete();

            $table->integer('discount')->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
