@extends('layouts.app')

@section('content')
<div class="container py-4">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
        </ol>
    </nav>

    <h3 class="fw-bold mb-4"><i class="bi bi-cart3 text-primary me-2"></i> Shopping Cart Summary</h3>

    @if(count($cart) > 0)
        <div class="row g-4">
            <!-- Cart Items Table -->
            <div class="col-lg-8">
                <div class="card-custom p-4 mb-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr class="text-muted small text-uppercase">
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $key => $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $item['image'] }}" width="60" height="60" class="rounded-3 me-3 object-fit-cover">
                                                <div>
                                                    <a href="{{ route('product.detail', $item['slug']) }}" class="fw-bold text-dark text-decoration-none d-block">{{ $item['name'] }}</a>
                                                    @if(!empty($item['variant']['color']) || !empty($item['variant']['size']))
                                                        <div class="badge bg-light text-primary border me-1 mb-1">
                                                            @if(!empty($item['variant']['color'])) Color: {{ $item['variant']['color'] }} @endif
                                                            @if(!empty($item['variant']['size'])) | Size: {{ $item['variant']['size'] }} @endif
                                                        </div>
                                                    @endif
                                                    <span class="small text-muted d-block">SKU: {{ $item['sku'] ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-dark">৳{{ number_format($item['price'], 2) }}</td>
                                        <td style="width: 140px;">
                                            <form action="{{ route('cart.update') }}" method="POST" class="d-flex align-items-center">
                                                @csrf
                                                <input type="hidden" name="key" value="{{ $key }}">
                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" onchange="this.form.submit()" class="form-control form-control-sm text-center rounded-pill">
                                            </form>
                                        </td>
                                        <td class="fw-extrabold text-primary">৳{{ number_format($item['total'], 2) }}</td>
                                        <td>
                                            <form action="{{ route('cart.remove') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="key" value="{{ $key }}">
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded-circle"><i class="bi bi-trash fs-5"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Coupon Input Form -->
                <div class="card-custom p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-ticket-perforated text-primary me-2"></i> Have a Promo Voucher or Coupon?</h6>
                    <form action="{{ route('cart.coupon') }}" method="POST" class="row g-2">
                        @csrf
                        <div class="col-md-8">
                            <input type="text" name="code" class="form-control rounded-pill" placeholder="Enter coupon code e.g. JRECOM2026..." required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-dark w-100 rounded-pill fw-semibold">Apply Coupon</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Summary Card -->
            <div class="col-lg-4">
                <div class="card-custom p-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Order Total Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold">৳{{ number_format($totals['subtotal'], 2) }}</span>
                    </div>

                    @if($totals['discount'] > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Voucher Discount</span>
                            <span class="fw-bold">-৳{{ number_format($totals['discount'], 2) }}</span>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Estimated Tax (5%)</span>
                        <span class="fw-bold">৳{{ number_format($totals['tax'], 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 border-top pt-2 fs-5">
                        <span class="fw-bold text-dark">Total Amount</span>
                        <span class="fw-extrabold text-primary">৳{{ number_format($totals['total'], 2) }}</span>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-primary-gradient btn-lg w-100 rounded-pill mt-2">
                        Proceed to Checkout <i class="bi bi-arrow-right me-1"></i>
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5 card-custom">
            <i class="bi bi-cart-x fs-1 text-muted"></i>
            <h4 class="fw-bold mt-3">Your Cart is Empty</h4>
            <p class="text-muted mb-4">Discover our top smartphone, gadget, and fashion deals today!</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary-gradient rounded-pill px-4 py-2">Start Shopping Now</a>
        </div>
    @endif

</div>
@endsection
