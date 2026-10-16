extends('layouts.app')

@section('title', 'Login - Student Management System')

@section('body-class', 'login-bg d-flex align-items-center justify-content-center p-3')

@section('hide-nav', true)

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-6 col-lg-4">

                <div class="login-card p-4 p-md-5">

                    
                    <div class="text-center mb-4">
                        <div class="logo-box d-flex align-items-center justify-content-center mx-auto mb-3">
                            <i class="fas fa-graduation-cap text-white fs-1"></i>
                        </div>
                        <h1 class="h4 fw-bold text-dark mb-1">Student Management</h1>
                        <p class="text-muted small mb-0">Sign in to your account</p>
                    </div>

                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    
                    <form method="POST" action="{{ route('login.submit') }}">
                        @csrf

                        
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                <i class="fas fa-envelope text-primary me-1"></i> Email Address
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email"
                                   value="{{ old('email') }}" 
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="Enter your email"
                                   required 
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                       
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">
                                <i class="fas fa-lock text-primary me-1"></i> Password
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       name="password" 
                                       id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Enter your password"
                                       required>
                                <button type="button" 
                                        class="btn btn-outline-secondary"
                                        onclick="togglePassword()">
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                       
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" 
                                       name="remember" 
                                       id="remember"
                                       class="form-check-input">
                                <label for="remember" class="form-check-label small">
                                    Remember Me
                                </label>
                            </div>
                            <a href="#" class="small text-primary text-decoration-none">
                                Forgot Password?
                            </a>
                        </div>

                        
                        <button type="submit" class="btn btn-login w-100 py-2 fw-semibold">
                            <i class="fas fa-sign-in-alt me-2"></i> Sign In
                        </button>

                    </form>

                    
                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="text-muted small mb-1">© 2026 Student Management System</p>
                        <p class="text-muted small mb-0">
                            <i class="fas fa-shield-alt me-1"></i> Secure System - Authorized Access Only
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function togglePassword() {
        const password = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');

        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endpush