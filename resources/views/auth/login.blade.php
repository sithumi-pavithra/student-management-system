@extends('layouts.app')

@section('title', 'Login - Student Management')

@section('body-class', 'login-bg min-h-screen flex items-center justify-center p-4')

@section('hide-nav', true)

@section('content')
    <div class="login-card rounded-2xl shadow-2xl w-full max-w-md p-8">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fas fa-graduation-cap text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Student Management</h1>
            <p class="text-sm text-gray-500 mt-1">Sign in to your account</p>
        </div>

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded-lg mb-4 text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ $errors->first() }}
            </div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope text-indigo-500 mr-1"></i> Email Address
                </label>
                <input type="email" name="email" value="{{ old('email') }}" 
                       class="form-input w-full border border-gray-300 rounded-lg px-4 py-3 transition"
                       placeholder="Enter your email" required autofocus>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock text-indigo-500 mr-1"></i> Password
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                           class="form-input w-full border border-gray-300 rounded-lg px-4 py-3 pr-12 transition"
                           placeholder="Enter your password" required>
                    <button type="button" onclick="togglePassword()" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600">
                    <span>Remember Me</span>
                </label>
                <a href="#" class="text-sm text-indigo-600 hover:underline">Forgot Password?</a>
            </div>

            <button type="submit" 
                    class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white py-3 rounded-lg font-medium transition flex items-center justify-center gap-2 shadow-lg shadow-indigo-200">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        {{-- Footer --}}
        <div class="mt-8 pt-6 border-t border-gray-200 text-center">
            <p class="text-xs text-gray-400">© 2026 Student Management System</p>
            <p class="text-xs text-gray-400 mt-1">Secure System - Authorized Access Only</p>
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
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
@endpush