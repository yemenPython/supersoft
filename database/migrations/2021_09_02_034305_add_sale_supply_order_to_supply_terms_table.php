<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSaleSupplyOrderToSupplyTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supply_terms', function (Blueprint $table) {
            $table->tinyInteger('sales_invoice')->default(0);
            $table->tinyInteger('sales_invoice_return')->default(0);
            $table->tinyInteger('sale_supply_order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supply_terms', function (Blueprint $table) {
            $table->dropColumn('sale_supply_order');
            $table->dropColumn('sales_invoice_return')->default(0);
            $table->dropColumn('sales_invoice')->default(0);

        });
    }
}
