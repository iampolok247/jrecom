@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card-custom p-4 p-md-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-dark mb-1">Welcome Back</h3>
                    <p class="text-muted small">Sign in to your JR-Ecom customer / admin account</p>
                </div>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control rounded-pill" placeholder="name@example.com" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password</label>
                        <input type="password" name="password" class="form-control rounded-pill" placeholder="••••••••" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label small" for="remember">Remember me</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-gradient w-100 rounded-pill py-2">Sign In</button>
                </form>

                <div class="text-center mt-4">
                    <span class="small text-muted">Don't have an account? <a href="{{ route('register') }}" class="fw-bold text-primary">Register Here</a></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
