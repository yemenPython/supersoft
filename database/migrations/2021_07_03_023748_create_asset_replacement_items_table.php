<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetReplacementItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_replacement_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('asset_replacement_id');
            $table->decimal('value_replacement', 15, 2)->default(0.0);
            $table->decimal('value_after_replacement', 15, 2)->default(0.0);
            $table->bigInteger('age');
            $table->foreign('asset_id')->references('id')->on('assets_tb');
            $table->foreign('asset_replacement_id')
                ->references('id')
                ->on('asset_replacements')
                ->onDelete('CASCADE');
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
        Schema::dropIfExists('asset_replacement_items');
    }
}
