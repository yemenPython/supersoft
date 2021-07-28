<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleSupplyOrderSupplyTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_supply_order_supply_terms', function (Blueprint $table) {
            $table->unsignedBigInteger('supply_order_id');
            $table->unsignedBigInteger('supply_term_id');
            $table->foreign('supply_order_id')->references('id')->on('sale_supply_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_supply_order_supply_terms');
    }
}
