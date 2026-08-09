@extends('layouts.admin')

@section('page_title', 'Manage Order #' . $order->order_number)

@section('content')
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="admin-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <h4 class="fw-bold m-0">Order Summary: {{ $order->order_number }}</h4>
                <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-outline-dark rounded-pill"><i class="bi bi-printer me-1"></i> Print Invoice</a>
            </div>

            <!-- Customer & Shipping details -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <h6 class="fw-bold text-uppercase text-muted small">Billing & Customer Information</h6>
                    <h5 class="fw-bold text-dark m-0">{{ $order->billing_name }}</h5>
                    <p class="small text-muted mb-1"><i class="bi bi-telephone me-1"></i> {{ $order->billing_phone }}</p>
                    <p class="small text-muted mb-1"><i class="bi bi-envelope me-1"></i> {{ $order->billing_email }}</p>
                    <p class="small text-muted mb-0"><i class="bi bi-geo-alt me-1"></i> {{ $order->billing_address }}, {{ $order->billing_city }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-uppercase text-muted small">Payment Method & Status</h6>
                    <p class="small m-0">Gateway: <strong>{{ $order->payment_method_name }}</strong></p>
                    <p class="small m-0">Transaction TrxID: <strong>{{ $order->transaction_id ?? 'N/A' }}</strong></p>
                    <p class="small m-0">Payment Status: <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">{{ strtoupper($order->payment_status) }}</span></p>
                </div>
            </div>

            <!-- Order Items -->
            <h5 class="fw-bold mb-3">Order Items</h5>
            <div class="table-responsive mb-4">
                <table class="table align-middle">
                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td class="fw-bold">
                                    {{ $item->product_name }}
                                    @if(!empty($item->variant_info['color']) || !empty($item->variant_info['size']))
                                        <div class="badge bg-light text-primary border ms-1">
                                            @if(!empty($item->variant_info['color'])) Color: {{ $item->variant_info['color'] }} @endif
                                            @if(!empty($item->variant_info['size'])) | Size: {{ $item->variant_info['size'] }} @endif
                                        </div>
                                    @endif
                                </td>
                                <td>৳{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-end fw-bold text-primary">৳{{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-top pt-3 text-end">
                <h4 class="fw-bold text-dark">Total Amount: <span class="text-primary">৳{{ number_format($order->total_amount, 2) }}</span></h4>
            </div>
        </div>

        <!-- Order Timeline Logs -->
        <div class="admin-card p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-clock-history text-primary me-2"></i> Order Status Activity History</h5>
            <div class="vstack gap-3 border-start border-primary ps-3">
                @foreach($order->timelines as $t)
                    <div>
                        <div class="fw-bold text-dark">{{ $t->status }}</div>
                        <p class="small text-muted m-0">{{ $t->comment }}</p>
                        <span class="small text-secondary" style="font-size: 0.72rem;">{{ $t->created_at->format('d M Y, h:i A') }} - By {{ $t->created_by }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Update Order Status Sidebar Form -->
    <div class="col-lg-4">
        <div class="admin-card p-4">
            <h5 class="fw-bold mb-3 border-bottom pb-2">Update Order Status</h5>

            <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Order Lifecycle Status</label>
                    <select name="order_status" class="form-select rounded-pill">
                        <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $order->order_status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="packing" {{ $order->order_status == 'packing' ? 'selected' : '' }}>Packing</option>
                        <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="returned" {{ $order->order_status == 'returned' ? 'selected' : '' }}>Returned</option>
                        <option value="refunded" {{ $order->order_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Payment Status</label>
                    <select name="payment_status" class="form-select rounded-pill">
                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Timeline Note / Comment</label>
                    <textarea name="comment" rows="3" class="form-control rounded-3" placeholder="Explain status update to customer..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold">Save Status Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
