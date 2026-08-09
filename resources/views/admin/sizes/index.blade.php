@extends('layouts.admin')

@section('page_title', 'Size & Storage Options Management')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="admin-card p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-rulers text-primary me-2"></i> Add New Size / Storage Option</h5>
            <form action="{{ route('admin.sizes.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Size / Storage Name *</label>
                    <input type="text" name="name" class="form-control rounded-pill" placeholder="e.g. 512GB / 12GB RAM, 2XL, 65 Inch" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Code / Abbreviation *</label>
                    <input type="text" name="code" class="form-control rounded-pill" placeholder="e.g. 512-12, 2XL, 65IN" required>
                </div>
                <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold">Save Size / Storage Option</button>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="admin-card p-4">
            <h5 class="fw-bold mb-3">Available Sizes & Storage Specs</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th>#</th>
                            <th>Name / Description</th>
                            <th>Code</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sizes as $idx => $size)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td class="fw-bold text-dark">{{ $size->name }}</td>
                                <td><code class="fw-bold text-primary">{{ $size->code }}</code></td>
                                <td>
                                    <form action="{{ route('admin.sizes.destroy', $size->id) }}" method="POST" onsubmit="return confirm('Delete size option?')">
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
            {{ $sizes->links() }}
        </div>
    </div>
</div>
@endsection
