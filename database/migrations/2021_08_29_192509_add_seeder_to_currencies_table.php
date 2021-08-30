<?php

use App\Models\Area;
use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeederToCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->boolean('seeder')->default(0);
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->boolean('seeder')->default(0);
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->boolean('seeder')->default(0);
        });

         Schema::table('areas', function (Blueprint $table) {
             $table->boolean('seeder')->default(0);
         });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropColumn('seeder');
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('seeder');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('seeder');
        });

        Schema::table('areas', function (Blueprint $table) {
            $table->dropColumn('seeder');
        });
    }
}
