<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('short_name_ar')->nullable();
            $table->string('short_name_en')->nullable();
            $table->string('branch')->nullable();
            $table->string('code')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('address')->nullable();
            $table->string('long')->nullable();
            $table->string('lat')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('url')->nullable();
            $table->date('date')->nullable();
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('bank_data');
    }
}
