@extends('layouts.admin')

@section('page_title', 'Brand Store Management')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="admin-card p-4">
            <h5 class="fw-bold mb-3">Add Brand</h5>
            <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Brand Name *</label>
                    <input type="text" name="name" class="form-control rounded-pill" placeholder="e.g. Sony" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Logo File Upload</label>
                    <input type="file" name="logo_file" class="form-control rounded-pill" accept="image/*">
                    <span class="small text-muted d-block mt-1">Or paste URL below:</span>
                    <input type="text" name="logo" class="form-control form-control-sm rounded-pill mt-1" placeholder="https://...">
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" checked>
                    <label class="form-check-label small" for="is_featured">Featured Brand</label>
                </div>
                <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold">Save Brand</button>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="admin-card p-4">
            <h5 class="fw-bold mb-3">All Brands</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th>Logo</th>
                            <th>Brand Name</th>
                            <th>Featured</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brands as $brand)
                            <tr>
                                <td><img src="{{ $brand->logo }}" height="35" class="object-fit-contain"></td>
                                <td class="fw-bold">{{ $brand->name }}</td>
                                <td><span class="badge bg-{{ $brand->is_featured ? 'success' : 'secondary' }}">{{ $brand->is_featured ? 'Yes' : 'No' }}</span></td>
                                <td>
                                    <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" onsubmit="return confirm('Delete brand?')">
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
            {{ $brands->links() }}
        </div>
    </div>
</div>
@endsection
