<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupplyOrderToSupplyTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supply_terms', function (Blueprint $table) {
            $table->tinyInteger('supply_order')->default(0);
            $table->tinyInteger('purchase_invoice')->default(0);
            $table->tinyInteger('purchase_return')->default(0);
            $table->tinyInteger('sale_quotation')->default(0);
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
            $table->dropColumn('supply_order');
            $table->dropColumn('purchase_invoice');
            $table->dropColumn('purchase_return');
            $table->dropColumn('sale_quotation');
        });
    }
}
