<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaxToSalesInvoiceItemReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_invoice_item_returns', function (Blueprint $table) {
            $table->decimal('tax', 15,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_invoice_item_returns', function (Blueprint $table) {
            $table->dropColumn('tax');
        });
    }
}
