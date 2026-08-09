@extends('layouts.admin')

@section('page_title', 'Coupons & Vouchers')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="admin-card p-4">
            <h5 class="fw-bold mb-3">Create Coupon Code</h5>
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Coupon Code *</label>
                    <input type="text" name="code" class="form-control rounded-pill" placeholder="e.g. JRECOM2026" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Discount Type *</label>
                    <select name="type" class="form-select rounded-pill">
                        <option value="fixed">Fixed Amount (৳)</option>
                        <option value="percent">Percentage (%)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Discount Value *</label>
                    <input type="number" name="value" step="0.01" class="form-control rounded-pill" placeholder="e.g. 500" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Minimum Spend (৳)</label>
                    <input type="number" name="min_spend" step="0.01" class="form-control rounded-pill" value="0">
                </div>
                <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold">Save Coupon</button>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="admin-card p-4">
            <h5 class="fw-bold mb-3">Active Coupons</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th>Code</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Min Spend</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coupons as $c)
                            <tr>
                                <td class="fw-bold text-primary">{{ $c->code }}</td>
                                <td><span class="badge bg-light text-dark border">{{ strtoupper($c->type) }}</span></td>
                                <td class="fw-bold">{{ $c->type === 'percent' ? $c->value . '%' : '৳' . number_format($c->value, 2) }}</td>
                                <td>৳{{ number_format($c->min_spend, 2) }}</td>
                                <td>
                                    <form action="{{ route('admin.coupons.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Delete coupon?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $coupons->links() }}
        </div>
    </div>
</div>
@endsection
