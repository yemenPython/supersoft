<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTotalInPurchaseInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        \Illuminate\Support\Facades\DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');


        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->decimal('discount', 15,2)->change();
            $table->decimal('total', 15,2)->change();
            $table->decimal('total_after_discount', 15,2)->change();
            $table->decimal('paid', 15,2)->change();
            $table->decimal('remaining', 15,2)->change();
            $table->decimal('tax', 15,2)->change();
            $table->decimal('subtotal', 15,2)->change();
            $table->decimal('additional_payments', 15,2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            //
        });
    }
}
