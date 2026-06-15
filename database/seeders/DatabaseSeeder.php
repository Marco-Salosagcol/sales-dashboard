<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call all your custom seeders
        $this->call([
            CitiesSeeder::class,
            CustomersSeeder::class,
            ProductsSeeder::class,
            OfficesSeeder::class,
            SalesSeeder::class,
        ]);
    }
}
