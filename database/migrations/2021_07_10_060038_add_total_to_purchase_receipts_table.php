<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalToPurchaseReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_receipts', function (Blueprint $table) {
            $table->decimal('total')->default(0);
            $table->decimal('total_accepted')->default(0);
            $table->decimal('total_rejected')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_receipts', function (Blueprint $table) {
            $table->dropColumn('total_rejected');
            $table->dropColumn('total_accepted');
            $table->dropColumn('total');
        });
    }
}
