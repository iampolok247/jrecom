@extends('layouts.admin')

@section('page_title', 'Order & Shipping Management')

@section('content')
<div class="admin-card p-4">
    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2">
                <div class="col-md-5">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control rounded-pill" placeholder="Order #, Name, Phone...">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select rounded-pill" onchange="this.form.submit()">
                        <option value="">All Order Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-dark rounded-pill w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr class="text-muted small text-uppercase">
                    <th>Order #</th>
                    <th>Customer Name</th>
                    <th>Phone</th>
                    <th>Payment Gateway</th>
                    <th>Payment</th>
                    <th>Order Status</th>
                    <th>Total Payable</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $o)
                    <tr>
                        <td class="fw-bold text-primary">{{ $o->order_number }}</td>
                        <td class="fw-bold text-dark">{{ $o->billing_name }}</td>
                        <td class="small">{{ $o->billing_phone }}</td>
                        <td class="small text-muted">{{ $o->payment_method_name }}</td>
                        <td><span class="badge bg-{{ $o->payment_status === 'paid' ? 'success' : 'warning' }} text-uppercase">{{ $o->payment_status }}</span></td>
                        <td><span class="badge bg-primary text-uppercase">{{ $o->order_status }}</span></td>
                        <td class="fw-extrabold text-dark">৳{{ number_format($o->total_amount, 2) }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.orders.show', $o->id) }}" class="btn btn-sm btn-dark rounded-pill">Manage</a>
                                <a href="{{ route('admin.orders.invoice', $o->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill"><i class="bi bi-printer"></i></a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">No orders found matching criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection
