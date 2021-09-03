<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesInvoiceReturnSupplyTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_invoice_return_supply_terms', function (Blueprint $table) {
            $table->unsignedBigInteger('sales_return_id');
            $table->unsignedBigInteger('supply_term_id');
            $table->foreign('sales_return_id')->references('id')->on('sales_invoice_returns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_invoice_return_supply_terms');
    }
}
