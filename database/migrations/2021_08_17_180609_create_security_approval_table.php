<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecurityApprovalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('security_approval', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('branch_id');
            $table->integer('user_id');
            $table->string('register_no');
            $table->date('expiration_date');
            $table->string('commercial_feature')->nullable();
            $table->string('company_type')->nullable();
            $table->string('company_field')->nullable();
            $table->string('fax')->nullable();
            $table->string('library_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('security_approval');
    }
}
