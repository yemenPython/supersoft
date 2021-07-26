<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTotalInPurchaseInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('purchase_invoice_items', function (Blueprint $table) {
            $table->decimal('discount', 15,2)->change();
            $table->decimal('tax', 15,2)->change();
            $table->decimal('subtotal', 15,2)->change();
            $table->decimal('total_after_discount', 15,2)->change();
            $table->decimal('last_purchase_price', 15,2)->change();
            $table->decimal('purchase_price', 15,2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_invoice_items', function (Blueprint $table) {
            //
        });
    }
}
