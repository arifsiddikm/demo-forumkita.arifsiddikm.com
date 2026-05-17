@extends('layouts.app')
@section('title', 'Reset Password - ForumKita')

@section('content')
<div style="min-height:70vh;display:flex;align-items:center;justify-content:center;padding:32px 16px;">
  <div style="width:100%;max-width:440px;">
    <div style="text-align:center;margin-bottom:32px;">
      <img src="{{ asset('images/logo.svg') }}" alt="ForumKita" style="height:48px;margin-bottom:16px;">
      <h1 style="font-size:1.5rem;font-weight:700;color:#1e293b;margin:0;">Reset Password</h1>
      <p style="color:#64748b;margin:8px 0 0;">Masukkan password baru Anda.</p>
    </div>

    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:28px;box-shadow:0 4px 24px rgba(0,0,0,0.06);">
      <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div style="margin-bottom:16px;">
          <label class="form-label">Email</label>
          <input type="email" name="email" value="{{ old('email', $email) }}" class="form-input" required>
          @error('email')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <div style="margin-bottom:16px;">
          <label class="form-label">Password Baru</label>
          <input type="password" name="password" class="form-input" required minlength="8">
          @error('password')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <div style="margin-bottom:24px;">
          <label class="form-label">Konfirmasi Password Baru</label>
          <input type="password" name="password_confirmation" class="form-input" required>
        </div>
        <button type="submit" class="btn-primary" style="width:100%;">
          <i class="fas fa-lock"></i> Reset Password
        </button>
      </form>
    </div>
  </div>
</div>
@endsection
