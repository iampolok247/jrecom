<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paymently.io API Secure Gateway Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100 py-5">

    <div class="card border-0 shadow-lg rounded-4 p-4" style="max-width: 480px; width: 100%;">
        <div class="text-center mb-4 border-bottom pb-3">
            <div class="d-flex align-items-center justify-content-center mb-2">
                <img src="https://img.icons8.com/fluency/96/bank-cards.png" height="48" class="me-2">
                <h3 class="fw-extrabold m-0 text-primary">Paymently.io</h3>
            </div>
            <span class="badge bg-primary-subtle text-primary fw-bold rounded-pill">SECURE API SANDBOX PORTAL</span>
        </div>

        <div class="mb-4">
            <div class="p-3 bg-light rounded-3 mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Merchant:</span>
                    <span class="fw-bold">{{ \App\Models\SiteSetting::getByKey('site_name', 'JR-Ecom') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Order ID:</span>
                    <span class="fw-bold text-primary">{{ $order->order_number }}</span>
                </div>
                <div class="d-flex justify-content-between fs-5 fw-bold border-top pt-2">
                    <span>Payable Amount:</span>
                    <span class="text-success">৳{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <div class="alert alert-info small rounded-3">
                <i class="bi bi-info-circle-fill me-1"></i> Simulated API Environment. Click <strong>Complete Payment</strong> to trigger Paymently.io automated webhook verification.
            </div>
        </div>

        <a href="{{ route('paymently.callback', ['order' => $order->order_number, 'payment_id' => $paymentId]) }}" class="btn btn-success btn-lg w-100 rounded-pill mb-2 fw-bold">
            <i class="bi bi-check-circle-fill me-2"></i> Complete Payment (Simulate API Success)
        </a>
        <a href="{{ route('checkout.index') }}" class="btn btn-outline-secondary btn-sm w-100 rounded-pill">Cancel Transaction</a>
    </div>

</body>
</html>
