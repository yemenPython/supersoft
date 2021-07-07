<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditPurchaseReturnItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('purchase_return_items', function (Blueprint $table) {
            $table->dropColumn(['last_purchase_price', 'total_before_discount']);
            $table->renameColumn('purchase_qty','quantity');
            $table->renameColumn('purchase_price','price');
            $table->decimal('sub_total')->default(0);
            $table->decimal('tax')->default(0);
            $table->decimal('total')->default(0);
            $table->tinyInteger('active');
            $table->integer('max_quantity')->default(0);

            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedBigInteger('part_price_id')->nullable();
            $table->unsignedBigInteger('part_price_segment_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_return_items', function (Blueprint $table) {
            //
        });
    }
}
