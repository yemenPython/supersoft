<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyContractTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_contract', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('branch_id');
            $table->integer('user_id');
            $table->date('contract_date');
            $table->date('register_date');
            $table->string('commercial_feature');
            $table->string('company_purpose');
            $table->string('share_capital');
            $table->integer('partnership_duration');
            $table->date('start_at');
            $table->date('end_at');
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
        Schema::dropIfExists('company_contract');
    }
}
