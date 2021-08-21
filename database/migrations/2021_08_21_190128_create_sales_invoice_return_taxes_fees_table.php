<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesInvoiceReturnTaxesFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_invoice_return_taxes_fees', function (Blueprint $table) {
            $table->unsignedBigInteger('sales_invoice_return_id');
            $table->unsignedBigInteger('tax_id');
            $table->foreign('sales_invoice_return_id')->references('id')->on('sales_invoice_returns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_invoice_return_taxes_fees');
    }
}
