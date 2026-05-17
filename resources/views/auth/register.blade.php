@extends('layouts.app')
@section('title', 'Daftar Akun ForumKita')
@section('meta_description', 'Bergabunglah dengan ForumKita - Forum diskusi terbesar di Indonesia.')

@section('content')
<div style="max-width:500px;margin:0 auto;padding:20px 0;">

    <div style="text-align:center;margin-bottom:32px;">
        <img src="{{ asset('images/logo.svg') }}" alt="ForumKita" style="height:48px;margin-bottom:16px;">
        <h1 style="font-size:1.6rem;font-weight:800;color:var(--fk-gray-900);margin:0 0 6px;">Buat Akun Baru</h1>
        <p style="color:var(--fk-gray-500);font-size:0.9rem;">Gratis selamanya. Bergabung sekarang!</p>
    </div>

    <div class="card">
        <div class="card-body" style="padding:32px;">

            @if($errors->any())
            <div class="alert alert-danger">
                <i class="fa fa-circle-exclamation" style="flex-shrink:0;"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" for="name">Nama Lengkap</label>
                        <input type="text" name="name" id="name" class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Nama kamu" value="{{ old('name') }}" required>
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" for="username">Username</label>
                        <div style="position:relative;">
                            <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--fk-gray-400);font-size:0.9rem;">@</span>
                            <input type="text" name="username" id="username" class="form-input {{ $errors->has('username') ? 'is-invalid' : '' }}" style="padding-left:32px;" placeholder="username" value="{{ old('username') }}" required pattern="[a-zA-Z0-9_]{3,20}">
                        </div>
                        @error('username') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div style="height:14px;"></div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <div style="position:relative;">
                        <i class="fa fa-envelope" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--fk-gray-400);"></i>
                        <input type="email" name="email" id="email" class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}" style="padding-left:40px;" placeholder="email@kamu.com" value="{{ old('email') }}" required>
                    </div>
                    @error('email') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div style="position:relative;">
                        <i class="fa fa-lock" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--fk-gray-400);"></i>
                        <input type="password" name="password" id="password" class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}" style="padding-left:40px;padding-right:44px;" placeholder="Min. 8 karakter" required>
                        <button type="button" onclick="togglePass('password')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--fk-gray-400);"><i class="fa fa-eye" id="eye1"></i></button>
                    </div>
                    {{-- Password strength bar --}}
                    <div style="margin-top:6px;">
                        <div style="height:4px;background:var(--fk-gray-200);border-radius:2px;overflow:hidden;">
                            <div id="strengthBar" style="height:100%;width:0;border-radius:2px;transition:all 0.3s;"></div>
                        </div>
                        <div id="strengthText" style="font-size:0.72rem;color:var(--fk-gray-400);margin-top:3px;"></div>
                    </div>
                    @error('password') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                    <div style="position:relative;">
                        <i class="fa fa-lock" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--fk-gray-400);"></i>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" style="padding-left:40px;padding-right:44px;" placeholder="Ulangi password" required>
                        <button type="button" onclick="togglePass('password_confirmation')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--fk-gray-400);"><i class="fa fa-eye" id="eye2"></i></button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Kelamin</label>
                    <div style="display:flex;gap:16px;margin-top:4px;">
                        <label class="form-check">
                            <input type="radio" name="gender" value="male" {{ old('gender','male') === 'male' ? 'checked' : '' }}>
                            <label>Laki-laki</label>
                        </label>
                        <label class="form-check">
                            <input type="radio" name="gender" value="female" {{ old('gender') === 'female' ? 'checked' : '' }}>
                            <label>Perempuan</label>
                        </label>
                        <label class="form-check">
                            <input type="radio" name="gender" value="other" {{ old('gender') === 'other' ? 'checked' : '' }}>
                            <label>Lainnya</label>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="agree_tos" id="agree_tos" required {{ old('agree_tos') ? 'checked' : '' }}>
                        <label for="agree_tos" style="font-size:0.875rem;">
                            Saya menyetujui <a href="{{ route('tos') }}" target="_blank" style="color:var(--fk-blue);font-weight:700;">Syarat & Ketentuan</a> dan <a href="{{ route('privacy') }}" target="_blank" style="color:var(--fk-blue);font-weight:700;">Kebijakan Privasi</a> ForumKita
                        </label>
                    </label>
                    @error('agree_tos') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px;">
                    <i class="fa fa-user-plus"></i> Buat Akun Sekarang
                </button>
            </form>

            <div style="text-align:center;margin-top:20px;padding-top:20px;border-top:1px solid var(--fk-gray-100);">
                <p style="font-size:0.875rem;color:var(--fk-gray-600);">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" style="color:var(--fk-blue);font-weight:700;text-decoration:none;">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePass(id) {
    const inp = document.getElementById(id);
    inp.type = inp.type === 'password' ? 'text' : 'password';
}
// Password strength
document.getElementById('password').addEventListener('input', function() {
    const val = this.value;
    const bar = document.getElementById('strengthBar');
    const txt = document.getElementById('strengthText');
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const levels = [
        { pct: '0%', color: '', label: '' },
        { pct: '25%', color: '#EF4444', label: 'Lemah' },
        { pct: '50%', color: '#F59E0B', label: 'Cukup' },
        { pct: '75%', color: '#3B82F6', label: 'Kuat' },
        { pct: '100%', color: '#22C55E', label: 'Sangat Kuat' },
    ];
    const lvl = levels[score];
    bar.style.width = lvl.pct;
    bar.style.background = lvl.color;
    txt.textContent = lvl.label;
    txt.style.color = lvl.color;
});
</script>
@endpush
@endsection
