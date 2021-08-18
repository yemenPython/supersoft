<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnedSaleReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returned_sale_receipts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number');

            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('user_id');

            $table->integer('salesable_id');
            $table->string('salesable_type');

            $table->integer('clientable_id');
            $table->string('clientable_type');

            $table->date('date');
            $table->time('time');
            $table->string('type');

            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('total_accepted', 15, 2)->default(0);
            $table->decimal('total_rejected', 15, 2)->default(0);

            $table->text('notes')->nullable();
            $table->string('library_path')->nullable();

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
        Schema::dropIfExists('returned_sale_receipts');
    }
}
