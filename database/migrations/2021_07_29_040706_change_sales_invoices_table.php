<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSalesInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->renameColumn('invoice_number','number');

            $table->dropForeign('customer_id');

            $table->dropColumn(['customer_id', 'number_of_items', 'customer_discount_status', 'customer_discount',
                'customer_discount_type', 'points_discount', 'points_rule_id', 'deleted_at']);

            $table->decimal('additional_payments', 15, 2)->default(0);
            $table->string('status')->default('pending')->comment('pending, processing, finished');

            $table->bigInteger('salesable_id');
            $table->string('salesable_type');
            $table->string('type_for')->default('customer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            //
        });
    }
}
