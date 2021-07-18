<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupplyOrderToTaxesFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taxes_fees', function (Blueprint $table) {
            $table->tinyInteger('supply_order')->default(0);
            $table->tinyInteger('purchase_return')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taxes_fees', function (Blueprint $table) {
            $table->dropColumn('purchase_return');
            $table->dropColumn('supply_order');
        });
    }
}
