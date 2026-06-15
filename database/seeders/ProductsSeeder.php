<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            ['name' => 'Laptop', 'category' => 'Electronics', 'price' => 45000],
            ['name' => 'Smartphone', 'category' => 'Electronics', 'price' => 25000],
            ['name' => 'Coffee Machine', 'category' => 'Appliances', 'price' => 8000],
        ]);
    }
}
