<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommercialRegisterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commercial_register', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('branch_id');
            $table->string('commercial_registry_office');
            $table->string('national_number');
            $table->string('deposit_number');
            $table->date('deposit_date');
            $table->date('valid_until');
            $table->string('commercial_feature');
            $table->string('company_type');
            $table->string('purpose');
            $table->string('no_of_years');
            $table->date('start_at');
            $table->date('end_at');
            $table->string('library_path',255)->nullable();
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
        Schema::dropIfExists('commercial_register');
    }
}
