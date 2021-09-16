<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLockerOpeningBalanceLibrariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locker_opening_balance_libraries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('locker_opening_balance_id');
            $table->string('file_name');
            $table->string('name');
            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();
            $table->string('extension');
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
        Schema::dropIfExists('locker_opening_balance_libraries');
    }
}
