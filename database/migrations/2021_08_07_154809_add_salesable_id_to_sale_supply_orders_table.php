<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSalesableIdToSaleSupplyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_supply_orders', function (Blueprint $table) {
            $table->dropColumn('customer_id');
            $table->bigInteger('salesable_id')->nullable();
            $table->string('salesable_type')->nullable();
            $table->string('type_for')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_supply_orders', function (Blueprint $table) {
            //
        });
    }
}
