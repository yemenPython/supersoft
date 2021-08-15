<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaintenanceCenterIdToMaintenanceCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maintenance_cards', function (Blueprint $table) {
            $table->unsignedBigInteger('maintenance_center_id')->nullable();
            $table->foreign('maintenance_center_id')->references('id')->on('maintenance_centers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maintenance_cards', function (Blueprint $table) {
            $table->dropForeign('maintenance_center_id');
        });
    }
}
