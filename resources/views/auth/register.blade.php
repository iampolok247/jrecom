@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card-custom p-4 p-md-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-dark mb-1">Create Account</h3>
                    <p class="text-muted small">Join JR-Ecom for exclusive discounts and order tracking</p>
                </div>

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control rounded-pill" placeholder="John Doe" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control rounded-pill" placeholder="name@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Phone Number *</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control rounded-pill" placeholder="+8801700000000" required>
                    </div>
                    <div class="row g-2 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Password *</label>
                            <input type="password" name="password" class="form-control rounded-pill" placeholder="Min 8 chars" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Confirm Password *</label>
                            <input type="password" name="password_confirmation" class="form-control rounded-pill" placeholder="Confirm password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-gradient w-100 rounded-pill py-2">Create Account</button>
                </form>

                <div class="text-center mt-4">
                    <span class="small text-muted">Already have an account? <a href="{{ route('login') }}" class="fw-bold text-primary">Sign In</a></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
