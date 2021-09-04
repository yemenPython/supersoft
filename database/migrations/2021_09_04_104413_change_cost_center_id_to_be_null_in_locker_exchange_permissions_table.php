<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCostCenterIdToBeNullInLockerExchangePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locker_exchange_permissions', function (Blueprint $table) {
           $table->dropColumn('cost_center_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locker_exchange_permissions', function (Blueprint $table) {
            $table->bigInteger('cost_center_id')->nullable();
        });
    }
}
