<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOperationTypeToPurchaseAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_assets', function (Blueprint $table) {
            $table->enum('operation_type', ['purchase', 'opening_balance'])->nullable();
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
            $table->dropColumn('operation_type');
        });
    }
}
