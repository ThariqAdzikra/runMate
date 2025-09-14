@extends('auth.template')

@section('title', 'Personal Info (Step 2 of 3) - RunMate')

@section('content')
<div class="auth-container" style="max-width: 520px;">
    <div class="logo">
        <h1>Almost Done!</h1>
        <p>Complete your personal information to continue</p>
    </div>

    {{-- Progress Bar --}}
    <div class="progress-container">
        <p class="progress-text">Step 2 of 3</p>
        <div class="progress-bar">
            <div class="progress-fill" style="width: 66%;"></div>
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

    <form method="POST" action="{{ route('auth.complete.step2.store') }}">
        @csrf
        
        <h3 class="section-title">Personal Information</h3>
        
        <div class="form-row">
            <div class="form-group">
                <label for="first_name" class="form-label">First Name *</label>
                <input type="text" id="first_name" name="first_name" class="form-input" 
                       placeholder="John" 
                       value="{{ old('first_name', session('google_user.name') ? explode(' ', session('google_user.name'))[0] : '') }}" 
                       required>
                @error('first_name')
                    <div style="color: #e74c3c; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="last_name" class="form-label">Last Name *</label>
                <input type="text" id="last_name" name="last_name" class="form-input" 
                       placeholder="Doe" 
                       value="{{ old('last_name', session('google_user.name') ? (explode(' ', session('google_user.name'), 2)[1] ?? '') : '') }}" 
                       required>
                @error('last_name')
                    <div style="color: #e74c3c; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="form-group">
            <label for="date_of_birth" class="form-label">Date of Birth *</label>
            <input type="date" id="date_of_birth" name="date_of_birth" class="form-input" 
                   value="{{ old('date_of_birth') }}" required>
            @error('date_of_birth')
                <div style="color: #e74c3c; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="phone" class="form-label">Phone Number (Optional)</label>
            <input type="tel" id="phone" name="phone" class="form-input" 
                   placeholder="+62 812 3456 7890" 
                   value="{{ old('phone') }}">
            @error('phone')
                <div style="color: #e74c3c; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <div class="button-group">
             <a href="{{ route('auth.complete.step1.form') }}" class="btn-secondary">Back</a>
            <button type="submit" class="btn-primary" style="flex: 1; margin: 0;">Continue to Step 3</button>
        </div>
    </form>
</div>
@endsection