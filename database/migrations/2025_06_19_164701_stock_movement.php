<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StockMovement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('location_id');
            $table->enum('type', ['inflow', 'outflow']); // inflow = delivery, outflow = release
            $table->integer('quantity');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
