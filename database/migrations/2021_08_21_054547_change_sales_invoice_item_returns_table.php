<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSalesInvoiceItemReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('sales_invoice_item_returns', function (Blueprint $table) {

            $table->dropColumn(['sales_invoice_item_id', 'purchase_invoice_id', 'available_qty', 'last_selling_price']);

            $table->renameColumn('return_qty','quantity');
            $table->renameColumn('selling_price','price');

            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedBigInteger('part_price_id')->nullable();
            $table->unsignedBigInteger('part_price_segment_id')->nullable();
            $table->unsignedBigInteger('spare_part_id')->nullable();

            $table->string('active')->default(0);
            $table->string('max_quantity')->default(0);

            $table->integer('itemable_id')->nullable();
            $table->integer('itemable_type')->nullable();
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
            //
        });
    }
}
