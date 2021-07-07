<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditPurchaseReturn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        \Illuminate\Support\Facades\DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('purchase_returns', function (Blueprint $table) {
            $table->dropColumn(['supplier_id', 'number_of_items', 'paid', 'remaining']);
            $table->unsignedBigInteger('purchase_invoice_id')->nullable()->change();

            $table->decimal('sub_total')->default(0);
            $table->string('status')->default('pending')->comment('pending, processing, finished');
            $table->decimal('additional_payments')->default(0);
            $table->string('invoice_type')->default('normal')->comment('from supply order, normal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_returns', function (Blueprint $table) {
            //
        });
    }
}
