<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEgyptianFederationOfConstructionAndBuildingContractorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('egyptian_federation_of_construction_and_building_contractors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('branch_id');
            $table->string('membership_no');
            $table->date('date_of_register');
            $table->string('payment_receipt',255);
            $table->date('end_date');
            $table->string('company_type');
            $table->string('library_path',255);
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
        Schema::dropIfExists('egyptian_federation_of_construction_and_building_contractors');
    }
}
