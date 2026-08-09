@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">Order Timeline & Details: <span class="text-primary">{{ $order->order_number }}</span></h4>
        <a href="{{ route('user.invoice', $order->order_number) }}" target="_blank" class="btn btn-dark rounded-pill"><i class="bi bi-printer me-1"></i> Print Invoice PDF</a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card-custom p-4 h-100">
                <h6 class="fw-bold text-uppercase text-muted small">Billing & Shipping</h6>
                <h5 class="fw-bold text-dark mt-2 mb-1">{{ $order->billing_name }}</h5>
                <p class="small text-muted mb-1"><i class="bi bi-telephone me-1"></i> {{ $order->billing_phone }}</p>
                <p class="small text-muted mb-1"><i class="bi bi-envelope me-1"></i> {{ $order->billing_email }}</p>
                <p class="small text-muted mb-0"><i class="bi bi-geo-alt me-1"></i> {{ $order->billing_address }}, {{ $order->billing_city }}</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-custom p-4 h-100">
                <h6 class="fw-bold text-uppercase text-muted small">Payment Details</h6>
                <div class="mt-2">
                    <div class="small text-muted mb-1">Gateway: <strong class="text-dark">{{ $order->payment_method_name }}</strong></div>
                    <div class="small text-muted mb-1">Status: <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">{{ strtoupper($order->payment_status) }}</span></div>
                    <div class="small text-muted">Trx ID: <strong class="text-dark">{{ $order->transaction_id ?? 'N/A' }}</strong></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-custom p-4 h-100">
                <h6 class="fw-bold text-uppercase text-muted small">Current Delivery Status</h6>
                <h3 class="fw-extrabold text-primary mt-2 mb-1 text-uppercase">{{ $order->order_status }}</h3>
                <span class="small text-muted">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</span>
            </div>
        </div>
    </div>

    <!-- Order Timelines Activity -->
    <div class="card-custom p-4 mb-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-clock-history text-primary me-2"></i> Real-time Order Tracking Timeline</h5>
        <div class="vstack gap-3 border-start border-primary ps-3 ms-2">
            @foreach($order->timelines as $timeline)
                <div>
                    <div class="fw-bold text-dark">{{ $timeline->status }}</div>
                    <p class="small text-muted m-0">{{ $timeline->comment }}</p>
                    <span class="small text-secondary" style="font-size: 0.72rem;">{{ $timeline->created_at->format('d M Y, h:i A') }} - Updated by {{ $timeline->created_by }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Purchased Items -->
    <div class="card-custom p-4">
        <h5 class="fw-bold mb-3">Purchased Items</h5>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr class="text-muted small text-uppercase">
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td class="fw-bold">{{ $item->product_name }}</td>
                            <td>৳{{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="fw-bold text-primary">৳{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
