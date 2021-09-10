<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommercialRegistrationNoToSecurityApprovalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('security_approval', function (Blueprint $table) {
            $table->string('commercial_registration_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('security_approval', function (Blueprint $table) {
            $table->dropColumn('commercial_registration_no');
        });
    }
}
