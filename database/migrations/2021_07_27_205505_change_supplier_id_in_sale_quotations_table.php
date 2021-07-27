<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSupplierIdInSaleQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_quotations', function (Blueprint $table) {
            $table->renameColumn('supplier_id', 'customer_id');
            $table->renameColumn('supplier_discount', 'customer_discount');
            $table->renameColumn('supplier_discount_type', 'customer_discount_type');
            $table->renameColumn('supplier_discount_active', 'customer_discount_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_quotations', function (Blueprint $table) {
            //
        });
    }
}
