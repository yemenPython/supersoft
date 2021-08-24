<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLockerOpeningBalanceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locker_opening_balance_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('locker_id');
            $table->unsignedBigInteger('locker_opening_balance_id');
            $table->double('current_balance');
            $table->double('added_balance');
            $table->double('total');
            $table->foreign('locker_id')->references('id')->on('lockers');
            $table->foreign('locker_opening_balance_id')->references('id')->on('locker_opening_balances');
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
        Schema::dropIfExists('locker_opening_balance_items');
    }
}
