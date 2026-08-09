<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice_{{ $order->order_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #1e293b; background: #fff; }
        .invoice-card { padding: 40px; border: 1px solid #e2e8f0; }
        @media print {
            .no-print { display: none !important; }
            .invoice-card { border: none; padding: 0; }
        }
    </style>
</head>
<body class="p-4">

    <div class="no-print mb-4 text-center">
        <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 fw-bold"><i class="bi bi-printer me-1"></i> Print / Save as PDF</button>
    </div>

    <div class="container max-w-4xl mx-auto invoice-card">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center border-bottom pb-4 mb-4">
            <div>
                <h3 class="fw-bold text-primary m-0">{{ \App\Models\SiteSetting::getByKey('site_name', 'JR-Ecom') }}</h3>
                <span class="small text-muted">{{ \App\Models\SiteSetting::getByKey('office_address', 'Dhaka, Bangladesh') }}</span>
            </div>
            <div class="text-end">
                <h4 class="fw-bold text-uppercase text-secondary m-0">INVOICE</h4>
                <span class="fw-bold text-dark">#{{ $order->order_number }}</span><br>
                <span class="small text-muted">Date: {{ $order->created_at->format('d M, Y') }}</span>
            </div>
        </div>

        <!-- Addresses -->
        <div class="row mb-4">
            <div class="col-6">
                <h6 class="fw-bold text-muted text-uppercase small">Billed & Shipped To:</h6>
                <h5 class="fw-bold m-0">{{ $order->billing_name }}</h5>
                <p class="small text-muted m-0">{{ $order->billing_address }}, {{ $order->billing_city }}</p>
                <p class="small text-muted m-0">Phone: {{ $order->billing_phone }} | Email: {{ $order->billing_email }}</p>
            </div>
            <div class="col-6 text-end">
                <h6 class="fw-bold text-muted text-uppercase small">Payment Info:</h6>
                <p class="small m-0">Method: <strong>{{ $order->payment_method_name }}</strong></p>
                <p class="small m-0">Status: <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">{{ strtoupper($order->payment_status) }}</span></p>
                <p class="small m-0">TrxID: <strong>{{ $order->transaction_id ?? 'N/A' }}</strong></p>
            </div>
        </div>

        <!-- Table -->
        <table class="table table-bordered mb-4">
            <thead class="bg-light">
                <tr class="small text-uppercase">
                    <th>#</th>
                    <th>Item Description</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $idx => $item)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td class="fw-bold">
                            {{ $item->product_name }}
                            @if(!empty($item->variant_info['color']) || !empty($item->variant_info['size']))
                                <div class="small text-secondary fw-normal">
                                    @if(!empty($item->variant_info['color'])) Color: {{ $item->variant_info['color'] }} @endif
                                    @if(!empty($item->variant_info['size'])) | Size: {{ $item->variant_info['size'] }} @endif
                                </div>
                            @endif
                        </td>
                        <td>৳{{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="text-end fw-bold">৳{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="row justify-content-end">
            <div class="col-5">
                <div class="d-flex justify-content-between mb-1 small">
                    <span>Subtotal:</span>
                    <span class="fw-bold">৳{{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discount > 0)
                    <div class="d-flex justify-content-between mb-1 small text-success">
                        <span>Discount:</span>
                        <span class="fw-bold">-৳{{ number_format($order->discount, 2) }}</span>
                    </div>
                @endif
                <div class="d-flex justify-content-between mb-1 small">
                    <span>Shipping:</span>
                    <span class="fw-bold">৳{{ number_format($order->shipping_cost, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between border-top pt-2 fs-5 fw-bold text-primary">
                    <span>Total Amount:</span>
                    <span>৳{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="mt-5 pt-4 border-top text-center text-muted small">
            Thank you for shopping with {{ \App\Models\SiteSetting::getByKey('site_name', 'JR-Ecom') }}. For support email: {{ \App\Models\SiteSetting::getByKey('support_email', 'support@jrecom.com') }}
        </div>
    </div>

</body>
</html>
