<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsumptionAssetItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumption_asset_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('consumption_asset_id');
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('asset_group_id')->nullable();
            $table->string('consumption_amount')->nullable();
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
        Schema::dropIfExists('consumption_asset_items');
    }
}
