<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Product;
use App\Models\Office;
use App\Models\Sale;
use App\Models\Customer;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get filter values
        $dateFilter     = $request->input('dateFilter', 'all');
        $productFilter  = $request->input('productFilter', 'all');
        $locationFilter = $request->input('locationFilter', 'all');
        $startDate      = $request->input('startDate');
        $endDate        = $request->input('endDate');

        // Base query
        $salesQuery = Sale::query();

        // Date range filter
        if ($startDate && $endDate) {
            $salesQuery->whereBetween('sale_date', [$startDate, $endDate]);
        }

        // Period filter (quarterly, semi-annual, annual)
        if ($dateFilter !== 'all') {
            if (preg_match('/^\d{4}-Q(\d)$/', $dateFilter, $matches)) {
                $salesQuery->whereYear('sale_date', substr($dateFilter, 0, 4))
                    ->whereRaw('QUARTER(sale_date) = ?', [$matches[1]]);
            } elseif (preg_match('/^\d{4}-H(\d)$/', $dateFilter, $matches)) {
                // Semi-annual: H1 = months 1–6, H2 = months 7–12
                $year = substr($dateFilter, 0, 4);
                if ($matches[1] == 1) {
                    $salesQuery->whereBetween('sale_date', [
                        Carbon::create($year, 1, 1),
                        Carbon::create($year, 6, 30)
                    ]);
                } else {
                    $salesQuery->whereBetween('sale_date', [
                        Carbon::create($year, 7, 1),
                        Carbon::create($year, 12, 31)
                    ]);
                }
            } elseif (preg_match('/^\d{4}-Y$/', $dateFilter)) {
                $salesQuery->whereYear('sale_date', substr($dateFilter, 0, 4));
            }
        }

        // Product filter
        if ($productFilter !== 'all') {
            $salesQuery->where('product_id', $productFilter);
        }

        // Location filter
        if ($locationFilter !== 'all') {
            $salesQuery->whereHas('customer.city', function ($q) use ($locationFilter) {
                $q->where('id', $locationFilter);
            });
        }

        // 1. Best Market by City
        $salesByCity = (clone $salesQuery)
            ->selectRaw('cities.name, SUM(amount) as total_sales')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->join('cities', 'customers.city_id', '=', 'cities.id')
            ->groupBy('cities.name')
            ->get();

        // 2. Top Product Sales
        $topProducts = (clone $salesQuery)
            ->selectRaw('products.name, SUM(amount) as total_sales')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->groupBy('products.name')
            ->get();

        // 3. Best Office Support
        $officeSupport = (clone $salesQuery)
            ->selectRaw('offices.name, SUM(amount) as total_sales, offices.support_score')
            ->join('offices', 'sales.office_id', '=', 'offices.id')
            ->groupBy('offices.name', 'offices.support_score')
            ->get();

        // 4. Sales Trend Over Time
        $salesTrend = Sale::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total_sales')
            ->when($request->startDate && $request->endDate, function ($query) use ($request) {
                $query->whereBetween('created_at', [$request->startDate, $request->endDate]);
            })
            ->groupBy('month')
            ->orderBy('month')
            ->get();


        // 5. Top Customer Contribution
        $topCustomers = (clone $salesQuery)
            ->selectRaw('customers.name, SUM(amount) as total_sales')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->groupBy('customers.name')
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get();

        // Lists for dropdowns
        $allProducts = Product::all();
        $cities      = City::all();

        return view('dashboard.index', compact(
            'salesByCity',
            'topProducts',
            'officeSupport',
            'salesTrend',
            'topCustomers',
            'allProducts',
            'cities'
        ));
    }
}
