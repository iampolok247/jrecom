@extends('layouts.admin')

@section('page_title', 'Homepage Builder & Section Manager')

@section('content')
<div class="row g-4 mb-4">
    <!-- Homepage Sections Orderer -->
    <div class="col-lg-6">
        <div class="admin-card p-4">
            <h5 class="fw-bold mb-3">Homepage Sections Visibility & Display Order</h5>
            <p class="small text-muted mb-4">Enable, disable, or change the layout order of any homepage section.</p>

            <form action="{{ route('admin.homepage.update_sections') }}" method="POST">
                @csrf
                <div class="vstack gap-3 mb-4">
                    @foreach($sections as $sec)
                        <div class="p-3 border rounded-4 d-flex align-items-center justify-content-between bg-light">
                            <div class="d-flex align-items-center">
                                <div class="form-check form-switch me-3">
                                    <input class="form-check-input" type="checkbox" name="sections[{{ $sec->id }}][is_enabled]" id="sec_{{ $sec->id }}" {{ $sec->is_enabled ? 'checked' : '' }}>
                                </div>
                                <div>
                                    <h6 class="fw-bold m-0 text-dark">{{ $sec->title }}</h6>
                                    <span class="small text-muted">Key: <code>{{ $sec->key }}</code></span>
                                </div>
                            </div>
                            <div style="width: 80px;">
                                <input type="number" name="sections[{{ $sec->id }}][order]" value="{{ $sec->order }}" class="form-control form-control-sm text-center rounded-pill">
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Save Section Order</button>
            </form>
        </div>
    </div>

    <!-- Hero Banners & Slider Manager -->
    <div class="col-lg-6">
        <div class="admin-card p-4">
            <h5 class="fw-bold mb-3">Add Hero Slider Banner</h5>
            <form action="{{ route('admin.homepage.store_banner') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                @csrf
                <input type="hidden" name="section" value="hero_slider">
                <div class="mb-2">
                    <label class="form-label small fw-bold">Banner Headline Title</label>
                    <input type="text" name="title" class="form-control form-control-sm rounded-pill" placeholder="e.g. Summer Mega Sale">
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold">Subtitle / Promo Tagline</label>
                    <input type="text" name="subtitle" class="form-control form-control-sm rounded-pill" placeholder="e.g. Save up to 50%">
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold">Banner Image File Upload</label>
                    <input type="file" name="image_file" class="form-control form-control-sm rounded-pill" accept="image/*">
                    <span class="small text-muted d-block mt-1">Or paste URL below:</span>
                    <input type="text" name="image" class="form-control form-control-sm rounded-pill mt-1" placeholder="https://images.unsplash.com/...">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Target Link URL</label>
                    <input type="text" name="link" class="form-control form-control-sm rounded-pill" placeholder="/shop?category=electronics">
                </div>
                <button type="submit" class="btn btn-dark btn-sm rounded-pill px-4 fw-bold">Upload Slide</button>
            </form>

            <h6 class="fw-bold mb-3 border-top pt-3">Existing Hero Sliders</h6>
            <div class="vstack gap-2">
                @foreach($sliders as $slide)
                    <div class="d-flex align-items-center justify-content-between p-2 border rounded-3 bg-white">
                        <div class="d-flex align-items-center">
                            <img src="{{ $slide->image }}" width="60" height="40" class="rounded object-fit-cover me-2">
                            <div>
                                <div class="fw-bold small text-dark">{{ $slide->title }}</div>
                            </div>
                        </div>
                        <form action="{{ route('admin.homepage.destroy_banner', $slide->id) }}" method="POST" onsubmit="return confirm('Delete banner?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger border-0"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
