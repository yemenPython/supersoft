<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLockerTransferLibrariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locker_transfer_libraries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('locker_transfer_id');
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
        Schema::dropIfExists('locker_transfer_libraries');
    }
}
