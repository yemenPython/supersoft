<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleSupplyOrderTaxesFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_supply_order_taxes_fees', function (Blueprint $table) {
            $table->unsignedBigInteger('supply_order_id');
            $table->unsignedBigInteger('tax_id');
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
        Schema::dropIfExists('sale_supply_order_taxes_fees');
    }
}
