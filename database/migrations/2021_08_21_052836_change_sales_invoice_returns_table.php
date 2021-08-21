<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSalesInvoiceReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        \Illuminate\Support\Facades\DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');


        Schema::table('sales_invoice_returns', function (Blueprint $table) {
            $table->renameColumn('invoice_number', 'number');

            $table->dropColumn(['customer_id', 'sales_invoice_id', 'number_of_items', 'points_discount', 'points_rule_id']);

            $table->decimal('additional_payments', 15, 2);
            $table->string('library_path')->nullable();
            $table->string('status')->default('pending');
            $table->string('type')->nullable()->change();
            $table->string('invoice_type')->nullable();

            $table->integer('invoiceable_id');
            $table->integer('invoiceable_type');

            $table->integer('clientable_id');
            $table->integer('clientable_type');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_invoice_returns', function (Blueprint $table) {
            //
        });
    }
}
