@extends('layouts.admin')

@section('page_title', 'Dashboard Analytics')

@section('content')
<!-- Header Banner / Welcome Greeting -->
<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h4 class="fw-extrabold text-dark mb-1" style="letter-spacing: -0.5px;">Welcome back, {{ auth()->user()->name }}! 👋</h4>
        <p class="text-muted small mb-0">Here is what is happening across your JR-Ecom storefront today.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary bg-gradient rounded-pill px-3 py-2 fw-semibold shadow-sm border-0">
            <i class="bi bi-plus-circle me-1"></i> Add Product
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark rounded-pill px-3 py-2 fw-semibold">
            <i class="bi bi-file-earmark-text me-1"></i> View Orders
        </a>
    </div>
</div>

<!-- Primary Colorful Metric KPI Cards -->
<div class="row g-4 mb-4">
    <!-- Card 1: Total Revenue -->
    <div class="col-xl-3 col-md-6">
        <div class="admin-card p-4 position-relative overflow-hidden h-100" style="border-left: 4px solid #6366f1 !important;">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <span class="text-muted text-uppercase fw-bold" style="font-size: 0.725rem; letter-spacing: 0.5px;">Total Sales Revenue</span>
                    <h2 class="fw-extrabold text-dark mt-2 mb-0" style="letter-spacing: -0.5px;">৳{{ number_format($totalRevenue, 2) }}</h2>
                </div>
                <div class="rounded-4 p-3 d-flex align-items-center justify-content-center text-white shadow-sm" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); width: 52px; height: 52px;">
                    <i class="bi bi-wallet2 fs-4"></i>
                </div>
            </div>
            <div class="d-flex align-items-center pt-2 border-top border-light">
                <span class="badge bg-emerald-subtle text-success border border-emerald-subtle rounded-pill me-2 px-2 py-1 fw-bold" style="background: #dcfce7; color: #15803d; font-size: 0.75rem;">
                    <i class="bi bi-arrow-up-right me-1"></i>{{ $revenueGrowthPercent >= 0 ? '+' : '' }}{{ $revenueGrowthPercent }}%
                </span>
                <span class="text-muted small">vs previous month</span>
            </div>
        </div>
    </div>

    <!-- Card 2: Total Orders -->
    <div class="col-xl-3 col-md-6">
        <div class="admin-card p-4 position-relative overflow-hidden h-100" style="border-left: 4px solid #10b981 !important;">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <span class="text-muted text-uppercase fw-bold" style="font-size: 0.725rem; letter-spacing: 0.5px;">Total Store Orders</span>
                    <h2 class="fw-extrabold text-dark mt-2 mb-0" style="letter-spacing: -0.5px;">{{ number_format($totalOrders) }}</h2>
                </div>
                <div class="rounded-4 p-3 d-flex align-items-center justify-content-center text-white shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); width: 52px; height: 52px;">
                    <i class="bi bi-bag-check-fill fs-4"></i>
                </div>
            </div>
            <div class="d-flex align-items-center pt-2 border-top border-light">
                <span class="badge bg-success-subtle text-success border rounded-pill me-2 px-2 py-1 fw-bold" style="font-size: 0.75rem;">
                    <i class="bi bi-check-circle me-1"></i>Active Volume
                </span>
                <span class="text-muted small">{{ $completedOrders }} Delivered</span>
            </div>
        </div>
    </div>

    <!-- Card 3: Pending Action Req -->
    <div class="col-xl-3 col-md-6">
        <div class="admin-card p-4 position-relative overflow-hidden h-100" style="border-left: 4px solid #f59e0b !important;">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <span class="text-muted text-uppercase fw-bold" style="font-size: 0.725rem; letter-spacing: 0.5px;">Pending Orders</span>
                    <h2 class="fw-extrabold text-amber mt-2 mb-0" style="color: #d97706; letter-spacing: -0.5px;">{{ number_format($pendingOrders) }}</h2>
                </div>
                <div class="rounded-4 p-3 d-flex align-items-center justify-content-center text-white shadow-sm" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); width: 52px; height: 52px;">
                    <i class="bi bi-clock-history fs-4"></i>
                </div>
            </div>
            <div class="d-flex align-items-center pt-2 border-top border-light">
                <span class="badge bg-warning-subtle text-warning border rounded-pill me-2 px-2 py-1 fw-bold" style="font-size: 0.75rem;">
                    <i class="bi bi-exclamation-circle me-1"></i>Action Req
                </span>
                <span class="text-muted small">Requires Processing</span>
            </div>
        </div>
    </div>

    <!-- Card 4: Registered Customers -->
    <div class="col-xl-3 col-md-6">
        <div class="admin-card p-4 position-relative overflow-hidden h-100" style="border-left: 4px solid #38bdf8 !important;">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <span class="text-muted text-uppercase fw-bold" style="font-size: 0.725rem; letter-spacing: 0.5px;">Active Customers</span>
                    <h2 class="fw-extrabold text-dark mt-2 mb-0" style="letter-spacing: -0.5px;">{{ number_format($totalCustomers) }}</h2>
                </div>
                <div class="rounded-4 p-3 d-flex align-items-center justify-content-center text-white shadow-sm" style="background: linear-gradient(135deg, #38bdf8 0%, #0284c7 100%); width: 52px; height: 52px;">
                    <i class="bi bi-people-fill fs-4"></i>
                </div>
            </div>
            <div class="d-flex align-items-center pt-2 border-top border-light">
                <span class="badge bg-info-subtle text-info border rounded-pill me-2 px-2 py-1 fw-bold" style="font-size: 0.75rem;">
                    <i class="bi bi-person-check me-1"></i>Verified Users
                </span>
                <span class="text-muted small">Storefront Buyers</span>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Metric Counter Strip -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card p-3 d-flex align-items-center gap-3">
            <div class="bg-indigo-subtle p-2.5 rounded-3 text-indigo" style="background: #e0e7ff; color: #4338ca;">
                <i class="bi bi-box-seam fs-4"></i>
            </div>
            <div>
                <h6 class="fw-extrabold text-dark mb-0">{{ $totalProducts }}</h6>
                <span class="small text-muted">Catalog Products</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card p-3 d-flex align-items-center gap-3">
            <div class="bg-cyan-subtle p-2.5 rounded-3 text-cyan" style="background: #cff4fc; color: #055160;">
                <i class="bi bi-truck fs-4"></i>
            </div>
            <div>
                <h6 class="fw-extrabold text-dark mb-0">{{ $processingOrders }}</h6>
                <span class="small text-muted">In Fulfillment</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card p-3 d-flex align-items-center gap-3">
            <div class="bg-emerald-subtle p-2.5 rounded-3 text-emerald" style="background: #d1e7dd; color: #0f5132;">
                <i class="bi bi-check2-circle fs-4"></i>
            </div>
            <div>
                <h6 class="fw-extrabold text-dark mb-0">{{ $completedOrders }}</h6>
                <span class="small text-muted">Completed Orders</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card p-3 d-flex align-items-center gap-3">
            <div class="bg-rose-subtle p-2.5 rounded-3 text-rose" style="background: #f8d7da; color: #842029;">
                <i class="bi bi-exclamation-triangle fs-4"></i>
            </div>
            <div>
                <h6 class="fw-extrabold text-dark mb-0">{{ $lowStockProducts->count() }}</h6>
                <span class="small text-muted">Low Stock Alerts</span>
            </div>
        </div>
    </div>
</div>

<!-- Low Stock Warning Alert Banner -->
@if($lowStockProducts->count() > 0)
    <div class="admin-card p-4 mb-4 border-0 shadow-sm position-relative overflow-hidden" style="background: linear-gradient(135deg, #fffbe0 0%, #fef3c7 100%); border-left: 5px solid #f59e0b !important;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-warning fs-4"></i>
                <h6 class="fw-extrabold text-dark mb-0">Low Inventory Stock Warning</h6>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-warning text-dark rounded-pill fw-bold px-3">
                <i class="bi bi-box-arrow-in-right me-1"></i> Manage Stock
            </a>
        </div>
        <div class="row g-3">
            @foreach($lowStockProducts as $low)
                <div class="col-md-4">
                    <div class="bg-white p-3 rounded-4 border shadow-sm d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold text-dark text-truncate" style="max-width: 180px;">{{ $low->name }}</div>
                            <span class="small text-muted">Remaining Stock</span>
                        </div>
                        <span class="badge bg-danger text-white rounded-pill px-3 py-2 fw-extrabold fs-6">
                            {{ $low->stock }} left
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- ApexCharts Data Visualization Section -->
<div class="row g-4 mb-4">
    <!-- Main Spline Area Chart: Revenue & Order Analytics -->
    <div class="col-lg-8">
        <div class="admin-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-extrabold text-dark mb-1">Sales Revenue & Order Volume</h5>
                    <p class="text-muted small mb-0">Interactive 6-month growth spline analysis</p>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-semibold">
                        <i class="bi bi-calendar3 me-1"></i> Last 6 Months
                    </span>
                </div>
            </div>
            <div id="revenueSplineChart" style="min-height: 330px;"></div>
        </div>
    </div>

    <!-- Secondary Donut Chart: Order Fulfillment Status -->
    <div class="col-lg-4">
        <div class="admin-card p-4 h-100">
            <div class="mb-4">
                <h5 class="fw-extrabold text-dark mb-1">Order Status Distribution</h5>
                <p class="text-muted small mb-0">Breakdown of current order fulfillment</p>
            </div>
            <div id="orderStatusDonutChart" style="min-height: 330px;" class="d-flex align-items-center justify-content-center"></div>
        </div>
    </div>
</div>

<!-- Bottom Section: Recent Orders & Recent Buyers -->
<div class="row g-4">
    <!-- Recent Orders Table -->
    <div class="col-lg-8">
        <div class="admin-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-extrabold text-dark mb-1">Recent Storefront Orders</h5>
                    <p class="text-muted small mb-0">Latest orders placed by customers</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                    View All Orders <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0 border-0">
                    <thead>
                        <tr class="text-muted small text-uppercase border-bottom" style="letter-spacing: 0.5px;">
                            <th class="py-3">Order #</th>
                            <th class="py-3">Customer</th>
                            <th class="py-3">Payment</th>
                            <th class="py-3">Fulfillment</th>
                            <th class="py-3">Total Amount</th>
                            <th class="py-3 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestOrders as $ord)
                            <tr class="border-bottom">
                                <td class="py-3">
                                    <span class="fw-extrabold text-primary" style="letter-spacing: -0.2px;">{{ $ord->order_number }}</span>
                                    <div class="small text-muted" style="font-size: 0.75rem;">{{ $ord->created_at ? $ord->created_at->format('M d, H:i') : 'N/A' }}</div>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle-sm bg-light text-primary border fw-bold" style="font-size: 0.8rem;">
                                            {{ strtoupper(substr($ord->billing_name ?? 'C', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark lh-1 mb-0">{{ $ord->billing_name }}</div>
                                            <span class="small text-muted" style="font-size: 0.75rem;">{{ $ord->billing_phone }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    @if($ord->payment_status === 'paid')
                                        <span class="badge bg-emerald-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-bold">PAID</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1 fw-bold">UNPAID</span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 text-uppercase fw-bold" style="font-size: 0.725rem;">
                                        {{ $ord->order_status }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="fw-extrabold text-dark fs-6">৳{{ number_format($ord->total_amount, 2) }}</span>
                                </td>
                                <td class="py-3 text-end">
                                    <a href="{{ route('admin.orders.show', $ord->id) }}" class="btn btn-sm btn-dark rounded-pill px-3 fw-semibold">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No orders found yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Registered Buyers -->
    <div class="col-lg-4">
        <div class="admin-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-extrabold text-dark mb-1">New Store Customers</h5>
                    <p class="text-muted small mb-0">Recently registered buyers</p>
                </div>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 fw-bold">View All</a>
            </div>

            <div class="vstack gap-3">
                @forelse($latestCustomers as $c)
                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 hover-bg-light border border-light">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle-sm bg-gradient text-white shadow-sm" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                                {{ strtoupper(substr($c->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark lh-1 mb-1">{{ $c->name }}</div>
                                <span class="small text-muted" style="font-size: 0.78rem;">{{ $c->email }}</span>
                            </div>
                        </div>
                        <span class="badge bg-light text-secondary border rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                            {{ $c->created_at ? $c->created_at->diffForHumans() : 'Recently' }}
                        </span>
                    </div>
                @empty
                    <div class="text-muted text-center py-4">No recent customers.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. ApexCharts Revenue Spline Chart ---
        const months = @json($chartMonths);
        const revenueData = @json($chartRevenue);
        const ordersData = @json($chartOrders);

        const splineOptions = {
            series: [{
                name: 'Sales Revenue (BDT)',
                data: revenueData
            }, {
                name: 'Orders Count',
                data: ordersData
            }],
            chart: {
                height: 330,
                type: 'area',
                toolbar: { show: false },
                fontFamily: "'Plus Jakarta Sans', sans-serif",
                zoom: { enabled: false }
            },
            colors: ['#6366f1', '#10b981'],
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: [3, 3]
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.35,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                xaxis: { lines: { show: false } }
            },
            xaxis: {
                categories: months,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: '#64748b', fontWeight: 600 }
                }
            },
            yaxis: [
                {
                    title: { text: 'Revenue (৳)', style: { color: '#6366f1', fontWeight: 700 } },
                    labels: {
                        formatter: function (val) {
                            return '৳' + (val / 1000).toFixed(0) + 'k';
                        },
                        style: { colors: '#64748b', fontWeight: 600 }
                    }
                },
                {
                    opposite: true,
                    title: { text: 'Orders', style: { color: '#10b981', fontWeight: 700 } },
                    labels: {
                        formatter: function (val) {
                            return val.toFixed(0);
                        },
                        style: { colors: '#64748b', fontWeight: 600 }
                    }
                }
            ],
            tooltip: {
                shared: true,
                intersect: false,
                y: [{
                    formatter: function (y) {
                        if (typeof y !== "undefined") {
                            return "৳" + y.toLocaleString('en-US', {minimumFractionDigits: 2});
                        }
                        return y;
                    }
                }, {
                    formatter: function (y) {
                        if (typeof y !== "undefined") {
                            return y.toFixed(0) + " orders";
                        }
                        return y;
                    }
                }]
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                fontWeight: 600
            }
        };

        const splineChart = new ApexCharts(document.querySelector("#revenueSplineChart"), splineOptions);
        splineChart.render();


        // --- 2. ApexCharts Order Status Donut Chart ---
        const orderStats = @json($orderStatusStats);
        const donutOptions = {
            series: Object.values(orderStats),
            labels: Object.keys(orderStats),
            chart: {
                type: 'donut',
                height: 310,
                fontFamily: "'Plus Jakarta Sans', sans-serif"
            },
            colors: ['#f59e0b', '#06b6d4', '#10b981', '#ef4444'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '72%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Orders',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            legend: {
                position: 'bottom',
                fontWeight: 600,
                fontSize: '13px'
            },
            stroke: { width: 0 }
        };

        const donutChart = new ApexCharts(document.querySelector("#orderStatusDonutChart"), donutOptions);
        donutChart.render();
    });
</script>
@endpush

