@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card-custom p-3">
                <div class="nav flex-column nav-pills">
                    <a href="{{ route('user.dashboard') }}" class="nav-link text-dark rounded-pill mb-1 fw-bold"><i class="bi bi-grid me-2"></i> Dashboard Overview</a>
                    <a href="{{ route('user.orders') }}" class="nav-link text-dark rounded-pill mb-1 fw-bold"><i class="bi bi-bag-check me-2"></i> My Orders</a>
                    <a href="{{ route('user.wishlist') }}" class="nav-link text-dark rounded-pill mb-1 fw-bold"><i class="bi bi-heart me-2"></i> Wishlist</a>
                    <a href="{{ route('user.profile') }}" class="nav-link active rounded-pill mb-1 fw-bold"><i class="bi bi-person-gear me-2"></i> Profile & Address</a>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card-custom p-4">
                <h5 class="fw-bold mb-4">Edit Profile & Address Book</h5>

                <form action="{{ route('user.profile.update') }}" method="POST">
                    @csrf
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control rounded-pill" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Phone Number *</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control rounded-pill" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">City</label>
                            <input type="text" name="city" value="{{ old('city', $user->city) }}" class="form-control rounded-pill">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">ZIP Code</label>
                            <input type="text" name="zip" value="{{ old('zip', $user->zip) }}" class="form-control rounded-pill">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Street Address</label>
                            <textarea name="address" rows="2" class="form-control rounded-3">{{ old('address', $user->address) }}</textarea>
                        </div>
                    </div>

                    <h6 class="fw-bold border-top pt-3 mb-3">Change Password (Optional)</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">New Password</label>
                            <input type="password" name="password" class="form-control rounded-pill" placeholder="Leave empty to keep current">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control rounded-pill" placeholder="Confirm new password">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-gradient rounded-pill px-4">Save Profile Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
