@extends('layouts.app')

@section('content')
<div class="container py-4">

    <!-- Hero Banner Slider -->
    @if(isset($sections['hero_slider']) && $sections['hero_slider']->is_enabled)
        <div class="row mb-5">
            <div class="col-lg-12">
                <div class="swiper heroSwiper rounded-4 overflow-hidden shadow-sm">
                    <div class="swiper-wrapper">
                        @foreach($heroSliders as $slide)
                            <div class="swiper-slide position-relative">
                                <img src="{{ $slide->image }}" class="w-100 object-fit-cover" style="height: 440px; filter: brightness(0.85);" alt="{{ $slide->title }}">
                                <div class="position-absolute top-50 start-0 translate-middle-y text-white p-4 p-md-5" style="max-width: 600px; z-index: 10;">
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3 text-uppercase">Featured Promotion</span>
                                    <h1 class="fw-extrabold display-5 mb-2 text-white">{{ $slide->title }}</h1>
                                    <p class="fs-5 mb-4 text-light opacity-90">{{ $slide->subtitle }}</p>
                                    <a href="{{ $slide->link ?? route('shop.index') }}" class="btn btn-primary-gradient px-4 py-3 text-uppercase tracking-wider">
                                        {{ $slide->button_text ?? 'Shop Now' }} <i class="bi bi-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next text-white"></div>
                    <div class="swiper-button-prev text-white"></div>
                </div>
            </div>
        </div>
    @endif

    <!-- Top Categories Grid -->
    @if(isset($sections['top_categories']) && $sections['top_categories']->is_enabled)
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold m-0 text-dark">Explore Top Categories</h3>
                    <p class="text-muted small m-0">Find everything from flagship smartphones to summer fashion</p>
                </div>
                <a href="{{ route('shop.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View All Categories</a>
            </div>

            <div class="row g-3">
                @foreach($topCategories as $cat)
                    <div class="col-6 col-md-3">
                        <a href="{{ route('shop.index', ['category' => $cat->slug]) }}" class="text-decoration-none">
                            <div class="card-custom p-4 text-center h-100 d-flex flex-column align-items-center justify-content-center">
                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                    <i class="bi {{ $cat->icon ?? 'bi-bag-check' }} fs-2"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">{{ $cat->name }}</h6>
                                <span class="small text-muted">{{ $cat->products_count ?? rand(20, 150) }}+ Products</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Flash Sale Countdown Section -->
    @if(isset($sections['flash_sale']) && $sections['flash_sale']->is_enabled && $flashSaleProducts->count() > 0)
        <section class="mb-5 p-4 rounded-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);">
            <div class="row align-items-center mb-4">
                <div class="col-md-6 text-white">
                    <span class="badge bg-danger text-white px-3 py-2 rounded-pill fw-bold mb-2">
                        <i class="bi bi-fire me-1"></i> LIMITED TIME FLASH SALE
                    </span>
                    <h2 class="fw-bold text-white mb-0">Hurry Up! Unbeatable Offers</h2>
                </div>
                <div class="col-md-6 d-flex justify-content-md-end mt-3 mt-md-0">
                    <!-- Countdown timer -->
                    <div class="d-flex gap-2 text-center text-white" x-data="{ hours: 14, minutes: 35, seconds: 45 }" x-init="setInterval(() => { if(seconds > 0) seconds--; else { seconds = 59; if(minutes > 0) minutes--; else { minutes = 59; hours--; } } }, 1000)">
                        <div class="bg-white text-dark rounded-3 px-3 py-2 fw-bold">
                            <span class="fs-4 d-block" x-text="hours">14</span>
                            <span class="small text-muted" style="font-size: 0.65rem;">HOURS</span>
                        </div>
                        <div class="fs-3 fw-bold align-self-center text-white">:</div>
                        <div class="bg-white text-dark rounded-3 px-3 py-2 fw-bold">
                            <span class="fs-4 d-block" x-text="minutes">35</span>
                            <span class="small text-muted" style="font-size: 0.65rem;">MINS</span>
                        </div>
                        <div class="fs-3 fw-bold align-self-center text-white">:</div>
                        <div class="bg-white text-dark rounded-3 px-3 py-2 fw-bold">
                            <span class="fs-4 d-block" x-text="seconds">45</span>
                            <span class="small text-muted" style="font-size: 0.65rem;">SECS</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                @foreach($flashSaleProducts as $product)
                    <div class="col-6 col-md-4 col-lg-2">
                        @include('frontend.partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Featured Products Grid -->
    @if(isset($sections['featured_products']) && $sections['featured_products']->is_enabled)
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold m-0 text-dark">Featured Products</h3>
                    <p class="text-muted small m-0">Handpicked premium products recommended by experts</p>
                </div>
                <a href="{{ route('shop.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View All Products</a>
            </div>

            <div class="row g-4">
                @foreach($featuredProducts as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        @include('frontend.partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Offer Banners -->
    @if($offerBanners->count() > 0)
        <section class="mb-5">
            <div class="row g-3">
                @foreach($offerBanners as $banner)
                    <div class="col-md-12">
                        <div class="position-relative rounded-4 overflow-hidden shadow-sm">
                            <img src="{{ $banner->image }}" class="w-100 object-fit-cover" style="height: 220px; filter: brightness(0.8);" alt="{{ $banner->title }}">
                            <div class="position-absolute top-50 start-0 translate-middle-y p-4 text-white">
                                <h3 class="fw-extrabold text-white mb-1">{{ $banner->title }}</h3>
                                <p class="mb-3 text-light">{{ $banner->subtitle }}</p>
                                <a href="{{ $banner->link ?? route('shop.index') }}" class="btn btn-light rounded-pill px-4 fw-bold text-dark">
                                    {{ $banner->button_text ?? 'Claim Discount' }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Trending Products -->
    @if(isset($sections['trending_products']) && $sections['trending_products']->is_enabled)
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold m-0 text-dark">Trending Right Now</h3>
                    <p class="text-muted small m-0">Most popular products gaining traction this week</p>
                </div>
            </div>
            <div class="row g-4">
                @foreach($trendingProducts as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        @include('frontend.partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- New Arrivals Section -->
    @if(isset($sections['new_arrivals']) && $sections['new_arrivals']->is_enabled)
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold m-0 text-dark">New Arrivals & Fresh Stocks</h3>
                    <p class="text-muted small m-0">Check out the latest additions to our store catalog</p>
                </div>
                <a href="{{ route('shop.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View All Products</a>
            </div>

            <div class="row g-4">
                @foreach($newArrivals as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        @include('frontend.partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Popular Brands Showcase -->
    @if(isset($sections['popular_brands']) && $sections['popular_brands']->is_enabled)
        <section class="mb-5 py-4 bg-white rounded-4 border px-4">
            <h5 class="fw-bold text-center mb-4 text-dark">Shop Official Brand Stores</h5>
            <div class="row align-items-center justify-content-center g-4 text-center">
                @foreach($brands as $brand)
                    <div class="col-4 col-md-2">
                        <a href="{{ route('shop.index', ['brand' => $brand->slug]) }}" class="d-block p-2 opacity-75 hover-opacity-100">
                            <img src="{{ $brand->logo }}" alt="{{ $brand->name }}" class="img-fluid rounded" style="max-height: 50px; object-fit: contain;">
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Newsletter Subscription Banner -->
    <section class="p-5 rounded-4 text-white text-center position-relative overflow-hidden mb-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
        <div class="position-relative z-2 max-w-2xl mx-auto">
            <h2 class="fw-extrabold text-white mb-2">Subscribe & Get 10% Off Instant Voucher</h2>
            <p class="mb-4 text-light opacity-90">Join 50,000+ shoppers receiving secret weekly deals, flash coupons and tech news.</p>
            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="row g-2 justify-content-center">
                @csrf
                <div class="col-md-6">
                    <input type="email" name="email" class="form-control form-control-lg rounded-pill border-0 px-4" placeholder="Enter your email address..." required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-dark btn-lg w-100 rounded-pill fw-bold">Subscribe Now</button>
                </div>
            </form>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var swiper = new Swiper(".heroSwiper", {
            loop: true,
            autoplay: { delay: 4000, disableOnInteraction: false },
            pagination: { el: ".swiper-pagination", clickable: true },
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
        });
    });
</script>
@endpush
