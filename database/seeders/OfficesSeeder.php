<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('offices')->insert([
            ['name' => 'Main Office', 'location' => 'Manila', 'support_score' => 95],
            ['name' => 'Cebu Branch', 'location' => 'Cebu', 'support_score' => 88],
            ['name' => 'Davao Branch', 'location' => 'Davao', 'support_score' => 90],
        ]);
    }
}
