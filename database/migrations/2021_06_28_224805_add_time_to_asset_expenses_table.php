<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeToAssetExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_expenses', function (Blueprint $table) {
            $table->time('time')->nullable();
            if (Schema::hasColumn('asset_expenses', 'dateTime')) {
                $table->dropColumn('dateTime');
            }
            $table->date('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_expenses', function (Blueprint $table) {
            $table->dropColumn('time');
            $table->dropColumn('date');
        });
    }
}
