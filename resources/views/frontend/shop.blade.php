@extends('layouts.app')

@section('content')
<div class="container py-4">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Shop Catalog</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <form action="{{ route('shop.index') }}" method="GET" class="card-custom p-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-funnel text-primary me-2"></i> Filter Products</h5>

                <!-- Search -->
                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Search</label>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control rounded-pill" placeholder="Keywords...">
                </div>

                <!-- Categories -->
                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Categories</label>
                    <div class="overflow-y-auto" style="max-height: 200px;">
                        @foreach($categories as $cat)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="category" value="{{ $cat->slug }}" id="cat_{{ $cat->id }}" {{ request('category') == $cat->slug ? 'checked' : '' }}>
                                <label class="form-check-label small fw-semibold" for="cat_{{ $cat->id }}">
                                    {{ $cat->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Brands -->
                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Brands</label>
                    <div class="overflow-y-auto" style="max-height: 180px;">
                        @foreach($brands as $brand)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="brand[]" value="{{ $brand->slug }}" id="brand_{{ $brand->id }}" {{ is_array(request('brand')) && in_array($brand->slug, request('brand')) ? 'checked' : '' }}>
                                <label class="form-check-label small" for="brand_{{ $brand->id }}">
                                    {{ $brand->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range -->
                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Max Price (৳)</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control rounded-pill" placeholder="e.g. 50000">
                </div>

                <button type="submit" class="btn btn-primary-gradient w-100 rounded-pill"><i class="bi bi-filter me-1"></i> Apply Filters</button>
                <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary btn-sm w-100 rounded-pill mt-2">Reset All</a>
            </form>
        </div>

        <!-- Product Grid & Sorting -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-4 border shadow-sm">
                <span class="text-muted small fw-semibold">Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} Products</span>
                <form action="{{ route('shop.index') }}" method="GET" class="d-flex align-items-center">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <input type="hidden" name="q" value="{{ request('q') }}">
                    <label class="small text-muted me-2 fw-semibold mb-0">Sort By:</label>
                    <select name="sort" onchange="this.form.submit()" class="form-select form-select-sm rounded-pill border-0 bg-light fw-semibold" style="width: 180px;">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                        <option value="price_low_high" {{ request('sort') == 'price_low_high' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high_low" {{ request('sort') == 'price_high_low' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="best_selling" {{ request('sort') == 'best_selling' ? 'selected' : '' }}>Best Sellers</option>
                    </select>
                </form>
            </div>

            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-6 col-md-4">
                        @include('frontend.partials.product-card', ['product' => $product])
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-search fs-1 text-muted"></i>
                        <h4 class="mt-3 fw-bold">No Products Found</h4>
                        <p class="text-muted">Try adjusting your filters or search keywords.</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-primary-gradient rounded-pill px-4">Browse All Catalog</a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $products->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
