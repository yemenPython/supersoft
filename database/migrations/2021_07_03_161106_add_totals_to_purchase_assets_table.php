<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalsToPurchaseAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_assets', function (Blueprint $table) {
            $table->decimal('total_purchase_cost',10,2)->default(0);
            $table->decimal('total_past_consumtion',10,2)->default(0);
            $table->decimal('net_total',10,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_assets', function (Blueprint $table) {
            $table->dropColumn('total_purchase_cost');
            $table->dropColumn('total_past_consumtion');
            $table->dropColumn('net_total');
        });
    }
}
