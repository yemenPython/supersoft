<?php

use App\Models\Area;
use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
use Illuminate\Database\Migrations\Migration;

class SetValuesToSeederTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $currency = Currency::find(1);
        if ($currency) {
            $currency->update([
                'seeder' => 1,
            ]);
        }

        $country = Country::find(1);
        if ($country) {
            $country->update([
                'seeder' => 1,
            ]);
        }

        $city = City::find(1);
        if ($city) {
            $city->update([
                'seeder' => 1,
            ]);
        }

        $area = Area::find(1);
        if ($area) {
            $area->update([
                'seeder' => 1,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
