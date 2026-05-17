@extends('layouts.app')
@section('title', 'Masuk ke ForumKita')
@section('meta_description', 'Login ke akun ForumKita Anda dan mulai berdiskusi.')

@section('content')
<div style="max-width:440px;margin:0 auto;padding:20px 0;">

    <div style="text-align:center;margin-bottom:32px;">
        <img src="{{ asset('images/logo.svg') }}" alt="ForumKita" style="height:48px;margin-bottom:16px;">
        <h1 style="font-size:1.6rem;font-weight:800;color:var(--fk-gray-900);margin:0 0 6px;">Selamat Datang Kembali!</h1>
        <p style="color:var(--fk-gray-500);font-size:0.9rem;">Masuk ke akun ForumKita kamu</p>
    </div>

    <div class="card">
        <div class="card-body" style="padding:32px;">

            {{-- Autofill Admin Button (only show if admin exists) --}}
            @if(config('app.debug'))
            <div style="background:var(--fk-yellow-light);border:2px dashed var(--fk-yellow);border-radius:10px;padding:12px;margin-bottom:20px;text-align:center;">
                <p style="font-size:0.78rem;color:var(--fk-yellow-dark);font-weight:600;margin:0 0 8px;"><i class="fa fa-flask"></i> Testing Mode</p>
                <button type="button" onclick="autofillAdmin()" class="btn btn-yellow btn-sm">
                    <i class="fa fa-user-shield"></i> Autofill Admin
                </button>
                <button type="button" onclick="autofillUser()" class="btn btn-outline btn-sm" style="margin-left:8px;">
                    <i class="fa fa-user"></i> Autofill User
                </button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger">
                <i class="fa fa-circle-exclamation"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="login">Email atau Username</label>
                    <div style="position:relative;">
                        <i class="fa fa-user" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--fk-gray-400);"></i>
                        <input type="text" name="login" id="login" class="form-input {{ $errors->has('login') ? 'is-invalid' : '' }}" style="padding-left:40px;" placeholder="Email atau username kamu" value="{{ old('login') }}" required autocomplete="username">
                    </div>
                    @error('login') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div style="position:relative;">
                        <i class="fa fa-lock" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--fk-gray-400);"></i>
                        <input type="password" name="password" id="password" class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}" style="padding-left:40px;padding-right:44px;" placeholder="Password kamu" required autocomplete="current-password">
                        <button type="button" onclick="togglePassword('password', this)" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--fk-gray-400);padding:4px;">
                            <i class="fa fa-eye" id="eye-password"></i>
                        </button>
                    </div>
                    @error('password') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                    <label class="form-check">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Ingat saya</label>
                    </label>
                    <a href="{{ route('password.request') }}" style="font-size:0.85rem;color:var(--fk-blue);font-weight:600;text-decoration:none;">Lupa password?</a>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px;">
                    <i class="fa fa-right-to-bracket"></i> Masuk Sekarang
                </button>
            </form>

            <div style="text-align:center;margin-top:20px;padding-top:20px;border-top:1px solid var(--fk-gray-100);">
                <p style="font-size:0.875rem;color:var(--fk-gray-600);">
                    Belum punya akun?
                    <a href="{{ route('register') }}" style="color:var(--fk-blue);font-weight:700;text-decoration:none;">Daftar Gratis</a>
                </p>
            </div>
        </div>
    </div>

    <p style="text-align:center;margin-top:20px;font-size:0.78rem;color:var(--fk-gray-400);">
        Dengan masuk, kamu menyetujui <a href="{{ route('tos') }}" style="color:var(--fk-blue);">Syarat & Ketentuan</a> dan <a href="{{ route('privacy') }}" style="color:var(--fk-blue);">Kebijakan Privasi</a> ForumKita.
    </p>
</div>

@push('scripts')
<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
function autofillAdmin() {
    document.getElementById('login').value = 'admin@forumkita.id';
    document.getElementById('password').value = 'Admin123!!';
    Swal.fire({ icon:'success', title:'Autofill Admin', text:'Data admin sudah diisi. Klik Masuk untuk lanjut.', timer:2000, showConfirmButton:false });
}
function autofillUser() {
    document.getElementById('login').value = 'user@forumkita.id';
    document.getElementById('password').value = 'User123!!';
    Swal.fire({ icon:'info', title:'Autofill User', text:'Data user testing sudah diisi.', timer:2000, showConfirmButton:false });
}
</script>
@endpush
@endsection
