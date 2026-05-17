@extends('layouts.app')
@section('title', 'Lupa Password - ForumKita')

@section('content')
<div style="min-height:70vh;display:flex;align-items:center;justify-content:center;padding:32px 16px;">
  <div style="width:100%;max-width:440px;">
    <div style="text-align:center;margin-bottom:32px;">
      <img src="{{ asset('images/logo.svg') }}" alt="ForumKita" style="height:48px;margin-bottom:16px;">
      <h1 style="font-size:1.5rem;font-weight:700;color:#1e293b;margin:0;">Lupa Password?</h1>
      <p style="color:#64748b;margin:8px 0 0;">Masukkan email Anda dan kami kirimkan link reset password.</p>
    </div>

    @if(session('success'))
      <div style="background:#d1fae5;border:1px solid #6ee7b7;color:#065f46;padding:14px 16px;border-radius:10px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:14px 16px;border-radius:10px;margin-bottom:20px;">
        {{ session('error') }}
      </div>
    @endif

    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:28px;box-shadow:0 4px 24px rgba(0,0,0,0.06);">
      <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div style="margin-bottom:20px;">
          <label class="form-label">Alamat Email</label>
          <input type="email" name="email" value="{{ old('email') }}" class="form-input" required autofocus placeholder="email@contoh.com">
          @error('email')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <button type="submit" class="btn-primary" style="width:100%;">
          <i class="fas fa-paper-plane"></i> Kirim Link Reset
        </button>
      </form>
    </div>

    <div style="text-align:center;margin-top:20px;">
      <a href="{{ route('login') }}" style="color:#2563EB;text-decoration:none;font-size:0.9rem;">
        <i class="fas fa-arrow-left"></i> Kembali ke Login
      </a>
    </div>
  </div>
</div>
@endsection
