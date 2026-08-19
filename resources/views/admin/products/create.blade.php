@extends('layouts.admin')

@section('page_title', 'Create New Product')

@section('content')
<div class="admin-card p-4 p-md-5 max-w-5xl mx-auto shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h4 class="fw-bold m-0 text-dark">Add New Catalog Product</h4>
            <p class="text-muted small mb-0">Upload images, configure color & size variants, inventory stock, and SEO</p>
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

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4 mb-4">
            <!-- Basic Details -->
            <div class="col-md-8">
                <label class="form-label fw-bold">Product Title / Name *</label>
                <input type="text" name="name" class="form-control rounded-pill py-2" placeholder="e.g. iPhone 15 Pro Max 256GB - Titanium" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">SKU Code *</label>
                <input type="text" name="sku" class="form-control rounded-pill py-2" value="SKU-{{ strtoupper(Str::random(6)) }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Category *</label>
                <select name="category_id" class="form-select rounded-pill py-2" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Brand Store</label>
                <select name="brand_id" class="form-select rounded-pill py-2">
                    <option value="">-- Select Brand --</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Available Stock Inventory *</label>
                <input type="number" name="stock" class="form-control rounded-pill py-2" value="50" required>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Purchase Price (৳)</label>
                <input type="number" name="purchase_price" step="0.01" class="form-control rounded-pill py-2" placeholder="0.00">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Regular Price (৳) *</label>
                <input type="number" name="regular_price" step="0.01" class="form-control rounded-pill py-2" placeholder="e.g. 50000" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Sale Discount Price (৳)</label>
                <input type="number" name="sale_price" step="0.01" class="form-control rounded-pill py-2" placeholder="e.g. 45000">
            </div>

            <!-- Product Image Upload Section -->
            <div class="col-md-12">
                <div class="p-4 border rounded-4 bg-light">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-images me-2"></i> Product Image Uploads (Up to 6 Images)</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Primary Thumbnail Image (File Upload)</label>
                            <input type="file" name="primary_image_file" class="form-control rounded-pill" accept="image/*">
                            <span class="small text-muted d-block mt-1">Or paste URL below:</span>
                            <input type="text" name="primary_image_url" class="form-control form-control-sm rounded-pill mt-1" placeholder="https://images.unsplash.com/...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Gallery Images (Upload up to 6 Image files)</label>
                            <input type="file" name="gallery_images[]" class="form-control rounded-pill" accept="image/*" multiple>
                            <span class="small text-muted d-block mt-1">Select up to 6 photos for image gallery zoom.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Color & Size Variants Section -->
            <div class="col-md-12">
                <div class="p-4 border rounded-4 bg-white">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-sliders me-2"></i> Color & Size Variant Matrix</h5>
                    <p class="small text-muted mb-3">Select available color swatches and size options. Customers can choose their variant during checkout.</p>
                    
                    <div class="row g-4">
                        <!-- Colors -->
                        <div class="col-md-6 border-end">
                            <label class="form-label small fw-bold text-uppercase">Available Colors</label>
                            <div class="vstack gap-2">
                                @foreach($colors as $color)
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-2" type="checkbox" name="colors[]" value="{{ $color->id }}" id="color_{{ $color->id }}">
                                        <span class="d-inline-block rounded-circle border me-2" style="width: 18px; height: 18px; background-color: {{ $color->code }};"></span>
                                        <label class="form-check-label small fw-semibold" for="color_{{ $color->id }}">
                                            {{ $color->name }} ({{ $color->code }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Inline Custom Color Creator -->
                            <div class="mt-3 p-3 bg-light rounded-3 border">
                                <label class="form-label small fw-bold text-primary mb-2"><i class="bi bi-plus-circle me-1"></i> Add Custom Color</label>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <input type="text" name="new_color_name" class="form-control form-control-sm rounded-pill" placeholder="Name e.g. Rose Gold">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex gap-1">
                                            <input type="color" class="form-control form-control-color form-control-sm rounded-3" value="#4f46e5" onchange="document.getElementById('new_color_code_create').value = this.value">
                                            <input type="text" name="new_color_code" id="new_color_code_create" class="form-control form-control-sm rounded-pill" placeholder="#4f46e5">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sizes -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase">Available Sizes / Specs</label>
                            <div class="vstack gap-2">
                                @foreach($sizes as $size)
                                    <div class="form-check">
                                        <input class="form-check-input me-2" type="checkbox" name="sizes[]" value="{{ $size->id }}" id="size_{{ $size->id }}">
                                        <label class="form-check-label small fw-semibold" for="size_{{ $size->id }}">
                                            {{ $size->name }} ({{ $size->code }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Inline Custom Size / Storage Creator -->
                            <div class="mt-3 p-3 bg-light rounded-3 border">
                                <label class="form-label small fw-bold text-primary mb-2"><i class="bi bi-plus-circle me-1"></i> Add Custom Size / Storage Option</label>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <input type="text" name="new_size_name" class="form-control form-control-sm rounded-pill" placeholder="Name e.g. 512GB / 16GB RAM">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="new_size_code" class="form-control form-control-sm rounded-pill" placeholder="Code e.g. 512-16">
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
                <textarea name="short_description" rows="2" class="form-control rounded-3" placeholder="Key feature highlights..."></textarea>
            </div>
            <div class="col-md-12">
                <label class="form-label fw-bold">Full Detailed Description</label>
                <textarea name="long_description" rows="4" class="form-control rounded-3" placeholder="Full product specifications..."></textarea>
            </div>

            <!-- Promotion Badges -->
            <div class="col-md-12">
                <div class="d-flex flex-wrap gap-4 p-3 bg-light rounded-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
                        <label class="form-check-label fw-bold text-success" for="is_active">Publish / Active Status</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" checked>
                        <label class="form-check-label fw-semibold" for="is_featured">Featured Product</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_flash_sale" value="1" id="is_flash_sale">
                        <label class="form-check-label text-danger fw-bold" for="is_flash_sale">Flash Sale Item</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_trending" value="1" id="is_trending">
                        <label class="form-check-label fw-semibold" for="is_trending">Trending Now</label>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold fs-6 shadow-sm">
            <i class="bi bi-cloud-upload me-2"></i> Publish Product with Uploaded Images & Variants
        </button>
    </form>
</div>
@endsection
