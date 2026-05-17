@extends('layouts.app')
@section('title', 'Edit Profil - ForumKita')

@section('content')
<div style="max-width:760px;margin:32px auto;padding:0 16px;">
  <h1 style="font-size:1.5rem;font-weight:700;color:#1e293b;margin-bottom:24px;"><i class="fas fa-user-edit" style="color:#2563EB;"></i> Edit Profil</h1>

  {{-- Tabs --}}
  <div style="display:flex;gap:4px;background:#f1f5f9;padding:4px;border-radius:10px;margin-bottom:24px;">
    <button onclick="switchTab('profil')" id="btn-profil" style="flex:1;padding:10px;border-radius:8px;border:none;cursor:pointer;font-weight:600;font-size:0.875rem;background:#2563EB;color:#fff;">Informasi Profil</button>
    <button onclick="switchTab('avatar')" id="btn-avatar" style="flex:1;padding:10px;border-radius:8px;border:none;cursor:pointer;font-weight:500;font-size:0.875rem;background:transparent;color:#64748b;">Foto Profil</button>
    <button onclick="switchTab('password')" id="btn-password" style="flex:1;padding:10px;border-radius:8px;border:none;cursor:pointer;font-weight:500;font-size:0.875rem;background:transparent;color:#64748b;">Keamanan</button>
  </div>

  @if(session('success'))
    <div style="background:#d1fae5;border:1px solid #6ee7b7;color:#065f46;padding:14px 16px;border-radius:10px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
      <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
  @endif

  {{-- Profile Info Tab --}}
  <div id="tab-profil">
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:28px;">
      <form method="POST" action="{{ route('profile.update') }}">
        @csrf @method('PUT')
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
          <div>
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
            @error('name')<span class="form-error">{{ $message }}</span>@enderror
          </div>
          <div>
            <label class="form-label">Username</label>
            <div style="position:relative;">
              <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;">@</span>
              <input type="text" name="username" value="{{ old('username', $user->username) }}" class="form-input" style="padding-left:28px;" required>
            </div>
            @error('username')<span class="form-error">{{ $message }}</span>@enderror
          </div>
        </div>

        <div style="margin-bottom:16px;">
          <label class="form-label">Bio</label>
          <textarea name="bio" rows="3" class="form-input" style="resize:vertical;" placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio', $user->bio) }}</textarea>
          @error('bio')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
          <div>
            <label class="form-label">Lokasi</label>
            <input type="text" name="location" value="{{ old('location', $user->location) }}" class="form-input" placeholder="Jakarta, Indonesia">
            @error('location')<span class="form-error">{{ $message }}</span>@enderror
          </div>
          <div>
            <label class="form-label">Website</label>
            <input type="url" name="website" value="{{ old('website', $user->website) }}" class="form-input" placeholder="https://...">
            @error('website')<span class="form-error">{{ $message }}</span>@enderror
          </div>
        </div>

        <div style="margin-bottom:16px;">
          <label class="form-label">Gender</label>
          <div style="display:flex;gap:20px;margin-top:8px;">
            @foreach(['male' => 'Laki-laki', 'female' => 'Perempuan', 'other' => 'Lainnya'] as $val => $label)
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
              <input type="radio" name="gender" value="{{ $val }}" {{ old('gender', $user->gender) === $val ? 'checked' : '' }} class="form-radio">
              <span style="color:#374151;font-size:0.9rem;">{{ $label }}</span>
            </label>
            @endforeach
          </div>
        </div>

        <div style="margin-bottom:20px;">
          <label class="form-label">Signature</label>
          <textarea name="signature" rows="2" class="form-input" style="resize:vertical;" placeholder="Tanda tangan di setiap post Anda...">{{ old('signature', $user->signature) }}</textarea>
        </div>

        <button type="submit" style="display:inline-flex;align-items:center;gap:8px;padding:10px 24px;background:#2563EB;color:white;border:none;border-radius:10px;font-weight:600;font-size:0.9rem;cursor:pointer;transition:background 0.2s;" onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563EB'"><i class="fas fa-save"></i> Simpan Perubahan</button>
      </form>
    </div>
  </div>

  {{-- Avatar Tab --}}
  <div id="tab-avatar" style="display:none;">
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:28px;text-align:center;">
      <div style="margin-bottom:24px;">
        @if($user->avatar)
          <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:4px solid #e2e8f0;">
        @else
          <div style="width:120px;height:120px;border-radius:50%;background:linear-gradient(135deg,#2563EB,#EAB308);display:flex;align-items:center;justify-content:center;font-size:3rem;color:#fff;font-weight:700;margin:0 auto;border:4px solid #e2e8f0;">
            {{ strtoupper(substr($user->name,0,1)) }}
          </div>
        @endif
      </div>
      <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
        @csrf
        <div style="margin-bottom:20px;">
          <label class="form-label" style="display:block;margin-bottom:8px;">Upload Foto Baru (max 2MB, JPG/PNG)</label>
          <input type="file" name="avatar" accept="image/*" class="form-input" style="padding:10px;">
          @error('avatar')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <button type="submit" style="display:inline-flex;align-items:center;gap:8px;padding:10px 24px;background:#2563EB;color:white;border:none;border-radius:10px;font-weight:600;font-size:0.9rem;cursor:pointer;" onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563EB'"><i class="fas fa-upload"></i> Upload Avatar</button>
      </form>
    </div>
  </div>

  {{-- Password Tab --}}
  <div id="tab-password" style="display:none;">
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:28px;">
      <form method="POST" action="{{ route('profile.password') }}">
        @csrf @method('PUT')
        <div style="margin-bottom:16px;">
          <label class="form-label">Password Saat Ini</label>
          <input type="password" name="current_password" class="form-input" required>
          @error('current_password')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <div style="margin-bottom:16px;">
          <label class="form-label">Password Baru</label>
          <input type="password" name="password" class="form-input" required>
          @error('password')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <div style="margin-bottom:20px;">
          <label class="form-label">Konfirmasi Password Baru</label>
          <input type="password" name="password_confirmation" class="form-input" required>
        </div>
        <button type="submit" style="display:inline-flex;align-items:center;gap:8px;padding:10px 24px;background:#2563EB;color:white;border:none;border-radius:10px;font-weight:600;font-size:0.9rem;cursor:pointer;" onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563EB'"><i class="fas fa-lock"></i> Ubah Password</button>
      </form>
    </div>
  </div>
</div>

<script>
function switchTab(tab) {
  ['profil','avatar','password'].forEach(t => {
    document.getElementById('tab-' + t).style.display = t === tab ? 'block' : 'none';
    const btn = document.getElementById('btn-' + t);
    if (t === tab) {
      btn.style.cssText = 'flex:1;padding:10px;border-radius:8px;border:none;cursor:pointer;font-weight:600;font-size:0.875rem;background:#2563EB;color:#fff;';
    } else {
      btn.style.cssText = 'flex:1;padding:10px;border-radius:8px;border:none;cursor:pointer;font-weight:500;font-size:0.875rem;background:transparent;color:#64748b;';
    }
  });
}
</script>
@endsection
