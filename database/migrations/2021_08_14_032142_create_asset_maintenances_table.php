<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetMaintenancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_maintenances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->unsignedInteger('asset_id');
            $table->unsignedInteger('maintenance_detection_id')->nullable();
            $table->unsignedInteger('maintenance_detection_type_id')->nullable();
            $table->integer('period');
            $table->integer('number_of_km_h');
            $table->enum('maintenance_type', ['km', 'hour']);
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('asset_maintenances');
    }
}
