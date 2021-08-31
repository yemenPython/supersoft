<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsFromBankCommisiionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_commissioners', function (Blueprint $table) {
            $table->dropColumn('branch_id');
            $table->dropColumn('name_ar');
            $table->dropColumn('name_en');
            $table->dropColumn('phone1');
            $table->dropColumn('phone2');
            $table->dropColumn('phone3');
            $table->dropColumn('email');
            $table->dropColumn('job');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_commissioners', function (Blueprint $table) {
        });
    }
}
