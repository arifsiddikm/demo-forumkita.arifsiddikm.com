@extends('layouts.app')
@section('title', 'Kontak - ForumKita')
@section('content')
<div style="max-width:680px;margin:40px auto;padding:0 16px;">
  <div style="text-align:center;margin-bottom:32px;">
    <h1 style="font-size:1.75rem;font-weight:800;color:#1e293b;margin:0;"><i class="fas fa-envelope" style="color:#2563EB;"></i> Hubungi Kami</h1>
    <p style="color:#64748b;margin:8px 0 0;">Ada pertanyaan atau laporan? Kirim pesan kepada kami.</p>
  </div>
  @if(session('success'))
    <div style="background:#d1fae5;border:1px solid #6ee7b7;color:#065f46;padding:14px 16px;border-radius:10px;margin-bottom:20px;display:flex;gap:10px;align-items:center;"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:14px 16px;border-radius:10px;margin-bottom:20px;">{{ session('error') }}</div>
  @endif
  <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:32px;">
    <form method="POST" action="{{ route('contact.send') }}">
      @csrf
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
        <div><label class="form-label">Nama <span style="color:#ef4444;">*</span></label><input type="text" name="name" value="{{ old('name') }}" class="form-input" required>@error('name')<span class="form-error">{{ $message }}</span>@enderror</div>
        <div><label class="form-label">Email <span style="color:#ef4444;">*</span></label><input type="email" name="email" value="{{ old('email') }}" class="form-input" required>@error('email')<span class="form-error">{{ $message }}</span>@enderror</div>
      </div>
      <div style="margin-bottom:16px;"><label class="form-label">Subjek <span style="color:#ef4444;">*</span></label><input type="text" name="subject" value="{{ old('subject') }}" class="form-input" required>@error('subject')<span class="form-error">{{ $message }}</span>@enderror</div>
      <div style="margin-bottom:20px;"><label class="form-label">Pesan <span style="color:#ef4444;">*</span></label><textarea name="message" rows="5" class="form-input" style="resize:vertical;" required>{{ old('message') }}</textarea>@error('message')<span class="form-error">{{ $message }}</span>@enderror</div>
      <button type="submit" class="btn-primary" style="width:100%;"><i class="fas fa-paper-plane"></i> Kirim Pesan</button>
    </form>
  </div>
</div>
@endsection
