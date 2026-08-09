@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
    <div class="card-custom p-5 max-w-lg mx-auto">
        <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
            <i class="bi bi-check-circle-fill display-4"></i>
        </div>
        <h2 class="fw-bold text-dark mb-2">Order Confirmed!</h2>
        <p class="text-muted">Thank you for your order. We have received your purchase and are preparing it for delivery.</p>

        <div class="p-3 bg-light rounded-4 mb-4 text-start">
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Order Number:</span>
                <span class="fw-bold text-primary">{{ $order->order_number }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Payment Method:</span>
                <span class="fw-semibold">{{ $order->payment_method_name }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Payment Status:</span>
                <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }} text-uppercase">{{ $order->payment_status }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Total Amount:</span>
                <span class="fw-extrabold text-dark">৳{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <div class="d-flex gap-2 justify-content-center">
            @auth
                <a href="{{ route('user.orders.detail', $order->order_number) }}" class="btn btn-primary-gradient rounded-pill px-4">Track Order</a>
                <a href="{{ route('user.invoice', $order->order_number) }}" target="_blank" class="btn btn-outline-dark rounded-pill px-4"><i class="bi bi-printer me-1"></i> Print Invoice</a>
            @else
                <a href="{{ route('home') }}" class="btn btn-primary-gradient rounded-pill px-4">Return to Storefront</a>
            @endauth
        </div>
    </div>
</div>
@endsection
