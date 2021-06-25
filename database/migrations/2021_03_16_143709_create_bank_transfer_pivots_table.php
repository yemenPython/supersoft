<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankTransferPivotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_transfer_pivots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('bank_transfer_id');
            $table->bigInteger('bank_receive_permission_id');
            $table->bigInteger('bank_exchange_permission_id');
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
        Schema::dropIfExists('bank_transfer_pivots');
    }
}
