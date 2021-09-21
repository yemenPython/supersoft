<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToOpeningBalanceAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('opening_balance_accounts', function (Blueprint $table) {
            $table->enum('status', ['progress', 'accepted', 'rejected'])->default('progress');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('opening_balance_accounts', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
