<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard | {{ \App\Models\SiteSetting::getByKey('site_name', 'JR-Ecom') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        :root {
            --admin-bg: #f8fafc;
            --sidebar-bg: linear-gradient(180deg, #0f172a 0%, #1e1b4b 100%);
            --card-border: #e2e8f0;
            --accent-primary: #6366f1;
            --accent-secondary: #10b981;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--admin-bg);
            color: #0f172a;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            width: 270px;
            min-height: 100vh;
            background: var(--sidebar-bg);
            color: #94a3b8;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            box-shadow: 4px 0 24px rgba(15, 23, 42, 0.15);
            display: flex;
            flex-direction: column;
        }

        .admin-brand-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 12px 16px;
        }

        .admin-sidebar .nav-link {
            color: #94a3b8;
            padding: 11px 16px;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 14px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            transition: all 0.25s ease;
            position: relative;
        }

        .admin-sidebar .nav-link i {
            font-size: 1.15rem;
            margin-right: 12px;
            transition: transform 0.2s ease;
        }

        .admin-sidebar .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(3px);
        }

        .admin-sidebar .nav-link:hover i {
            transform: scale(1.15);
        }

        .admin-sidebar .nav-link.active {
            color: #ffffff;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            box-shadow: 0 8px 20px -4px rgba(99, 102, 241, 0.5);
        }

        .admin-sidebar .nav-link.active i {
            color: #ffffff !important;
        }

        /* Nav Icon Specific Accent Colors */
        .admin-sidebar .nav-link:not(.active) .bi-grid-fill { color: #818cf8; }
        .admin-sidebar .nav-link:not(.active) .bi-box-seam-fill { color: #38bdf8; }
        .admin-sidebar .nav-link:not(.active) .bi-folder-symlink-fill { color: #fbbf24; }
        .admin-sidebar .nav-link:not(.active) .bi-award-fill { color: #f472b6; }
        .admin-sidebar .nav-link:not(.active) .bi-palette-fill { color: #a78bfa; }
        .admin-sidebar .nav-link:not(.active) .bi-rulers { color: #34d399; }
        .admin-sidebar .nav-link:not(.active) .bi-cart-check-fill { color: #4ade80; }
        .admin-sidebar .nav-link:not(.active) .bi-credit-card-2-front-fill { color: #fb7185; }
        .admin-sidebar .nav-link:not(.active) .bi-layout-text-window-reverse { color: #22d3ee; }
        .admin-sidebar .nav-link:not(.active) .bi-ticket-perforated-fill { color: #f59e0b; }
        .admin-sidebar .nav-link:not(.active) .bi-people-fill { color: #60a5fa; }
        .admin-sidebar .nav-link:not(.active) .bi-gear-wide-connected { color: #94a3b8; }
        .admin-sidebar .nav-link:not(.active) .bi-shield-check { color: #10b981; }

        /* Main Section */
        .admin-main {
            margin-left: 270px;
            padding: 28px;
            min-height: calc(100vh - 72px);
        }

        /* Top Navbar */
        .top-navbar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--card-border);
            margin-left: 270px;
            padding: 14px 28px;
            position: sticky;
            top: 0;
            z-index: 1040;
        }

        /* Pulse Dot Animation */
        .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: #10b981;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulseDot 1.8s infinite;
        }

        @keyframes pulseDot {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        /* Modern Admin Cards */
        .admin-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 20px;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.03), 0 8px 10px -6px rgba(15, 23, 42, 0.02);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .admin-card:hover {
            box-shadow: 0 20px 30px -10px rgba(15, 23, 42, 0.07);
        }

        /* Avatar Circle */
        .avatar-circle-sm {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="admin-sidebar p-3">
        <div class="admin-brand-card mb-4 mt-1">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-gradient p-2 rounded-3 text-white me-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="bi bi-shop fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-extrabold text-white mb-0" style="letter-spacing: -0.3px;">JR-Ecom</h6>
                    <span class="badge bg-indigo text-indigo-subtle px-2 py-0.5 rounded-pill fw-bold" style="font-size: 0.65rem; background: rgba(99, 102, 241, 0.25); color: #c7d2fe;">PRO ADMIN CONTROL</span>
                </div>
            </div>
        </div>

        <nav class="nav flex-column flex-grow-1 overflow-y-auto pe-1">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam-fill"></i> Products Catalog
            </a>
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-folder-symlink-fill"></i> Categories Tree
            </a>
            <a href="{{ route('admin.brands.index') }}" class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                <i class="bi bi-award-fill"></i> Brands
            </a>
            <a href="{{ route('admin.colors.index') }}" class="nav-link {{ request()->routeIs('admin.colors.*') ? 'active' : '' }}">
                <i class="bi bi-palette-fill"></i> Colors & Swatches
            </a>
            <a href="{{ route('admin.sizes.index') }}" class="nav-link {{ request()->routeIs('admin.sizes.*') ? 'active' : '' }}">
                <i class="bi bi-rulers"></i> Sizes & Storage Specs
            </a>
            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-cart-check-fill"></i> Orders & Shipping
            </a>
            <a href="{{ route('admin.payment.index') }}" class="nav-link {{ request()->routeIs('admin.payment.*') ? 'active' : '' }}">
                <i class="bi bi-credit-card-2-front-fill"></i> Paymently & Gateways
            </a>
            <a href="{{ route('admin.homepage.index') }}" class="nav-link {{ request()->routeIs('admin.homepage.*') ? 'active' : '' }}">
                <i class="bi bi-layout-text-window-reverse"></i> Homepage Builder
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated-fill"></i> Coupons & Vouchers
            </a>
            <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Customers
            </a>
            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear-wide-connected"></i> Site Settings
            </a>
            <a href="{{ route('admin.activity.index') }}" class="nav-link {{ request()->routeIs('admin.activity.*') ? 'active' : '' }}">
                <i class="bi bi-shield-check"></i> Activity Logs
            </a>
        </nav>

        <div class="mt-auto pt-3 border-top border-secondary border-opacity-25">
            <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-outline-light w-100 mb-2 rounded-pill fw-semibold py-2">
                <i class="bi bi-box-arrow-up-right me-1"></i> View Storefront
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger w-100 rounded-pill fw-semibold py-2 bg-gradient border-0 shadow-sm">
                    <i class="bi bi-power me-1"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Top Navbar -->
    <header class="top-navbar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <h5 class="fw-bold text-dark mb-0" style="letter-spacing: -0.4px;">@yield('page_title', 'Admin Panel')</h5>
            <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold px-3 py-1.5 rounded-pill d-flex align-items-center gap-2">
                <span class="pulse-dot"></span> System Online
            </span>
        </div>

        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm rounded-circle p-2 shadow-sm text-secondary me-1 position-relative" title="Orders Alert">
                <i class="bi bi-bell-fill fs-6"></i>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
            </a>

            <div class="d-flex align-items-center gap-2 ps-3 border-start">
                <div class="avatar-circle-sm bg-gradient text-white shadow-sm" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <div class="fw-bold text-dark lh-1 mb-0" style="font-size: 0.9rem;">{{ auth()->user()->name }}</div>
                    <span class="small text-muted" style="font-size: 0.75rem;">Administrator</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="ms-2">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1" title="Logout">
                        <i class="bi bi-power"></i>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="admin-main">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 mb-4 shadow-sm" role="alert" style="background: #ecfdf5; border-left: 5px solid #10b981 !important;">
                <div class="d-flex align-items-center text-emerald-800">
                    <i class="bi bi-check-circle-fill text-emerald fs-4 me-3"></i>
                    <div>
                        <strong class="fw-bold">Success!</strong> {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 mb-4 shadow-sm" role="alert" style="background: #fef2f2; border-left: 5px solid #ef4444 !important;">
                <div class="d-flex align-items-center text-rose-800">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-4 me-3"></i>
                    <div>
                        <strong class="fw-bold">Notice:</strong> {{ session('error') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

