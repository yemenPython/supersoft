<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSparePartIdToPurchaseReturnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_return_items', function (Blueprint $table) {
            $table->unsignedBigInteger('spare_part_id')->nullable();
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
            $table->dropColumn('spare_part_id');
        });
    }
}
