@extends('layouts.app')

@section('content')
<div class="container py-4" x-data="{ selectedGateway: 'paymently' }">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none">Cart</a></li>
            <li class="breadcrumb-item active" aria-current="page">Express Checkout</li>
        </ol>
    </nav>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="row g-4">
            <!-- Billing Details -->
            <div class="col-lg-7">
                <div class="card-custom p-4 mb-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-geo-alt text-primary me-2"></i> Shipping & Billing Address</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Full Name *</label>
                            <input type="text" name="billing_name" value="{{ $user->name ?? '' }}" class="form-control rounded-pill" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email Address *</label>
                            <input type="email" name="billing_email" value="{{ $user->email ?? '' }}" class="form-control rounded-pill" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Phone Number *</label>
                            <input type="text" name="billing_phone" value="{{ $user->phone ?? '' }}" class="form-control rounded-pill" placeholder="+88017..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">City / Area *</label>
                            <input type="text" name="billing_city" value="{{ $user->city ?? 'Dhaka' }}" class="form-control rounded-pill" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Street Address *</label>
                            <textarea name="billing_address" rows="2" class="form-control rounded-3" placeholder="House no, road, area details..." required>{{ $user->address ?? '' }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Order Notes / Instructions (Optional)</label>
                            <textarea name="notes" rows="2" class="form-control rounded-3" placeholder="Special delivery instructions..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method Selection -->
                <div class="card-custom p-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-shield-lock-fill text-primary me-2"></i> Select Payment Method</h5>

                    <div class="vstack gap-3">
                        @forelse($paymentMethods as $pm)
                            @if($pm->code === 'paymently')
                                <!-- Paymently.io Premium Gateway Option -->
                                <div class="border rounded-4 p-4 cursor-pointer position-relative transition-all shadow-sm"
                                     :class="selectedGateway === 'paymently' ? 'border-primary bg-primary bg-opacity-10 shadow' : 'bg-white hover-shadow-sm'"
                                     style="border-width: 2px !important; transition: all 0.3s ease;"
                                     @click="selectedGateway = 'paymently'">
                                    
                                    <div class="form-check d-flex align-items-start justify-content-between m-0">
                                        <div class="d-flex align-items-start">
                                            <input class="form-check-input mt-1 me-3 fs-5" type="radio" name="payment_method_code" value="paymently" id="pm_{{ $pm->id }}" x-model="selectedGateway">
                                            <div>
                                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                                    <label class="form-check-label fw-bold text-dark fs-6 cursor-pointer mb-0" for="pm_{{ $pm->id }}">
                                                        Paymently.io Instant Gateway (Cards, Banking, MFS)
                                                    </label>
                                                    <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 small fw-bold" style="font-size: 0.7rem;">Recommended</span>
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 small fw-bold" style="font-size: 0.7rem;">
                                                        <i class="bi bi-lightning-charge-fill me-1"></i>Instant Automated
                                                    </span>
                                                </div>

                                                <p class="small text-muted mt-2 mb-3">
                                                    Pay securely & instantly using Visa, Mastercard, AMEX, bKash, Nagad, Rocket or Internet Banking with instant order confirmation.
                                                </p>

                                                <div class="d-flex flex-wrap align-items-center gap-2 pt-1">
                                                    <span class="badge bg-white text-dark border shadow-2xs rounded-pill px-3 py-1.5 small fw-semibold d-inline-flex align-items-center gap-1.5">
                                                        <span class="rounded-circle d-inline-block" style="width: 10px; height: 10px; background-color: #e2136e;"></span> bKash
                                                    </span>
                                                    <span class="badge bg-white text-dark border shadow-2xs rounded-pill px-3 py-1.5 small fw-semibold d-inline-flex align-items-center gap-1.5">
                                                        <span class="rounded-circle d-inline-block" style="width: 10px; height: 10px; background-color: #f7921e;"></span> Nagad
                                                    </span>
                                                    <span class="badge bg-white text-dark border shadow-2xs rounded-pill px-3 py-1.5 small fw-semibold d-inline-flex align-items-center gap-1.5">
                                                        <span class="rounded-circle d-inline-block" style="width: 10px; height: 10px; background-color: #8c3494;"></span> Rocket
                                                    </span>
                                                    <span class="badge bg-white text-dark border shadow-2xs rounded-pill px-3 py-1.5 small fw-semibold d-inline-flex align-items-center gap-1.5">
                                                        <i class="bi bi-credit-card-2-front-fill text-primary"></i> Cards & AMEX
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-none d-sm-block text-end">
                                            <div class="p-2.5 rounded-3 bg-white border shadow-2xs">
                                                <i class="bi bi-wallet2 text-primary fs-3"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Cash On Delivery Option -->
                                <div class="border rounded-4 p-4 cursor-pointer position-relative transition-all"
                                     :class="selectedGateway === '{{ $pm->code }}' ? 'border-primary bg-primary bg-opacity-10 shadow-sm' : 'bg-white hover-shadow-sm'"
                                     style="border-width: 2px !important; transition: all 0.3s ease;"
                                     @click="selectedGateway = '{{ $pm->code }}'">
                                    <div class="form-check d-flex align-items-start justify-content-between m-0">
                                        <div class="d-flex align-items-start">
                                            <input class="form-check-input mt-1 me-3 fs-5" type="radio" name="payment_method_code" value="{{ $pm->code }}" id="pm_{{ $pm->id }}" x-model="selectedGateway">
                                            <div>
                                                <label class="form-check-label fw-bold text-dark fs-6 cursor-pointer d-block mb-1" for="pm_{{ $pm->id }}">
                                                    {{ $pm->name }}
                                                </label>
                                                <p class="small text-muted mb-0">
                                                    {{ $pm->instructions ?? 'Pay cash upon receiving your items at your doorstep.' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-none d-sm-block text-end">
                                            <div class="p-2.5 rounded-3 bg-white border shadow-2xs">
                                                <i class="bi bi-cash-stack text-success fs-3"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <!-- Fallback default when payment_methods table is unseeded -->
                            <div class="border rounded-4 p-4 cursor-pointer bg-primary bg-opacity-10 border-primary shadow-sm" @click="selectedGateway = 'cod'">
                                <div class="form-check d-flex align-items-start justify-content-between m-0">
                                    <div class="d-flex align-items-start">
                                        <input class="form-check-input mt-1 me-3 fs-5" type="radio" name="payment_method_code" value="cod" id="pm_fallback_cod" checked x-model="selectedGateway">
                                        <div>
                                            <label class="form-check-label fw-bold text-dark fs-6 cursor-pointer d-block mb-1" for="pm_fallback_cod">
                                                Cash On Delivery (COD)
                                            </label>
                                            <p class="small text-muted mb-0">
                                                Pay cash upon receiving your items at your doorstep.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-none d-sm-block text-end">
                                        <div class="p-2.5 rounded-3 bg-white border shadow-2xs">
                                            <i class="bi bi-cash-stack text-success fs-3"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Order Summary & Checkout Button -->
            <div class="col-lg-5">
                <div class="card-custom p-4 sticky-top" style="top: 90px;">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Order Items ({{ count($cart) }})</h5>

                    <div class="overflow-y-auto mb-3" style="max-height: 250px;">
                        @foreach($cart as $item)
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $item['image'] }}" width="45" height="45" class="rounded-3 me-2 object-fit-cover">
                                    <div>
                                        <div class="small fw-bold text-truncate" style="max-width: 180px;">{{ $item['name'] }}</div>
                                        @if(!empty($item['variant']['color']) || !empty($item['variant']['size']))
                                            <div class="small text-primary" style="font-size: 0.7rem;">
                                                @if(!empty($item['variant']['color'])) {{ $item['variant']['color'] }} @endif
                                                @if(!empty($item['variant']['size'])) ({{ $item['variant']['size'] }}) @endif
                                            </div>
                                        @endif
                                        <span class="small text-muted" style="font-size: 0.72rem;">{{ $item['quantity'] }} x ৳{{ number_format($item['price'], 2) }}</span>
                                    </div>
                                </div>
                                <span class="fw-bold small text-primary">৳{{ number_format($item['total'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Subtotal</span>
                            <span class="fw-bold small">৳{{ number_format($totals['subtotal'], 2) }}</span>
                        </div>
                        @if($totals['discount'] > 0)
                            <div class="d-flex justify-content-between mb-2 text-success small">
                                <span>Coupon Discount</span>
                                <span class="fw-bold">-৳{{ number_format($totals['discount'], 2) }}</span>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Express Shipping</span>
                            <span class="fw-bold">৳{{ number_format($totals['shipping'], 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 border-top pt-2 fs-5">
                            <span class="fw-bold text-dark">Total Payable</span>
                            <span class="fw-extrabold text-primary">৳{{ number_format($totals['total'], 2) }}</span>
                        </div>

                        <button type="submit" class="btn btn-primary-gradient btn-lg w-100 rounded-pill py-3">
                            <i class="bi bi-lock-fill me-1"></i> Confirm & Place Order
                        </button>
                        <p class="text-center text-muted small mt-2 mb-0"><i class="bi bi-shield-check me-1"></i> 256-bit SSL Encrypted Secure Checkout</p>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection
