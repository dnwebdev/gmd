<?php

use Illuminate\Database\Seeder;

class CityInEnglishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ($check = \App\Models\City::whereNull('city_name_en')->first()) {
            $number = 0;
            \App\Models\City::where('city_name_en', NULL)->chunk(2000, function ($cities) use ($number) {
                foreach ($cities as $city) {
                    echo ++$number .' - '.$city->id_city.' . '.$city->city_name;
                    echo "\n";
                    $city->update(['city_name_en' => $city->city_name]);
                }
            });
        }
        if (\App\Models\State::whereNull('state_name_en')->first()) {
            $number = 0;
            \App\Models\State::whereNull('state_name_en')->chunk(2000, function ($states) use ($number) {
                foreach ($states as $state) {
                    echo ++$number .' - '.$state->id_state.' . '.$state->state_name;
                    echo "\n";
                    $state->update(['state_name_en' => $state->state_name]);
                }
            });
        }


    }
}
