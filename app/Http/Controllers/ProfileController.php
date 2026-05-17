<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $threads = $user->threads()->with('category')->latest()->paginate(10);
        $replies = $user->replies()->with('thread')->latest()->paginate(10);
        $badges  = $user->badges ?? collect();

        return view('profile.show', compact('user', 'threads', 'replies', 'badges'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'username'  => 'required|string|max:50|alpha_dash|unique:users,username,' . $user->id,
            'gender'    => 'nullable|in:male,female,other',
            'bio'       => 'nullable|string|max:500',
            'location'  => 'nullable|string|max:100',
            'website'   => 'nullable|url|max:255',
            'signature' => 'nullable|string|max:300',
        ]);

        $user->update($validated);

        return redirect()->route('profile.show', $user->username)
            ->with('success', 'Profil berhasil diperbarui!');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return redirect()->route('profile.edit')->with('success', 'Avatar berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak cocok.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('profile.edit')->with('success', 'Password berhasil diubah!');
    }
}
