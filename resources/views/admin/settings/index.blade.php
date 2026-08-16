@extends('layouts.admin')

@section('page_title', 'Site Settings & Branding Uploads')

@section('content')
<div class="admin-card p-4 p-md-5 max-w-4xl mx-auto shadow-sm">
    <h4 class="fw-bold mb-3 border-bottom pb-3">Global Site Configuration & Branding Uploads</h4>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Storefront Branding & Logo File Uploads -->
        <h5 class="fw-bold text-primary mb-3"><i class="bi bi-image me-1"></i> Storefront Logo & Favicon Uploads</h5>
        <div class="row g-4 mb-4 p-4 border rounded-4 bg-light">
            <!-- Main Navbar Logo Upload -->
            <div class="col-md-4 border-end">
                <label class="form-label small fw-bold">Header Navbar Logo</label>
                <input type="file" name="site_logo_file" class="form-control rounded-pill" accept="image/*">
                <span class="small text-muted d-block mt-1">Or paste URL below:</span>
                <input type="text" name="site_logo" value="{{ $settings['site_logo'] ?? '' }}" class="form-control form-control-sm rounded-pill mt-1" placeholder="https://...">
                @if(!empty($settings['site_logo']))
                    <div class="mt-2">
                        <span class="small text-muted d-block">Navbar Logo Preview:</span>
                        <img src="{{ $settings['site_logo'] }}" height="40" class="object-fit-contain bg-white p-1 border rounded mt-1">
                    </div>
                @endif
            </div>

            <!-- Footer / Dark Logo Upload -->
            <div class="col-md-4 border-end">
                <label class="form-label small fw-bold">Footer Brand Logo</label>
                <input type="file" name="site_dark_logo_file" class="form-control rounded-pill" accept="image/*">
                <span class="small text-muted d-block mt-1">Or paste URL below:</span>
                <input type="text" name="site_dark_logo" value="{{ $settings['site_dark_logo'] ?? '' }}" class="form-control form-control-sm rounded-pill mt-1" placeholder="https://...">
                @if(!empty($settings['site_dark_logo']))
                    <div class="mt-2">
                        <span class="small text-muted d-block">Footer Logo Preview:</span>
                        <img src="{{ $settings['site_dark_logo'] }}" height="40" class="object-fit-contain bg-dark p-1 border rounded mt-1">
                    </div>
                @endif
            </div>

            <!-- Favicon Upload -->
            <div class="col-md-4">
                <label class="form-label small fw-bold">Favicon Icon</label>
                <input type="file" name="site_favicon_file" class="form-control rounded-pill" accept="image/*">
                <span class="small text-muted d-block mt-1">Or paste URL below:</span>
                <input type="text" name="site_favicon" value="{{ $settings['site_favicon'] ?? '' }}" class="form-control form-control-sm rounded-pill mt-1" placeholder="https://...">
                @if(!empty($settings['site_favicon']))
                    <div class="mt-2">
                        <span class="small text-muted d-block">Favicon Preview:</span>
                        <img src="{{ $settings['site_favicon'] }}" height="32" width="32" class="object-fit-contain bg-white p-1 border rounded mt-1">
                    </div>
                @endif
            </div>
        </div>

        <!-- Storefront Identity Details -->
        <h5 class="fw-bold text-primary mb-3"><i class="bi bi-shop me-1"></i> General Information</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label small fw-bold">Site Name *</label>
                <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'JR-Ecom' }}" class="form-control rounded-pill">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold">Browser Tab Title</label>
                <input type="text" name="site_title" value="{{ $settings['site_title'] ?? '' }}" class="form-control rounded-pill">
            </div>
            <div class="col-md-12">
                <label class="form-label small fw-bold">Tagline</label>
                <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] ?? '' }}" class="form-control rounded-pill">
            </div>
        </div>

        <!-- Color Scheme & Theme -->
        <h5 class="fw-bold text-primary mb-3 border-top pt-3"><i class="bi bi-palette me-1"></i> Dynamic Colors & Appearance</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Primary Brand Color</label>
                <input type="color" name="primary_color" value="{{ $settings['primary_color'] ?? '#4f46e5' }}" class="form-control form-control-color w-100 rounded-3">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">Secondary Color</label>
                <input type="color" name="secondary_color" value="{{ $settings['secondary_color'] ?? '#06b6d4' }}" class="form-control form-control-color w-100 rounded-3">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">Card Border Radius</label>
                <input type="text" name="border_radius" value="{{ $settings['border_radius'] ?? '16px' }}" class="form-control rounded-pill" placeholder="e.g. 16px">
            </div>
        </div>

        <!-- Contact Info -->
        <h5 class="fw-bold text-primary mb-3 border-top pt-3"><i class="bi bi-telephone me-1"></i> Contact & Support Details</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label small fw-bold">Support Phone Number</label>
                <input type="text" name="support_phone" value="{{ $settings['support_phone'] ?? '' }}" class="form-control rounded-pill">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold">Support Email</label>
                <input type="email" name="support_email" value="{{ $settings['support_email'] ?? '' }}" class="form-control rounded-pill">
            </div>
            <div class="col-md-12">
                <label class="form-label small fw-bold">Office Address</label>
                <input type="text" name="office_address" value="{{ $settings['office_address'] ?? '' }}" class="form-control rounded-pill">
            </div>
        </div>

        <!-- SEO Metadata -->
        <h5 class="fw-bold text-primary mb-3 border-top pt-3"><i class="bi bi-search me-1"></i> SEO Meta Information</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label small fw-bold">Meta Title</label>
                <input type="text" name="seo_meta_title" value="{{ $settings['seo_meta_title'] ?? '' }}" class="form-control rounded-pill">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold">Meta Keywords</label>
                <input type="text" name="seo_meta_keywords" value="{{ $settings['seo_meta_keywords'] ?? '' }}" class="form-control rounded-pill">
            </div>
            <div class="col-md-12">
                <label class="form-label small fw-bold">Meta Description</label>
                <textarea name="seo_meta_description" rows="2" class="form-control rounded-3">{{ $settings['seo_meta_description'] ?? '' }}</textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-sm">
            <i class="bi bi-cloud-upload me-1"></i> Save Settings & Uploaded Logos
        </button>
    </form>
</div>
@endsection
