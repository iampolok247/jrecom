@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card-custom p-3">
                <div class="nav flex-column nav-pills">
                    <a href="{{ route('user.dashboard') }}" class="nav-link text-dark rounded-pill mb-1 fw-bold"><i class="bi bi-grid me-2"></i> Dashboard Overview</a>
                    <a href="{{ route('user.orders') }}" class="nav-link active rounded-pill mb-1 fw-bold"><i class="bi bi-bag-check me-2"></i> My Orders</a>
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

        <div class="col-lg-9">
            <div class="card-custom p-4">
                <h5 class="fw-bold mb-4">My Order History</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr class="text-muted small text-uppercase">
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Payment</th>
                                <th>Order Status</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="fw-bold text-primary">{{ $order->order_number }}</td>
                                    <td class="small">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                    <td><span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }} text-uppercase">{{ $order->payment_status }}</span></td>
                                    <td><span class="badge bg-primary text-uppercase">{{ $order->order_status }}</span></td>
                                    <td class="fw-extrabold text-dark">৳{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('user.orders.detail', $order->order_number) }}" class="btn btn-sm btn-outline-primary rounded-pill">View</a>
                                            <a href="{{ route('user.invoice', $order->order_number) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill"><i class="bi bi-printer"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">No orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
