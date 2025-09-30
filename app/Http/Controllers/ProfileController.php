<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the user's profile page.
     */
    public function show()
    {
        // PERBAIKAN: Menunjuk ke view yang benar di dalam folder layouts
        // Menggunakan 'layouts.profile' karena file Anda bernama profile.blade.php
        return view('layouts.profile');
    }

    /**
     * Update the user's profile, specifically the profile photo.
     */
    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Hapus foto lama jika ada
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Simpan foto baru dan dapatkan path-nya
        $path = $request->file('profile_photo')->store('profile-photos', 'public');

        // Update path foto di database
        $user->profile_photo_path = $path;
        $user->save();

        // Redirect kembali dengan pesan sukses
        return back()->with('success', 'Profile photo updated successfully!');
    }
}

