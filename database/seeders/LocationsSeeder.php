<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            'Badakhshan', 'Badghis', 'Balkh', 'Bamyan', 'Daykundi', 'Farah', 'Faryab', 'Ghazni', 'Ghor',
            'Helmand', 'Herat', 'Jowzjan', 'Kabul', 'Kandahar', 'Kunar', 'Kunduz', 'Laghman', 'Logar', 'Nangarhar',
            'Nimroz', 'Nuristan', 'Paktia', 'Paktika', 'Panjshir', 'Parwan', 'Samangan', 'Sari Pul', 'Takhar',
            'Urozgan', 'Wardak', 'Zabul'
        ];

        foreach ($provinces as $province) {
            Location::create([
                'province' => $province,
            ]);
        }
    }
}
