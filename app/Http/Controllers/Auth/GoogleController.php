<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)->first();
            if ($user) {
                Auth::login($user);
                return redirect()->intended('/dashboard')->with('success', 'Welcome back!');
            }

            if (User::where('email', $googleUser->email)->exists()) {
                return redirect('/login')->with('error', 'An account with this email already exists. Please login using your password.');
            }

            // Save Google info to session and start the 3-step registration
            session([
                'google_user' => [
                    'id' => $googleUser->id,
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'avatar' => $googleUser->avatar,
                ]
            ]);

            return redirect()->route('auth.complete.step1.form')
                ->with('message', 'Welcome! Please create your account credentials to begin.');

        } catch (Exception $e) {
            \Log::error('Google authentication error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'An error occurred during Google authentication. Please try again.');
        }
    }

    /**
     * Show registration form step 1: Set Username and Password.
     */
    public function showCompleteFormStep1()
    {
        if (!session('google_user')) {
            return redirect('/login')->with('error', 'Session expired. Please try again.');
        }
        return view('auth.register-step1');
    }

    /**
     * Process data from step 1, save to session, and redirect to step 2.
     */
    public function storeRegistrationStep1(Request $request)
    {
        if (!session('google_user')) {
            return redirect('/login')->with('error', 'Session expired. Please try again.');
        }

        $validatedData = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username', 'regex:/^[a-zA-Z0-9_]+$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'username.regex' => 'Username can only contain letters, numbers, and underscores.',
            'username.unique' => 'This username is already taken.',
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        // Save step 1 data to a structured session key
        session()->put('registration_data.step1', $validatedData);

        return redirect()->route('auth.complete.step2.form');
    }

    /**
     * Show registration form step 2: Personal Information.
     */
    public function showCompleteFormStep2()
    {
        if (!session('google_user') || !session('registration_data.step1')) {
            return redirect('/login')->with('error', 'Please complete step 1 first.');
        }
        return view('auth.register-step2');
    }

    /**
     * Process data from step 2, save to session, and redirect to step 3.
     */
    public function storeRegistrationStep2(Request $request)
    {
        if (!session('google_user') || !session('registration_data.step1')) {
            return redirect('/login')->with('error', 'Session expired. Please try again.');
        }

        $validatedData = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'phone' => ['nullable', 'string', 'max:20'],
        ], [
            'date_of_birth.before' => 'Please enter a valid birth date.',
        ]);

        // Save step 2 data to the session
        session()->put('registration_data.step2', $validatedData);

        return redirect()->route('auth.complete.step3.form');
    }

    /**
     * Show registration form step 3: Physical Details.
     */
    public function showCompleteFormStep3()
    {
        if (!session('google_user') || !session('registration_data.step1') || !session('registration_data.step2')) {
            return redirect('/login')->with('error', 'Please complete the previous steps first.');
        }
        return view('auth.register-step3');
    }

    /**
     * Process step 3 data and finalize registration by creating a new user.
     */
    public function finalizeRegistration(Request $request)
    {
        $googleUser = session('google_user');
        $regData = session('registration_data');

        if (!$googleUser || !$regData || !isset($regData['step1']) || !isset($regData['step2'])) {
            return redirect('/login')->with('error', 'Session expired. Please start over.');
        }

        $step3Data = $request->validate([
            'gender' => ['required', 'in:male,female,other'],
            'height' => ['nullable', 'numeric', 'min:100', 'max:250'],
            'weight' => ['nullable', 'numeric', 'min:30', 'max:300'],
            'activity_level' => ['nullable', 'in:sedentary,light,moderate,very_active,extremely_active'],
        ]);

        try {
            $user = User::create([
                'name' => $regData['step2']['first_name'] . ' ' . $regData['step2']['last_name'],
                'first_name' => $regData['step2']['first_name'],
                'last_name' => $regData['step2']['last_name'],
                'username' => $regData['step1']['username'],
                'email' => $googleUser['email'],
                'password' => Hash::make($regData['step1']['password']), // Hash and store the password
                'google_id' => $googleUser['id'],
                'date_of_birth' => $regData['step2']['date_of_birth'],
                'phone' => $regData['step2']['phone'] ?? null,
                'gender' => $step3Data['gender'],
                'height' => $step3Data['height'] ?? null,
                'weight' => $step3Data['weight'] ?? null,
                'activity_level' => $step3Data['activity_level'] ?? null,
                'profile_photo_path' => $googleUser['avatar'],
                'email_verified_at' => now(),
            ]);

            session()->forget(['google_user', 'registration_data']);

            Auth::login($user);
            return redirect('/dashboard')->with('success', 'Registration completed successfully! Welcome to RunMate!');

        } catch (Exception $e) {
            \Log::error('User creation error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while creating your account. Please try again.');
        }
    }

    /**
     * Handle manual login using email/username and password.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $login = $request->input('login');
        $password = $request->input('password');

        // Determine if login is email or username
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $field => $login,
            'password' => $password
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard')->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->onlyInput('login');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'You have been logged out successfully.');
    }
}