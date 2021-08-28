<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAnnualConsumtionRateToAssetExpenseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_expense_items', function (Blueprint $table) {
            $table->double('annual_consumtion_rate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_expense_items', function (Blueprint $table) {
            $table->dropColumn('annual_consumtion_rate');
        });
    }
}
