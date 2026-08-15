<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ \App\Models\SiteSetting::getByKey('site_title', 'JR-Ecom | Modern E-Commerce Store') }}</title>
    <meta name="description" content="{{ \App\Models\SiteSetting::getByKey('seo_meta_description', 'Shop latest products at JR-Ecom.') }}">
    <meta name="keywords" content="{{ \App\Models\SiteSetting::getByKey('seo_meta_keywords', 'ecommerce, shop') }}">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ \App\Models\SiteSetting::getByKey('site_favicon', 'https://img.icons8.com/gradient/32/shopping-bag.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Dynamic Theme Styles -->
    <style>
        [x-cloak] { display: none !important; }
        :root {
            --primary-color: {{ \App\Models\SiteSetting::getByKey('primary_color', '#4f46e5') }};
            --primary-hover: #4338ca;
            --secondary-color: {{ \App\Models\SiteSetting::getByKey('secondary_color', '#06b6d4') }};
            --accent-color: {{ \App\Models\SiteSetting::getByKey('accent_color', '#f59e0b') }};
            --bg-body: #f8fafc;
            --card-radius: {{ \App\Models\SiteSetting::getByKey('border_radius', '16px') }};
            --font-main: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--bg-body);
            color: #1e293b;
            overflow-x: hidden;
        }

        .btn-primary-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: #ffffff;
            border: none;
            border-radius: var(--card-radius);
            font-weight: 600;
            padding: 10px 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
        }
        .btn-primary-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(79, 70, 229, 0.45);
            color: #fff;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: var(--card-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .card-custom {
            border: none;
            border-radius: var(--card-radius);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
            background: #ffffff;
        }
        .card-custom:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.09);
        }

        .sticky-top-header {
            position: sticky;
            top: 0;
            z-index: 1040;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e2e8f0;
        }

        .badge-discount {
            position: absolute;
            top: 12px;
            left: 12px;
            background: #ef4444;
            color: #fff;
            font-weight: 700;
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 20px;
            z-index: 2;
        }

        .product-img-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: calc(var(--card-radius) - 4px);
            aspect-ratio: 1 / 1;
            background: #f1f5f9;
        }
        .product-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .card-custom:hover .product-img-wrapper img {
            transform: scale(1.06);
        }

        .search-results-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            z-index: 1050;
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>
<body x-data="appCart()">

    <!-- Top Announcement Bar -->
    <div class="py-2 text-white text-center small fw-semibold" style="background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));">
        <i class="bi bi-lightning-fill me-1"></i> Mega Sale 2026 is LIVE! Get Free Express Shipping on orders over ৳2,000. Use Code: <span class="badge bg-white text-dark ms-1">JRECOM2026</span>
    </div>

    <!-- Header Section -->
    <header class="sticky-top-header py-3">
        <div class="container">
            <div class="row align-items-center">
                <!-- Brand Logo -->
                <div class="col-6 col-lg-3 d-flex align-items-center">
                    <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none text-dark">
                        @php
                            $siteLogo = \App\Models\SiteSetting::getByKey('site_logo');
                            $siteName = \App\Models\SiteSetting::getByKey('site_name', 'JR-Ecom');
                        @endphp
                        @if(!empty($siteLogo))
                            <img src="{{ $siteLogo }}" alt="{{ $siteName }}" height="44" class="object-fit-contain">
                        @else
                            <div>
                                <span class="fs-3 fw-extrabold tracking-tight" style="color: var(--primary-color);">{{ $siteName }}</span>
                                <span class="d-block small text-muted lh-1 fw-medium" style="font-size: 0.68rem;">STOREFRONT</span>
                            </div>
                        @endif
                    </a>
                </div>

                <!-- Live Search Bar -->
                <div class="col-12 col-lg-6 my-2 my-lg-0 position-relative" x-data="{ searchQuery: '', results: [], showDropdown: false }" @click.outside="showDropdown = false">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 ps-3 rounded-start-pill"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" 
                               class="form-control bg-light border-start-0 py-2 rounded-end-pill shadow-none" 
                               placeholder="Search 10,000+ smartphones, laptops, fashion, cosmetics..."
                               x-model="searchQuery"
                               @input.debounce.300ms="
                                  if(searchQuery.length >= 2) {
                                      fetch('{{ route('search.suggestions') }}?query=' + searchQuery)
                                          .then(res => res.json())
                                          .then(data => { results = data; showDropdown = true; });
                                  } else { showDropdown = false; }
                               ">
                    </div>

                    <!-- Live Search Dropdown -->
                    <div class="search-results-dropdown p-2 mt-1" x-show="showDropdown && results.length > 0" x-transition>
                        <template x-for="item in results" :key="item.id">
                            <a :href="item.url" class="d-flex align-items-center p-2 text-decoration-none text-dark rounded-3 hover-bg-light">
                                <img :src="item.image" width="45" height="45" class="rounded me-3 object-fit-cover">
                                <div>
                                    <div class="fw-semibold text-truncate" style="max-width: 320px;" x-text="item.name"></div>
                                    <div class="small text-primary fw-bold" x-text="item.price"></div>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>

                <!-- Header Actions (Cart, Wishlist, Account) -->
                <div class="col-6 col-lg-3 d-flex align-items-center justify-content-end gap-3">
                    @auth
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                    <i class="bi bi-person-fill fs-5"></i>
                                </div>
                                <span class="fw-semibold d-none d-md-inline">{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4">
                                @if(auth()->user()->isAdmin())
                                    <li><a class="dropdown-item fw-semibold text-primary" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Admin Panel</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('user.dashboard') }}"><i class="bi bi-grid me-2"></i>Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('user.orders') }}"><i class="bi bi-bag-check me-2"></i>My Orders</a></li>
                                <li><a class="dropdown-item" href="{{ route('user.wishlist') }}"><i class="bi bi-heart me-2"></i>Wishlist</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary rounded-pill px-3 py-1 text-dark border-0 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </a>
                    @endauth

                    <!-- Cart Drawer Trigger -->
                    <button class="btn btn-primary-gradient rounded-pill px-3 py-2 position-relative" @click="toggleMiniCart()">
                        <i class="bi bi-cart3 fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" x-text="$store.cart.cartCount">0</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation Mega Menu -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
        <div class="container">
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 font-weight-medium">
                    <li class="nav-item">
                        <a class="nav-link fw-bold text-dark me-3" href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i> Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-semibold me-3" href="#" data-bs-toggle="dropdown"><i class="bi bi-grid-3x3-gap me-1"></i> Categories</a>
                        <ul class="dropdown-menu border-0 shadow-lg rounded-4 p-3" style="min-width: 260px;">
                            @foreach(\App\Models\Category::where('level', 0)->take(8)->get() as $cat)
                                <li>
                                    <a class="dropdown-item py-2 rounded-3 d-flex align-items-center" href="{{ route('shop.index', ['category' => $cat->slug]) }}">
                                        <i class="bi {{ $cat->icon ?? 'bi-tag' }} me-2 text-primary"></i> {{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link fw-semibold me-3" href="{{ route('shop.index') }}">All Products</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold me-3 text-danger" href="{{ route('shop.index', ['filter' => 'flash_sale']) }}"><i class="bi bi-fire me-1"></i> Flash Sale</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold me-3" href="{{ route('shop.index', ['filter' => 'best_seller']) }}">Best Sellers</a></li>
                </ul>
                <div class="d-none d-lg-block text-muted small fw-semibold">
                    <i class="bi bi-headset me-1 text-primary"></i> Support: {{ \App\Models\SiteSetting::getByKey('support_phone', '+880 1700 000 000') }}
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Alerts -->
    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- Main Content Body -->
    <main>
        @yield('content')
    </main>

    <!-- Mini Cart Backdrop Overlay -->
    <div x-cloak
         x-show="$store.cart.showMiniCart" 
         @click="$store.cart.showMiniCart = false" 
         class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50" 
         style="z-index: 1070;" 
         x-transition.opacity></div>

    <!-- Mini Cart Slide-over Drawer -->
    <div x-cloak
         x-show="$store.cart.showMiniCart" 
         :class="$store.cart.showMiniCart ? 'd-flex' : 'd-none'"
         class="position-fixed top-0 end-0 bottom-0 bg-white shadow-lg p-4 flex-column" 
         style="width: 380px; z-index: 1080;" 
         x-transition>
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
            <h5 class="fw-bold m-0"><i class="bi bi-bag-check text-primary me-2"></i> Your Shopping Cart</h5>
            <button class="btn-close" @click="$store.cart.showMiniCart = false"></button>
        </div>
        <div class="overflow-y-auto flex-grow-1" style="max-height: calc(100vh - 220px);">
            <template x-for="(item, key) in $store.cart.cart" :key="key">
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <img :src="item.image" width="60" height="60" class="rounded-3 me-3 object-fit-cover">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 small fw-bold text-truncate" style="max-width: 180px;" x-text="item.name"></h6>
                        <div class="small text-muted" x-text="item.quantity + ' x ৳' + Number(item.price).toFixed(2)"></div>
                    </div>
                    <button class="btn btn-sm btn-link text-danger p-0 ms-2" @click="$store.cart.remove(key)"><i class="bi bi-trash"></i></button>
                </div>
            </template>
            <div x-show="Object.keys($store.cart.cart).length === 0" class="text-center py-5 text-muted">
                <i class="bi bi-cart-x fs-1 opacity-50"></i>
                <p class="mt-2">Your cart is empty.</p>
            </div>
        </div>
        <div class="border-top pt-3 mt-auto" x-show="Object.keys($store.cart.cart).length > 0">
            <div class="d-flex justify-content-between fw-bold mb-3 fs-5">
                <span>Subtotal:</span>
                <span class="text-primary" x-text="'৳' + Number($store.cart.totals.subtotal || 0).toFixed(2)"></span>
            </div>
            <a href="{{ route('cart.index') }}" class="btn btn-outline-dark w-100 rounded-pill mb-2 fw-semibold">View Full Cart</a>
            <a href="{{ route('checkout.index') }}" class="btn btn-primary-gradient w-100 rounded-pill">Proceed to Checkout</a>
        </div>
    </div>

    <!-- Modern Footer -->
    <footer class="bg-dark text-white pt-5 mt-5">
        <div class="container pb-4 border-bottom border-secondary">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="fw-bold mb-3 text-primary">{{ \App\Models\SiteSetting::getByKey('site_name', 'JR-Ecom') }}</h5>
                    <p class="text-secondary small">{{ \App\Models\SiteSetting::getByKey('footer_description', 'JR-Ecom is a premier multi-category e-commerce platform offering top brands, fast shipping, and 24/7 support.') }}</p>
                    <div class="d-flex gap-3 fs-5 mt-3">
                        <a href="{{ \App\Models\SiteSetting::getByKey('facebook_url', '#') }}" class="text-secondary hover-text-white"><i class="bi bi-facebook"></i></a>
                        <a href="{{ \App\Models\SiteSetting::getByKey('instagram_url', '#') }}" class="text-secondary hover-text-white"><i class="bi bi-instagram"></i></a>
                        <a href="{{ \App\Models\SiteSetting::getByKey('youtube_url', '#') }}" class="text-secondary hover-text-white"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="fw-bold text-white mb-3">Quick Links</h6>
                    <ul class="list-unstyled text-secondary small lh-lg">
                        <li><a href="{{ route('home') }}" class="text-decoration-none text-secondary">Home</a></li>
                        <li><a href="{{ route('shop.index') }}" class="text-decoration-none text-secondary">Shop All</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-decoration-none text-secondary">My Cart</a></li>
                        <li><a href="{{ route('checkout.index') }}" class="text-decoration-none text-secondary">Checkout</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="fw-bold text-white mb-3">Customer Service</h6>
                    <ul class="list-unstyled text-secondary small lh-lg">
                        <li><a href="{{ route('user.orders') }}" class="text-decoration-none text-secondary">Track Order</a></li>
                        <li><a href="#" class="text-decoration-none text-secondary">Return Policy</a></li>
                        <li><a href="#" class="text-decoration-none text-secondary">Warranty Info</a></li>
                        <li><a href="#" class="text-decoration-none text-secondary">Payment Gateways</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6 class="fw-bold text-white mb-3">Accepted Payment Methods</h6>
                    <p class="text-secondary small">We support 100% secure automated checkout via Paymently.io API & local banking.</p>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge bg-secondary p-2"><i class="bi bi-credit-card me-1"></i> Paymently.io</span>
                        <span class="badge bg-danger p-2">bKash</span>
                        <span class="badge bg-warning text-dark p-2">Nagad</span>
                        <span class="badge bg-primary p-2">Rocket</span>
                        <span class="badge bg-success p-2">Cash On Delivery</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container py-3 text-center text-secondary small">
            {{ \App\Models\SiteSetting::getByKey('footer_copyright', '© 2026 JR-Ecom Store. All Rights Reserved.') }}
        </div>
    </footer>

    <!-- JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        function registerCartStore() {
            if (window.Alpine && !window.Alpine.store('cart')) {
                Alpine.store('cart', {
                    showMiniCart: false,
                    cart: {},
                    totals: { subtotal: 0 },
                    get cartCount() {
                        return Object.values(this.cart || {}).reduce((sum, item) => sum + (parseInt(item.quantity) || 0), 0);
                    },
                    init() {
                        this.fetchCart();
                    },
                    fetchCart() {
                        fetch('{{ route('cart.mini') }}', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(r => r.json())
                        .then(data => {
                            this.cart = data.cart || {};
                            this.totals = data.totals || { subtotal: 0 };
                        })
                        .catch(err => console.error('Cart fetch error:', err));
                    },
                    toggleMiniCart() {
                        this.showMiniCart = !this.showMiniCart;
                        if (this.showMiniCart) this.fetchCart();
                    },
                    add(productId, quantity = 1, variant = {}) {
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
                        fetch('{{ route('cart.add') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({ product_id: productId, quantity: quantity, variant: variant })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                this.cart = data.cart;
                                this.totals = data.totals;
                                this.showMiniCart = true;
                            }
                        })
                        .catch(err => console.error('Cart add error:', err));
                    },
                    remove(key) {
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
                        fetch('{{ route('cart.remove') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({ key: key })
                        })
                        .then(r => r.json())
                        .then(data => {
                            this.cart = data.cart;
                            this.totals = data.totals;
                        })
                        .catch(err => console.error('Cart remove error:', err));
                    }
                });
            }
        }

        document.addEventListener('alpine:init', registerCartStore);
        if (window.Alpine) {
            registerCartStore();
        }

        // Global shortcut function for onclick and nested Alpine callers
        window.addToCart = function(productId, quantity = 1, variant = {}) {
            if (window.Alpine && window.Alpine.store('cart')) {
                window.Alpine.store('cart').add(productId, quantity, variant);
            }
        };

        function appCart() {
            return {
                get showMiniCart() { return window.Alpine && window.Alpine.store('cart') ? Alpine.store('cart').showMiniCart : false; },
                set showMiniCart(val) { if (window.Alpine && window.Alpine.store('cart')) Alpine.store('cart').showMiniCart = val; },
                get cart() { return window.Alpine && window.Alpine.store('cart') ? Alpine.store('cart').cart : {}; },
                get totals() { return window.Alpine && window.Alpine.store('cart') ? Alpine.store('cart').totals : { subtotal: 0 }; },
                get cartCount() { return window.Alpine && window.Alpine.store('cart') ? Alpine.store('cart').cartCount : 0; },
                toggleMiniCart() { if (window.Alpine && window.Alpine.store('cart')) Alpine.store('cart').toggleMiniCart(); },
                addToCart(productId, quantity = 1, variant = {}) { if (window.Alpine && window.Alpine.store('cart')) Alpine.store('cart').add(productId, quantity, variant); },
                removeItem(key) { if (window.Alpine && window.Alpine.store('cart')) Alpine.store('cart').remove(key); }
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
