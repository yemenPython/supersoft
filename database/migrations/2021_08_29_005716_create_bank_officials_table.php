<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankOfficialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_officials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('bank_data_id');
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('phone3')->nullable();
            $table->string('email')->nullable();
            $table->string('job')->nullable();
            $table->date('date_from');
            $table->date('date_to');
            $table->boolean('status')->default(1);
            $table->foreign('bank_data_id')->references('id')->on('bank_data');
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
        Schema::dropIfExists('bank_officials');
    }
}
