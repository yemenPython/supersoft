<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleQuotationSupplyTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_quotation_supply_terms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_quotation_id');
            $table->unsignedBigInteger('supply_term_id');
            $table->foreign('sale_quotation_id')->references('id')->on('sale_quotations')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_quotation_supply_terms');
    }
}
