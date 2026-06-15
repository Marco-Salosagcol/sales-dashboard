<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Office;
use Carbon\Carbon;

class SalesSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $products  = Product::all();
        $offices   = Office::all();

        if ($customers->isEmpty() || $products->isEmpty() || $offices->isEmpty()) {
            return; // prevent null errors
        }

        foreach ($offices as $office) {
            foreach ($products as $product) {
                for ($i = 0; $i < 20; $i++) {
                    $customer = $customers->random();

                    Sale::create([
                        'customer_id' => $customer->id,
                        'product_id'  => $product->id,
                        'office_id'   => $office->id,
                        'sale_date'   => Carbon::create(2026, rand(1, 6), rand(1, 28)),
                        'amount'      => rand(1000, 5000),
                    ]);
                }
            }
        }
    }
}
