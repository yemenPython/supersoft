<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsumptionAssetItemExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumption_asset_item_expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('consumption_asset_item_id');
            $table->integer('asset_id');
            $table->integer('expense_id');
            $table->double('consumption_amount');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consumption_asset_item_expenses');
    }
}
