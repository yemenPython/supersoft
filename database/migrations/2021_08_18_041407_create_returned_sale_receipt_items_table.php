<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnedSaleReceiptItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returned_sale_receipt_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_receipt_id');
            $table->integer('itemable_id');
            $table->string('itemable_type');
            $table->unsignedBigInteger('part_id');
            $table->unsignedBigInteger('part_price_id');
            $table->unsignedBigInteger('spare_part_id')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedBigInteger('part_price_segment_id')->nullable();
            $table->integer('total_quantity')->default(0);
            $table->integer('refused_quantity')->default(0);
            $table->integer('accepted_quantity')->default(0);
            $table->decimal('defect_percent')->default(0);
            $table->decimal('price', 15, 2)->default(0);
            $table->timestamps();
            $table->foreign('sale_receipt_id')->references('id')->on('returned_sale_receipts')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('returned_sale_receipt_items');
    }
}
