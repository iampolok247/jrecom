@extends('layouts.admin')

@section('page_title', 'Products Catalog')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold m-0">Products Catalog</h4>
        <p class="text-muted small m-0">Manage stock inventory, pricing, variants, and catalog items</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold">
        <i class="bi bi-plus-lg me-1"></i> Add New Product
    </a>
</div>

<div class="admin-card p-4">
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <form action="{{ route('admin.products.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control rounded-start-pill" placeholder="Search by SKU or Product Title...">
                    <button type="submit" class="btn btn-dark rounded-end-pill px-4">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr class="text-muted small text-uppercase">
                    <th>Image</th>
                    <th>Name & SKU</th>
                    <th>Category</th>
                    <th>Regular Price</th>
                    <th>Sale Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                    <tr>
                        <td>
                            <img src="{{ $p->primary_image_url }}" width="50" height="50" class="rounded-3 object-fit-cover">
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $p->name }}</div>
                            <span class="small text-muted">SKU: {{ $p->sku }}</span>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $p->category->name ?? 'N/A' }}</span></td>
                        <td class="fw-semibold">৳{{ number_format($p->regular_price, 2) }}</td>
                        <td class="fw-bold text-primary">৳{{ number_format($p->effective_price, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $p->stock <= 5 ? 'danger' : 'success' }} px-3 py-2 rounded-pill">
                                {{ $p->stock }} Pcs
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $p->is_active ? 'success' : 'secondary' }}">
                                {{ $p->is_active ? 'Active' : 'Draft' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.products.edit', $p->id) }}" class="btn btn-sm btn-outline-primary rounded-pill"><i class="bi bi-pencil me-1"></i> Edit</a>
                                <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">No products found in catalog.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
