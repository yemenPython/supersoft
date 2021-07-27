<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleSupplyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_supply_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number');
            $table->unsignedBigInteger('branch_id');
            $table->date('date');
            $table->time('time');
            $table->date('supply_date_from');
            $table->date('supply_date_to');
            $table->string('type');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('status')->default('pending')->comment('pending, processing, finished');
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->string('discount_type')->default('amount')->comment('amount, percent');
            $table->decimal('total_after_discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('additional_payments', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->string('library_path')->nullable();
            $table->text('description')->nullable();
            $table->decimal('customer_discount', 15, 2)->default(0);
            $table->string('customer_discount_type')->default('amount');
            $table->tinyInteger('customer_discount_active')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_supply_orders');
    }
}
