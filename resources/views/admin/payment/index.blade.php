@extends('layouts.admin')

@section('page_title', 'Payment Gateway & Paymently.io API Management')

@section('content')
<div class="row g-4 mb-4">
    <!-- Paymently.io API Settings Card -->
    <div class="col-lg-6">
        <div class="admin-card p-4 h-100">
            <div class="d-flex align-items-center mb-3">
                <img src="https://img.icons8.com/fluency/96/bank-cards.png" height="40" class="me-2">
                <h5 class="fw-bold m-0 text-primary">Paymently.io API Integration</h5>
            </div>
            <p class="small text-muted mb-4">Configure Paymently.io REST API credentials, webhook verification, environment mode, and automatic payment status updates.</p>

            <form action="{{ route('admin.payment.update_paymently') }}" method="POST">
                @csrf
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="paymently_enabled" id="paymently_enabled" {{ $paymentlyEnabled ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold" for="paymently_enabled">Enable Paymently.io Gateway</label>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">API Base URL</label>
                    <input type="text" name="paymently_base_url" value="{{ $paymentlyBaseUrl }}" class="form-control rounded-pill" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">API Public Key</label>
                    <input type="text" name="paymently_api_key" value="{{ $paymentlyApiKey }}" class="form-control rounded-pill" placeholder="pk_live_..." required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">API Secret Key</label>
                    <input type="password" name="paymently_secret_key" value="{{ $paymentlySecretKey }}" class="form-control rounded-pill" placeholder="sk_live_..." required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Environment</label>
                    <select name="paymently_environment" class="form-select rounded-pill">
                        <option value="sandbox" {{ $paymentlyEnv === 'sandbox' ? 'selected' : '' }}>Sandbox (Test Mode)</option>
                        <option value="production" {{ $paymentlyEnv === 'production' ? 'selected' : '' }}>Production (Live Real Money)</option>
                    </select>
                </div>

                <div class="p-3 bg-light rounded-4 mb-4">
                    <div class="small fw-bold text-dark mb-1"><i class="bi bi-link-45deg me-1"></i> Your Webhook URL for Paymently Dashboard:</div>
                    <code class="user-select-all small">{{ route('paymently.webhook') }}</code>
                </div>

                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold"><i class="bi bi-check-circle me-1"></i> Save Paymently Credentials</button>
            </form>
        </div>
    </div>

    <!-- Manual & Local Gateways (bKash, Nagad, Rocket, COD) -->
    <div class="col-lg-6">
        <div class="admin-card p-4 h-100">
            <h5 class="fw-bold mb-3">Local Payment Gateways</h5>
            <p class="small text-muted mb-4">Configure account numbers, instructions, and active status for bKash, Nagad, Rocket, and COD.</p>

            <div class="accordion" id="gatewayAccordion">
                @foreach($methods as $m)
                    <div class="accordion-item rounded-3 mb-2 overflow-hidden border">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#gw_{{ $m->id }}">
                                {{ $m->name }} <span class="badge bg-{{ $m->is_active ? 'success' : 'secondary' }} ms-2">{{ $m->is_active ? 'Active' : 'Disabled' }}</span>
                            </button>
                        </h2>
                        <div id="gw_{{ $m->id }}" class="accordion-collapse collapse" data-bs-parent="#gatewayAccordion">
                            <div class="accordion-body">
                                <form action="{{ route('admin.payment.update_method', $m->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Gateway Name</label>
                                        <input type="text" name="name" value="{{ $m->name }}" class="form-control form-control-sm rounded-pill">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Account / Merchant Number</label>
                                        <input type="text" name="account_number" value="{{ $m->account_number }}" class="form-control form-control-sm rounded-pill">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Instructions for Customer</label>
                                        <textarea name="instructions" rows="2" class="form-control form-control-sm rounded-3">{{ $m->instructions }}</textarea>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="act_{{ $m->id }}" {{ $m->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label small fw-semibold" for="act_{{ $m->id }}">Enabled for Checkout</label>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-dark rounded-pill px-3">Update Method</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Transaction & Webhook Logs Table -->
<div class="admin-card p-4">
    <h5 class="fw-bold mb-3"><i class="bi bi-list-columns-reverse text-primary me-2"></i> Paymently.io API Audit Logs</h5>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr class="text-muted small text-uppercase">
                    <th>Timestamp</th>
                    <th>Order #</th>
                    <th>Payment ID</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="small text-muted">{{ $log->created_at->format('d M Y, h:i:s A') }}</td>
                        <td class="fw-bold text-primary">{{ $log->order_number ?? 'N/A' }}</td>
                        <td class="small">{{ $log->payment_id ?? 'N/A' }}</td>
                        <td class="fw-bold">৳{{ number_format($log->amount, 2) }}</td>
                        <td><span class="badge bg-info text-uppercase">{{ $log->status }}</span></td>
                        <td class="small text-muted">{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No Paymently API logs recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
