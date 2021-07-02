<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseAssetItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_asset_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchase_asset_id');
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('asset_group_id')->nullable();
            $table->string('purchase_cost')->nullable();
            $table->string('past_consumtion')->nullable();
            $table->string('annual_consumtion_rate')->nullable();
            $table->string('asset_age')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_asset_items');
    }
}
