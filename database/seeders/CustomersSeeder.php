<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\City;

class CustomersSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Juan Dela Cruz', 'email' => 'juan@example.com'],
            ['name' => 'Maria Santos', 'email' => 'maria@example.com'],
            ['name' => 'Pedro Reyes', 'email' => 'pedro@example.com'],
            ['name' => 'Ana Lopez', 'email' => 'ana@example.com'],
            ['name' => 'Carlos Garcia', 'email' => 'carlos@example.com'],
        ];

        foreach ($customers as $c) {
            Customer::create([
                'name'    => $c['name'],
                'email'   => $c['email'],
                'city_id' => City::inRandomOrder()->first()->id, // ✅ assign a valid city
            ]);
        }
    }
}
