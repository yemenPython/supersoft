<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSalesInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('sales_invoice_items', function (Blueprint $table) {

            $table->dropForeign(['purchase_invoice_id']);

            $table->dropColumn(['purchase_invoice_id', 'sold_qty', 'last_selling_price', 'deleted_at']);

            $table->renameColumn('available_qty','quantity');
            $table->renameColumn('selling_price','price');

            $table->unsignedBigInteger('part_price_id');
            $table->unsignedBigInteger('part_price_segment_id')->nullable();

            $table->decimal('tax', 15, 2)->nullable();
            $table->unsignedBigInteger('spare_part_id')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_invoice_items', function (Blueprint $table) {
            //
        });
    }
}
