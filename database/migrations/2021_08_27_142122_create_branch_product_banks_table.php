<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchProductBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_product_banks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bank_data_id');
            $table->unsignedBigInteger('branch_product_id');
            $table->foreign('bank_data_id')->references('id')->on('bank_data');
            $table->foreign('branch_product_id')->references('id')->on('branch_products');
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
        Schema::dropIfExists('branch_product_banks');
    }
}
