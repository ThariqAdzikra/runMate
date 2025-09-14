@extends('auth.template')

@section('title', 'Physical Details (Step 3 of 3) - RunMate')

@section('content')
<div class="auth-container" style="max-width: 520px;">
    <div class="logo">
        <h1>Final Step!</h1>
        <p>This information helps personalize your experience</p>
    </div>

    {{-- Progress Bar --}}
    <div class="progress-container">
        <p class="progress-text">Step 3 of 3</p>
        <div class="progress-bar">
            <div class="progress-fill" style="width: 100%;"></div>
        </div>
    </div>

    @if(session('error'))
        <div class="error-message">{{ session('error') }}</div>
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

    <form method="POST" action="{{ route('auth.complete.step3.finalize') }}">
        @csrf
        
        <h3 class="section-title">Physical Details</h3>
        
        <div class="form-group">
            <label for="gender" class="form-label">Gender *</label>
            <select id="gender" name="gender" class="form-select" required>
                <option value="">Select Gender</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('gender')
                <div style="color: #e74c3c; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="height" class="form-label">Height (cm)</label>
                <input type="number" id="height" name="height" class="form-input" 
                       placeholder="170" min="100" max="250" 
                       value="{{ old('height') }}">
                @error('height')
                    <div style="color: #e74c3c; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="weight" class="form-label">Weight (kg)</label>
                <input type="number" id="weight" name="weight" class="form-input" 
                       placeholder="70" min="30" max="300" 
                       value="{{ old('weight') }}">
                @error('weight')
                    <div style="color: #e74c3c; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="form-group">
            <label for="activity_level" class="form-label">Activity Level</label>
            <select id="activity_level" name="activity_level" class="form-select">
                <option value="">Select Activity Level</option>
                <option value="sedentary" {{ old('activity_level') == 'sedentary' ? 'selected' : '' }}>
                    Sedentary (little/no exercise)
                </option>
                <option value="light" {{ old('activity_level') == 'light' ? 'selected' : '' }}>
                    Light (exercise 1-3 days/week)
                </option>
                <option value="moderate" {{ old('activity_level') == 'moderate' ? 'selected' : '' }}>
                    Moderate (exercise 3-5 days/week)
                </option>
                <option value="very_active" {{ old('activity_level') == 'very_active' ? 'selected' : '' }}>
                    Very Active (exercise 6-7 days/week)
                </option>
                <option value="extremely_active" {{ old('activity_level') == 'extremely_active' ? 'selected' : '' }}>
                    Extremely Active (heavy exercise, physical job)
                </option>
            </select>
            @error('activity_level')
                <div style="color: #e74c3c; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <div class="button-group">
            <a href="{{ route('auth.complete.step2.form') }}" class="btn-secondary">Back</a>
            <button type="submit" class="btn-primary" style="flex: 1; margin: 0;">
                Complete Registration
            </button>
        </div>
    </form>
</div>
@endsection