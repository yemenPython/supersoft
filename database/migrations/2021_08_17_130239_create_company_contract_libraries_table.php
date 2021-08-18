<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyContractLibrariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_contract_libraries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_contract_id');
            $table->string('name');
            $table->string('file_name');
            $table->string('extension');
            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();
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
        Schema::dropIfExists('company_contract_libraries');
    }
}
