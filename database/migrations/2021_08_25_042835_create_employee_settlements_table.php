<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeSettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_settlements', function (Blueprint $table) {
            $table->unsignedBigInteger('settlement_id');
            $table->unsignedBigInteger('employee_id');
            $table->foreign('settlement_id')->references('id')->on('settlements')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employee_data')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_settlements');
    }
}
