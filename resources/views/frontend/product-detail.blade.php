@extends('layouts.app')

@section('content')
<div class="container py-4" x-data="{ 
    mainImage: '{{ $product->primary_image_url }}', 
    qty: 1, 
    selectedColorId: null, 
    selectedColorName: '', 
    selectedSizeId: null, 
    selectedSizeName: '' 
}">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.index') }}" class="text-decoration-none">Shop</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Product Main Card -->
    <div class="card-custom p-4 mb-5 shadow-sm">
        <div class="row g-4">
            <!-- Image Gallery -->
            <div class="col-lg-5">
                <div class="rounded-4 overflow-hidden bg-light mb-3 text-center position-relative shadow-sm" style="aspect-ratio: 1/1;">
                    <img :src="mainImage" class="w-100 h-100 object-fit-cover transition-all" alt="{{ $product->name }}">
                    @if($product->discount_percent > 0)
                        <span class="badge-discount">-{{ $product->discount_percent }}% OFF</span>
                    @endif
                </div>

                <!-- Gallery Thumbnails (Supports uploaded photos) -->
                <div class="d-flex gap-2 overflow-x-auto pb-2">
                    @foreach($product->images as $img)
                        <img src="{{ $img->image }}" class="rounded-3 border cursor-pointer me-1 shadow-sm" 
                             style="width: 72px; height: 72px; object-fit: cover;" 
                             @click="mainImage = '{{ $img->image }}'">
                    @endforeach
                </div>
            </div>

            <!-- Product Details -->
            <div class="col-lg-7 d-flex flex-column">
                <span class="badge bg-primary-subtle text-primary fw-bold text-uppercase w-auto me-auto mb-2 px-3 py-2 rounded-pill">
                    {{ $product->category->name ?? 'Gadgets' }}
                </span>
                <h2 class="fw-extrabold text-dark mb-2 display-6" style="font-size: 1.8rem;">{{ $product->name }}</h2>

                <!-- Rating & Reviews -->
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="text-warning">
                        @for($i=1; $i<=5; $i++)
                            <i class="bi bi-star-fill"></i>
                        @endfor
                    </div>
                    <span class="fw-bold text-dark">{{ $product->average_rating }}</span>
                    <span class="text-muted">({{ $product->reviews->count() }} customer reviews)</span>
                    <span class="text-muted">| SKU: <strong class="text-dark">{{ $product->sku }}</strong></span>
                </div>

                <!-- Price Block -->
                <div class="p-3 bg-light rounded-4 mb-4 d-flex align-items-baseline gap-3 border">
                    <span class="display-6 fw-extrabold text-primary">৳{{ number_format($product->effective_price, 2) }}</span>
                    @if($product->sale_price && $product->sale_price < $product->regular_price)
                        <span class="fs-5 text-muted text-decoration-line-through">৳{{ number_format($product->regular_price, 2) }}</span>
                        <span class="badge bg-danger text-white rounded-pill px-3 py-1">Save ৳{{ number_format($product->regular_price - $product->sale_price, 2) }}</span>
                    @endif
                </div>

                <!-- Short Description -->
                <p class="text-secondary mb-4">{{ $product->short_description }}</p>

                <!-- Stock Status -->
                <div class="mb-4">
                    @if($product->stock > 0)
                        <span class="badge bg-success-subtle text-success fw-bold px-3 py-2 rounded-pill"><i class="bi bi-check-circle-fill me-1"></i> In Stock ({{ $product->stock }} units available)</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger fw-bold px-3 py-2 rounded-pill"><i class="bi bi-x-circle-fill me-1"></i> Out of Stock</span>
                    @endif
                </div>

                <!-- Interactive Color Variant Selector Swatches -->
                @php $colorVariants = $product->variants->whereNotNull('color_id')->unique('color_id'); @endphp
                @if($colorVariants->count() > 0)
                    <div class="mb-4">
                        <label class="fw-bold small text-uppercase text-muted d-block mb-2">
                            Select Color: <span class="text-primary fw-extrabold" x-text="selectedColorName || 'Choose color'"></span>
                        </label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($colorVariants as $var)
                                @if($var->color)
                                    <button type="button" 
                                            class="btn rounded-pill px-3 py-2 btn-sm d-flex align-items-center gap-2 border fw-bold"
                                            :class="selectedColorId === {{ $var->color->id }} ? 'btn-dark text-white border-dark shadow-sm' : 'btn-outline-dark bg-white'"
                                            @click="selectedColorId = {{ $var->color->id }}; selectedColorName = '{{ $var->color->name }}'">
                                        <span class="rounded-circle d-inline-block border" style="width: 16px; height: 16px; background-color: {{ $var->color->code }};"></span>
                                        <span>{{ $var->color->name }}</span>
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Interactive Size / Capacity Variant Selector Swatches -->
                @php $sizeVariants = $product->variants->whereNotNull('size_id')->unique('size_id'); @endphp
                @if($sizeVariants->count() > 0)
                    <div class="mb-4">
                        <label class="fw-bold small text-uppercase text-muted d-block mb-2">
                            Select Size / Storage: <span class="text-primary fw-extrabold" x-text="selectedSizeName || 'Choose size'"></span>
                        </label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($sizeVariants as $var)
                                @if($var->size)
                                    <button type="button" 
                                            class="btn rounded-pill px-3 py-2 btn-sm border fw-bold"
                                            :class="selectedSizeId === {{ $var->size->id }} ? 'btn-primary text-white shadow-sm' : 'btn-outline-secondary bg-white text-dark'"
                                            @click="selectedSizeId = {{ $var->size->id }}; selectedSizeName = '{{ $var->size->name }}'">
                                        <span>{{ $var->size->name }} ({{ $var->size->code }})</span>
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Quantity & Add to Cart -->
                <div class="d-flex align-items-center gap-3 mt-auto pt-3 border-top">
                    <div class="input-group rounded-pill overflow-hidden border" style="width: 130px;">
                        <button class="btn btn-light" @click="if(qty > 1) qty--">-</button>
                        <input type="text" class="form-control text-center border-0 fw-bold" x-model="qty" readonly>
                        <button class="btn btn-light" @click="qty++">+</button>
                    </div>

                    <button class="btn btn-primary-gradient btn-lg px-4 flex-grow-1"
                            @click="addToCart({{ $product->id }}, qty, { color: selectedColorName, size: selectedSizeName })">
                        <i class="bi bi-cart-plus me-2"></i> Add to Shopping Cart
                    </button>

                    <!-- Wishlist Toggle -->
                    <button class="btn btn-outline-danger btn-lg rounded-circle" 
                            @click="
                                fetch('{{ route('user.wishlist.toggle', $product->id) }}', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                }).then(r => r.json()).then(d => alert(d.message));
                            ">
                        <i class="bi bi-heart-fill"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Technical Specs & Reviews Tabs -->
    <div class="card-custom p-4 mb-5">
        <ul class="nav nav-tabs border-bottom mb-4" id="prodTab">
            <li class="nav-item">
                <button class="nav-link active fw-bold text-dark" data-bs-toggle="tab" data-bs-target="#desc">Product Details & Specs</button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold text-dark" data-bs-toggle="tab" data-bs-target="#reviews">Customer Reviews ({{ $product->reviews->count() }})</button>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="desc">
                {!! $product->long_description !!}

                @if($product->specification)
                    <h5 class="fw-bold mt-4 mb-3">Technical Specifications</h5>
                    <table class="table table-bordered rounded-3 overflow-hidden">
                        <tbody>
                            @foreach($product->specification as $key => $val)
                                <tr>
                                    <th class="bg-light w-25 fw-semibold">{{ $key }}</th>
                                    <td>{{ $val }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Customer Reviews Form & List -->
            <div class="tab-pane fade" id="reviews">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <h5 class="fw-bold mb-3">Verified Buyer Reviews</h5>
                        @forelse($product->reviews as $rev)
                            <div class="p-3 bg-light rounded-4 mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-bold">{{ $rev->user->name ?? 'Verified Buyer' }}</span>
                                    <span class="text-warning small">
                                        @for($i=1;$i<=$rev->rating;$i++) <i class="bi bi-star-fill"></i> @endfor
                                    </span>
                                </div>
                                <p class="small text-muted mb-0">{{ $rev->review }}</p>
                            </div>
                        @empty
                            <p class="text-muted">No buyer reviews yet. Be the first to leave a review!</p>
                        @endforelse
                    </div>

                    <div class="col-lg-5">
                        <div class="p-4 border rounded-4 bg-white">
                            <h5 class="fw-bold mb-3">Write a Review</h5>
                            @auth
                                <form action="{{ route('product.review', $product->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Rating</label>
                                        <select name="rating" class="form-select rounded-pill">
                                            <option value="5">5 Stars - Excellent</option>
                                            <option value="4">4 Stars - Very Good</option>
                                            <option value="3">3 Stars - Average</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Review Comment</label>
                                        <textarea name="review" rows="3" class="form-control rounded-3" placeholder="Share your experience..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary-gradient w-100 rounded-pill">Submit Review</button>
                                </form>
                            @else
                                <p class="text-muted small">Please <a href="{{ route('login') }}" class="fw-bold">login</a> to post a customer review.</p>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <h4 class="fw-bold mb-4">Related Products You Might Like</h4>
        <div class="row g-4 mb-5">
            @foreach($relatedProducts as $rel)
                <div class="col-6 col-md-3">
                    @include('frontend.partials.product-card', ['product' => $rel])
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
