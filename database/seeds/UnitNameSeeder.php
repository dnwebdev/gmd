<?php

use Illuminate\Database\Seeder;
use App\Models\UnitName;

class UnitNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unites = [
            [
                'id'    => 'Orang',
                'en'    => 'Person'
            ],
            [
                'id'    => 'Mobil',
                'en'    => 'Car'
            ],
            [
                'id'    => 'Motor',
                'en'    => 'Motorcycle'
            ],
            [
                'id'    => 'Sepeda',
                'en'    => 'Bike'
            ],
            [
                'id'    => 'Kapal',
                'en'    => 'Boat'
            ],
            [
                'id'    => 'Tenda',
                'en'    => 'Tent'
            ],
            [
                'id'    => 'Villa',
                'en'    => 'Villa'
            ],
            [
                'id'    => 'Kamar',
                'en'    => 'Room'
            ],
            [
                'id'    => 'Bus Kecil (kapasitas 15-22 orang)',
                'en'    => 'Micro Bus / Mini Bus (capacity 15-22 persons)'
            ],
            [
                'id'    => 'Bus Sedang (kapasitas 27-34 orang)',
                'en'    => 'Medium Bus (capacity 27-34 persons)'
            ],
            [
                'id'    => 'Bus Besar (kapasitas 45-60 orang)',
                'en'    => 'Big Bus (capacity 45-60 persons)'
            ],
            [
                'id'    => 'Hari',
                'en'    => 'Day'
            ]
        ];

        UnitName::insert(
            collect($unites)->map(function ($value) {
                return [
                    'name_id'       => $value['id'],
                    'name_en'       => $value['en'],
                    'is_active'     => true,
                    'created_at'    => now()
                ];
            })->toArray()
        );
    }
}
