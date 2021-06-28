<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assets_tb', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();

        });
        Schema::table('asset_expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assets_tb', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
        Schema::table('asset_expenses', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
