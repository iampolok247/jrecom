@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <!-- Sidebar nav -->
        <div class="col-lg-3">
            <div class="card-custom p-4 text-center mb-4">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                    <i class="bi bi-person-fill fs-1"></i>
                </div>
                <h5 class="fw-bold m-0">{{ $user->name }}</h5>
                <span class="small text-muted d-block mb-3">{{ $user->email }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill w-100 fw-bold">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout Account
                    </button>
                </form>
            </div>

            <div class="card-custom p-3">
                <div class="nav flex-column nav-pills">
                    <a href="{{ route('user.dashboard') }}" class="nav-link active rounded-pill mb-1 fw-bold"><i class="bi bi-grid me-2"></i> Dashboard Overview</a>
                    <a href="{{ route('user.orders') }}" class="nav-link text-dark rounded-pill mb-1 fw-bold"><i class="bi bi-bag-check me-2"></i> My Orders</a>
                    <a href="{{ route('user.wishlist') }}" class="nav-link text-dark rounded-pill mb-1 fw-bold"><i class="bi bi-heart me-2"></i> Wishlist</a>
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

        <!-- Dashboard Content -->
        <div class="col-lg-9">
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card-custom p-4 bg-primary text-white">
                        <h6 class="text-white-50 text-uppercase small fw-bold">Total Orders Placed</h6>
                        <h2 class="fw-extrabold text-white mb-0">{{ $totalOrdersCount }} Orders</h2>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-custom p-4 bg-dark text-white">
                        <h6 class="text-white-50 text-uppercase small fw-bold">Saved Wishlist Items</h6>
                        <h2 class="fw-extrabold text-white mb-0">{{ $wishlistCount }} Items</h2>
                    </div>
                </div>
            </div>

            <div class="card-custom p-4">
                <h5 class="fw-bold mb-3">Recent Orders</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr class="text-muted small">
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td class="fw-bold text-primary">{{ $order->order_number }}</td>
                                    <td class="small">{{ $order->created_at->format('d M, Y') }}</td>
                                    <td><span class="badge bg-info text-uppercase">{{ $order->order_status }}</span></td>
                                    <td class="fw-bold">৳{{ number_format($order->total_amount, 2) }}</td>
                                    <td><a href="{{ route('user.orders.detail', $order->order_number) }}" class="btn btn-sm btn-outline-primary rounded-pill">Details</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
