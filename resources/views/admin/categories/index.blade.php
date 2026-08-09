@extends('layouts.admin')

@section('page_title', 'Category Tree Management')

@section('content')
<div class="row g-4">
    <div class="col-md-5">
        <div class="admin-card p-4">
            <h5 class="fw-bold mb-3">Add New Category</h5>
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Category Name *</label>
                    <input type="text" name="name" class="form-control rounded-pill" placeholder="e.g. Smart Watches" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Parent Category (For Sub/Child)</label>
                    <select name="parent_id" class="form-select rounded-pill">
                        <option value="">-- None (Root Category) --</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Bootstrap Icon Class</label>
                    <input type="text" name="icon" class="form-control rounded-pill" value="bi-tag">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Image File Upload</label>
                    <input type="file" name="image_file" class="form-control rounded-pill" accept="image/*">
                    <span class="small text-muted d-block mt-1">Or paste URL below:</span>
                    <input type="text" name="image" class="form-control form-control-sm rounded-pill mt-1" placeholder="https://...">
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured">
                    <label class="form-check-label small" for="is_featured">Feature on Homepage</label>
                </div>
                <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold">Create Category</button>
            </form>
        </div>
    </div>

    <div class="col-md-7">
        <div class="admin-card p-4">
            <h5 class="fw-bold mb-3">Category Tree List</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Parent</th>
                            <th>Level</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $cat)
                            <tr>
                                <td><i class="bi {{ $cat->icon ?? 'bi-tag' }} fs-4 text-primary"></i></td>
                                <td class="fw-bold">{{ $cat->name }}</td>
                                <td class="small text-muted">{{ $cat->parent->name ?? 'Root' }}</td>
                                <td><span class="badge bg-light text-dark border">Level {{ $cat->level }}</span></td>
                                <td>
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Delete category?')">
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
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
