<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankDataLibrariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_data_libraries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bank_data_id');
            $table->string('file_name');
            $table->string('name');
            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();
            $table->string('extension');
            $table->timestamps();
            $table->foreign('bank_data_id')->references('id')->on('bank_data')->onDelete('CASCADE');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_data_libraries');
    }
}
