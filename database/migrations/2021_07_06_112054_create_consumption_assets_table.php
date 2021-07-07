<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsumptionAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumption_assets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number');
            $table->unsignedBigInteger('branch_id');
            $table->date('date_from');
            $table->date('date_to');
            $table->date('date');
            $table->time('time');
            $table->text('note')->nullable();
            $table->decimal('total_purchase_cost',10,2)->default(0);
            $table->decimal('total_past_consumtion',10,2)->default(0);
            $table->decimal('total_replacement',10,2)->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('branch_id')->references('id')->on('branches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consumption_assets');
    }
}
