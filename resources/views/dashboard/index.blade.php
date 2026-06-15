@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-primary">Sales Dashboard</h1>

    <!-- KPI Cards Row -->
    <div class="row mb-4">
        <!-- ... your KPI cards here ... -->
    </div>

    <!-- Filters Row -->
    <form method="GET" action="{{ route('dashboard.index') }}">
        <div class="row mb-4">
            <!-- Date range -->
            <div class="col-md-4">
                <label for="startDate" class="form-label">Start Date</label>
                <input type="date" name="startDate" id="startDate"
                       value="{{ request('startDate') }}" class="form-control"
                       onchange="this.form.submit()">

                <label for="endDate" class="form-label mt-2">End Date</label>
                <input type="date" name="endDate" id="endDate"
                       value="{{ request('endDate') }}" class="form-control"
                       onchange="this.form.submit()">
            </div>

            <!-- Period filter -->
            <div class="col-md-4">
                <label for="dateFilter" class="form-label">Period</label>
                <select name="dateFilter" id="dateFilter" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ request('dateFilter') == 'all' ? 'selected' : '' }}>All Time</option>
                    <option value="2026-Q1" {{ request('dateFilter') == '2026-Q1' ? 'selected' : '' }}>Q1 2026</option>
                    <option value="2026-Q2" {{ request('dateFilter') == '2026-Q2' ? 'selected' : '' }}>Q2 2026</option>
                    <option value="2026-H1" {{ request('dateFilter') == '2026-H1' ? 'selected' : '' }}>H1 2026</option>
                    <option value="2026-H2" {{ request('dateFilter') == '2026-H2' ? 'selected' : '' }}>H2 2026</option>
                    <option value="2026-Y"  {{ request('dateFilter') == '2026-Y'  ? 'selected' : '' }}>Year 2026</option>
                </select>
            </div>

            <!-- Product filter -->
            <div class="col-md-4">
                <label for="productFilter" class="form-label">Product</label>
                <select name="productFilter" id="productFilter" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ request('productFilter') == 'all' ? 'selected' : '' }}>All Products</option>
                    @foreach($allProducts as $product)
                        <option value="{{ $product->id }}" {{ request('productFilter') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Location filter -->
            <div class="col-md-4 mt-3">
                <label for="locationFilter" class="form-label">Location</label>
                <select name="locationFilter" id="locationFilter" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ request('locationFilter') == 'all' ? 'selected' : '' }}>All Locations</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ request('locationFilter') == $city->id ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <canvas id="salesByCityChart"></canvas>
        </div>
        <div class="col-md-6 mb-4">
            <canvas id="topProductsChart"></canvas>
        </div>
        <div class="col-md-6 mb-4">
            <canvas id="officeSupportChart"></canvas>
        </div>
        <div class="col-md-6 mb-4">
            <canvas id="salesTrendChart"></canvas>
        </div>
        <div class="col-md-12 mb-4">
            <canvas id="topCustomersChart"></canvas>
        </div>
    </div>
</div>

<script>
    // Best Market by City
    new Chart(document.getElementById('salesByCityChart'), {
        type: 'bar',
        data: {
            labels: {{ Js::from($salesByCity->pluck('name')) }},
            datasets: [{
                label: 'Total Sales',
                data: {{ Js::from($salesByCity->pluck('total_sales')) }},
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            }]
        }
    });

    // Top Product Sales
    new Chart(document.getElementById('topProductsChart'), {
        type: 'pie',
        data: {
            labels: {{ Js::from($topProducts->pluck('name')) }},
            datasets: [{
                data: {{ Js::from($topProducts->pluck('total_sales')) }},
                backgroundColor: ['#FF6384','#36A2EB','#FFCE56']
            }]
        }
    });

    // Office Support (horizontal bar style)
    new Chart(document.getElementById('officeSupportChart'), {
        type: 'bar',
        data: {
            labels: {{ Js::from($officeSupport->pluck('name')) }},
            datasets: [{
                label: 'Total Sales',
                data: {{ Js::from($officeSupport->pluck('total_sales')) }},
                backgroundColor: 'rgba(75, 192, 192, 0.7)'
            }]
        },
        options: {
            indexAxis: 'y'
        }
    });

    // Sales Trend Over Time
    new Chart(document.getElementById('salesTrendChart'), {
        type: 'line',
        data: {
            labels: {{ Js::from($salesTrend->pluck('month')) }},
            datasets: [{
                label: 'Total Sales',
                data: {{ Js::from($salesTrend->pluck('total_sales')) }},
                borderColor: 'rgba(255, 99, 132, 0.7)',
                fill: false
            }]
        }
    });

    // Top Customers
    new Chart(document.getElementById('topCustomersChart'), {
        type: 'doughnut',
        data: {
            labels: {{ Js::from($topCustomers->pluck('name')) }},
            datasets: [{
                data: {{ Js::from($topCustomers->pluck('total_sales')) }},
                backgroundColor: ['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF']
            }]
        }
    });
</script>
@endsection
