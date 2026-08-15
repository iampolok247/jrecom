@extends('layouts.admin')

@section('page_title', 'Edit Product')

@section('content')
<div class="admin-card p-4 p-md-5 max-w-5xl mx-auto shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h4 class="fw-bold m-0 text-dark">Edit Product: {{ $product->name }}</h4>
            <p class="text-muted small mb-0">Update gallery photos, colors, sizes, stock & pricing</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Back to Catalog
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger rounded-4 border-0 mb-4 shadow-sm">
            <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i> Please correct the following errors:</h6>
            <ul class="mb-0 small ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4 mb-4">
            <div class="col-md-8">
                <label class="form-label fw-bold">Product Name *</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control rounded-pill py-2" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">SKU Code *</label>
                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="form-control rounded-pill py-2" required>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Category *</label>
                <select name="category_id" class="form-select rounded-pill py-2" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Brand Store</label>
                <select name="brand_id" class="form-select rounded-pill py-2">
                    <option value="">-- Select Brand --</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}" {{ $product->brand_id == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Stock Inventory *</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="form-control rounded-pill py-2" required>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Regular Price (৳) *</label>
                <input type="number" name="regular_price" step="0.01" value="{{ old('regular_price', $product->regular_price) }}" class="form-control rounded-pill py-2" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Sale Price (৳)</label>
                <input type="number" name="sale_price" step="0.01" value="{{ old('sale_price', $product->sale_price) }}" class="form-control rounded-pill py-2">
            </div>

            <!-- Existing & New Images -->
            <div class="col-md-12">
                <div class="p-4 border rounded-4 bg-light">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-images me-2"></i> Manage Product Images</h5>
                    <div class="d-flex gap-2 mb-3 overflow-x-auto pb-2">
                        @foreach($product->images as $img)
                            <div class="position-relative">
                                <img src="{{ $img->image }}" width="80" height="80" class="rounded-3 border object-fit-cover">
                                @if($img->is_primary)
                                    <span class="badge bg-primary position-absolute top-0 start-0 m-1" style="font-size: 0.6rem;">Primary</span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Upload New Primary Image</label>
                            <input type="file" name="primary_image_file" class="form-control rounded-pill" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Upload Additional Gallery Images (up to 6 files)</label>
                            <input type="file" name="gallery_images[]" class="form-control rounded-pill" accept="image/*" multiple>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colors & Sizes -->
            <div class="col-md-12">
                <div class="p-4 border rounded-4 bg-white">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-sliders me-2"></i> Color & Size Variants</h5>
                    <div class="row g-4">
                        <div class="col-md-6 border-end">
                            <label class="form-label small fw-bold text-uppercase">Available Colors</label>
                            <div class="vstack gap-2">
                                @php $selectedColors = $product->variants->pluck('color_id')->toArray(); @endphp
                                @foreach($colors as $color)
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-2" type="checkbox" name="colors[]" value="{{ $color->id }}" id="ecolor_{{ $color->id }}" {{ in_array($color->id, $selectedColors) ? 'checked' : '' }}>
                                        <span class="d-inline-block rounded-circle border me-2" style="width: 18px; height: 18px; background-color: {{ $color->code }};"></span>
                                        <label class="form-check-label small fw-semibold" for="ecolor_{{ $color->id }}">{{ $color->name }} ({{ $color->code }})</label>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Inline Custom Color Creator -->
                            <div class="mt-3 p-3 bg-light rounded-3 border">
                                <label class="form-label small fw-bold text-primary mb-2"><i class="bi bi-plus-circle me-1"></i> Add Custom Color</label>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <input type="text" name="new_color_name" class="form-control form-control-sm rounded-pill" placeholder="Name e.g. Deep Purple">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex gap-1">
                                            <input type="color" class="form-control form-control-color form-control-sm rounded-3" value="#6b21a8" onchange="document.getElementById('new_color_code_edit').value = this.value">
                                            <input type="text" name="new_color_code" id="new_color_code_edit" class="form-control form-control-sm rounded-pill" placeholder="#6b21a8">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase">Available Sizes / Specs</label>
                            <div class="vstack gap-2">
                                @php $selectedSizes = $product->variants->pluck('size_id')->toArray(); @endphp
                                @foreach($sizes as $size)
                                    <div class="form-check">
                                        <input class="form-check-input me-2" type="checkbox" name="sizes[]" value="{{ $size->id }}" id="esize_{{ $size->id }}" {{ in_array($size->id, $selectedSizes) ? 'checked' : '' }}>
                                        <label class="form-check-label small fw-semibold" for="esize_{{ $size->id }}">{{ $size->name }} ({{ $size->code }})</label>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Inline Custom Size / Storage Creator -->
                            <div class="mt-3 p-3 bg-light rounded-3 border">
                                <label class="form-label small fw-bold text-primary mb-2"><i class="bi bi-plus-circle me-1"></i> Add Custom Size / Storage Option</label>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <input type="text" name="new_size_name" class="form-control form-control-sm rounded-pill" placeholder="Name e.g. 1TB SSD / 32GB RAM">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="new_size_code" class="form-control form-control-sm rounded-pill" placeholder="Code e.g. 1TB-32">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="col-md-12">
                <label class="form-label fw-bold">Short Summary Description</label>
                <textarea name="short_description" rows="2" class="form-control rounded-3">{{ old('short_description', $product->short_description) }}</textarea>
            </div>
            <div class="col-md-12">
                <label class="form-label fw-bold">Full Description</label>
                <textarea name="long_description" rows="4" class="form-control rounded-3">{{ old('long_description', $product->long_description) }}</textarea>
            </div>

            <!-- Badges -->
            <div class="col-md-12">
                <div class="d-flex flex-wrap gap-4 p-3 bg-light rounded-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" {{ $product->is_featured ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_featured">Featured</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_flash_sale" value="1" id="is_flash_sale" {{ $product->is_flash_sale ? 'checked' : '' }}>
                        <label class="form-check-label text-danger fw-bold" for="is_flash_sale">Flash Sale</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ $product->is_active ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">Active Status</label>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold fs-6 shadow-sm">
            Update Product Catalog
        </button>
    </form>
</div>
@endsection
