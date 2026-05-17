<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = Str::lower($request->input('login')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors(['login' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."]);
        }

        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $field => $login,
            'password' => $request->input('password'),
        ];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 300);
            return back()->withErrors(['login' => 'Email/username atau password salah.'])->withInput(['login' => $login]);
        }

        if (Auth::user()->is_banned) {
            Auth::logout();
            return back()->withErrors(['login' => 'Akun kamu telah diblokir. Alasan: ' . (Auth::user()->ban_reason ?? 'Melanggar aturan.')]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();
        Auth::user()->updateLastSeen();

        return redirect()->intended(route('home'))->with('success', 'Selamat datang kembali, ' . Auth::user()->username . '!');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Kamu berhasil keluar.');
    }
}
