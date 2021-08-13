<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaintenanceCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance_centers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_en')->nullable();
            $table->string('name_ar')->nullable();
            $table->unsignedInteger('branch_id')->nullable();
            $table->string('status')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->unsignedInteger('area_id')->nullable();
            $table->string('phone_1')->nullable();
            $table->string('phone_2')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('fax')->nullable();
            $table->string('commercial_number')->nullable();
            $table->string('tax_card')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('identity_number')->nullable();
            $table->string('commercial_record_area')->nullable();
            $table->string('tax_file_number')->nullable();
            $table->string('company_code')->nullable();
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
        Schema::dropIfExists('maintenance_centers');
    }
}
