<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetReplacementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_replacements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('number');
            $table->date('date');
            $table->time('time');
            $table->decimal('total_before_replacement', 15, 2)->default(0.0);
            $table->decimal('total_after_replacement', 15, 2)->default(0.0);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('branch_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('branch_id')->references('id')->on('branches');
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
        Schema::dropIfExists('asset_replacements');
    }
}
