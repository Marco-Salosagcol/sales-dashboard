<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class CitiesSeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['name' => 'Manila'],
            ['name' => 'Cebu'],
            ['name' => 'Davao'],
            ['name' => 'Baguio'],
            ['name' => 'Iloilo'],
        ];

        foreach ($cities as $c) {
            City::create($c);
        }
    }
}
