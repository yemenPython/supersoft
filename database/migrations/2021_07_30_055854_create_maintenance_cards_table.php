<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaintenanceCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number');
            $table->string('type');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('created_by');
            $table->tinyInteger('receive_status')->default(0)->comment('0=>not_received, 1=>received');;
            $table->string('status')->nullable();
            $table->date('receive_date');
            $table->time('receive_time');
            $table->tinyInteger('delivery_status')->default(0)->comment('0=>not_delivered, 1=>delivered');
            $table->date('delivery_date');
            $table->time('delivery_time');
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
        Schema::dropIfExists('maintenance_cards');
    }
}
