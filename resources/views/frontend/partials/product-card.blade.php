<div class="card-custom h-100 d-flex flex-column position-relative">
    @if($product->discount_percent > 0)
        <span class="badge-discount">-{{ $product->discount_percent }}% OFF</span>
    @endif

    <!-- Product Image -->
    <div class="product-img-wrapper">
        <a href="{{ route('product.detail', $product->slug) }}">
            <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" loading="lazy">
        </a>
    </div>

    <!-- Details Body -->
    <div class="p-3 d-flex flex-column flex-grow-1">
        <span class="text-uppercase text-muted fw-bold" style="font-size: 0.65rem;">{{ $product->category->name ?? 'Product' }}</span>
        <h6 class="fw-bold my-1 text-truncate" style="font-size: 0.9rem;">
            <a href="{{ route('product.detail', $product->slug) }}" class="text-decoration-none text-dark hover-text-primary">
                {{ $product->name }}
            </a>
        </h6>

        <!-- Rating Stars -->
        <div class="d-flex align-items-center mb-2" style="font-size: 0.75rem;">
            <div class="text-warning me-1">
                @for($i=1; $i<=5; $i++)
                    <i class="bi bi-star-fill"></i>
                @endfor
            </div>
            <span class="text-muted fw-semibold">({{ $product->reviews_count ?? rand(5, 80) }})</span>
        </div>

        <!-- Pricing -->
        <div class="mt-auto d-flex align-items-baseline gap-2">
            <span class="fs-5 fw-extrabold text-primary">৳{{ number_format($product->effective_price, 2) }}</span>
            @if($product->sale_price && $product->sale_price < $product->regular_price)
                <span class="small text-muted text-decoration-line-through">৳{{ number_format($product->regular_price, 2) }}</span>
            @endif
        </div>

        <!-- Add to Cart AJAX Button -->
        <button class="btn btn-outline-primary btn-sm w-100 rounded-pill mt-3 fw-semibold"
                @click="addToCart({{ $product->id }})">
            <i class="bi bi-cart-plus me-1"></i> Add to Cart
        </button>
    </div>
</div>
