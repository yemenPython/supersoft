<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTitleArToPurchaseInvoiceLibrariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_invoice_libraries', function (Blueprint $table) {
            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_invoice_libraries', function (Blueprint $table) {
            $table->dropColumn('title_en');
            $table->dropColumn('title_ar');
        });
    }
}
