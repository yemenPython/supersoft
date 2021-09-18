<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToAssetsGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assets_groups', function (Blueprint $table) {
            $table->string('consumption_type')->default('manual');
            $table->integer('age_years')->nullable();
            $table->integer('age_months')->nullable();
            $table->integer('consumption_period')->nullable();
            $table->string('consumption_for')->default('asset');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assets_groups', function (Blueprint $table) {
            $table->dropColumn('consumption_type');
            $table->dropColumn('age_years');
            $table->dropColumn('age_months');
            $table->dropColumn('consumption_period');
            $table->dropColumn('consumption_for');
        });
    }
}
