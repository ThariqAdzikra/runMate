@extends('auth.template')

@section('title', 'Login - RunMate')

@section('content')
<div class="auth-container" style="max-width: 420px;">
    <div class="logo">
        <h1>Welcome Back</h1>
        <p>Sign in to your account</p>
    </div>

    @if(session('error'))
        <div class="error-message">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="login" class="form-label">Email or Username</label>
            <input type="text" id="login" name="login" class="form-input"
                   placeholder="Enter your email or username"
                   value="{{ old('login') }}" required>
            @error('login')
                <div style="color: #ff6b6b; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-input"
                   placeholder="Enter your password" required>
            <div style="text-align: right; margin-top: 8px;">
                <a href="{{ route('password.request') }}" class="forgot-password">
                    Forgot Password?
                </a>
            </div>
            @error('password')
                <div style="color: #ff6b6b; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-primary">Sign In</button>
    </form>

    <div class="divider">
        <div class="divider-line"></div>
        <span class="divider-text">or</span>
        <div class="divider-line"></div>
    </div>

    <a href="{{ route('auth.google') }}" class="google-btn">
        <svg style="width: 20px; height: 20px; margin-right: 10px;" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
        </svg>
        Continue with Google
    </a>

</div>
@endsection