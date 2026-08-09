@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card-custom p-3">
                <div class="nav flex-column nav-pills">
                    <a href="{{ route('user.dashboard') }}" class="nav-link text-dark rounded-pill mb-1 fw-bold"><i class="bi bi-grid me-2"></i> Dashboard Overview</a>
                    <a href="{{ route('user.orders') }}" class="nav-link text-dark rounded-pill mb-1 fw-bold"><i class="bi bi-bag-check me-2"></i> My Orders</a>
                    <a href="{{ route('user.wishlist') }}" class="nav-link active rounded-pill mb-1 fw-bold"><i class="bi bi-heart me-2"></i> Wishlist</a>
                    <a href="{{ route('user.profile') }}" class="nav-link text-dark rounded-pill mb-1 fw-bold"><i class="bi bi-person-gear me-2"></i> Profile & Address</a>
                    <form action="{{ route('logout') }}" method="POST" class="mt-2 pt-2 border-top">
                        @csrf
                        <button type="submit" class="nav-link text-danger w-100 text-start rounded-pill fw-bold border-0 bg-transparent">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card-custom p-4">
                <h5 class="fw-bold mb-4">My Saved Wishlist</h5>

                <div class="row g-4">
                    @forelse($wishlists as $item)
                        @if($item->product)
                            <div class="col-md-4">
                                @include('frontend.partials.product-card', ['product' => $item->product])
                            </div>
                        @endif
                    @empty
                        <div class="col-12 text-center py-5 text-muted">
                            <i class="bi bi-heart fs-1 opacity-50"></i>
                            <p class="mt-2">No wishlist items saved yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
