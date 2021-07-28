<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleQuotationSaleSupplyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_quotation_sale_supply_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('supply_order_id');
            $table->unsignedBigInteger('sale_quotation_id');
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
        Schema::dropIfExists('purchase_quotation_sale_supply_orders');
    }
}
