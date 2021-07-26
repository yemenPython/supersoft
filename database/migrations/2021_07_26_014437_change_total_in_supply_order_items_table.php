<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTotalInSupplyOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supply_order_items', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->change();
            $table->decimal('sub_total', 15, 2)->change();
            $table->decimal('discount', 15, 2)->change();
            $table->decimal('tax', 15, 2)->change();
            $table->decimal('total_after_discount', 15, 2)->change();
            $table->decimal('total', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supply_order_items', function (Blueprint $table) {
            //
        });
    }
}
