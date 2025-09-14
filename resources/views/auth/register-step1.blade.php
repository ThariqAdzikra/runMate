@extends('auth.template')

@section('title', 'Create Account (Step 1 of 3) - RunMate')

@section('content')
<div class="auth-container" style="max-width: 520px;">
    <div class="logo">
        <h1>Create Your Account</h1>
        <p>Set a unique username and a secure password.</p>
    </div>

    {{-- Progress Bar --}}
    <div class="progress-container">
        <p class="progress-text">Step 1 of 3</p>
        <div class="progress-bar">
            <div class="progress-fill" style="width: 33%;"></div>
        </div>
    </div>

    @if(session('error'))
        <div class="error-message">{{ session('error') }}</div>
    @endif
    
    @if(session('message'))
        <div class="success-message">{{ session('message') }}</div>
    @endif
    
    @if ($errors->any())
        <div class="error-message">
            <ul style="padding-left: 20px; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('auth.complete.step1.store') }}">
        @csrf
        
        <h3 class="section-title">Account Credentials</h3>
        
        <div class="form-group">
            <label for="username" class="form-label">Username *</label>
            <input type="text" id="username" name="username" class="form-input" 
                   placeholder="johndoe123" 
                   value="{{ old('username') }}" required>
            <small class="form-helper">This will be your unique identifier for logging in.</small>
            @error('username')
                <div style="color: #e74c3c; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password" class="form-label">Password *</label>
            <input type="password" id="password" name="password" class="form-input" 
                   placeholder="Enter a strong password" required>
            <small class="form-helper">Minimum 8 characters.</small>
            @error('password')
                <div style="color: #e74c3c; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirm Password *</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" 
                   placeholder="Confirm your password" required>
            @error('password_confirmation')
                <div style="color: #e74c3c; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-primary">Continue to Step 2</button>
    </form>
</div>
@endsection