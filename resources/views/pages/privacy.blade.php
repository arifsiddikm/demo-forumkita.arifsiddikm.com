@extends('layouts.app')
@section('title', 'Kebijakan Privasi - ForumKita')
@section('content')
<div style="max-width:800px;margin:40px auto;padding:0 16px;">
  <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:40px;">
    <h1 style="font-size:1.75rem;font-weight:800;color:#1e293b;margin:0 0 8px;">Kebijakan Privasi</h1>
    <p style="color:#94a3b8;font-size:0.875rem;margin-bottom:28px;">Terakhir diperbarui: Januari 2025</p>
    <div style="color:#374151;line-height:1.8;">
      <h2 style="font-size:1.1rem;font-weight:700;color:#1e293b;margin:24px 0 8px;">1. Data yang Kami Kumpulkan</h2>
      <p>Kami mengumpulkan informasi yang Anda berikan saat mendaftar (nama, email, username), serta data penggunaan seperti thread yang dibuat, balasan, dan aktivitas forum.</p>
      <h2 style="font-size:1.1rem;font-weight:700;color:#1e293b;margin:24px 0 8px;">2. Penggunaan Data</h2>
      <p>Data Anda digunakan untuk mengelola akun, meningkatkan layanan, dan mengirimkan notifikasi yang relevan. Kami tidak menjual data Anda kepada pihak ketiga.</p>
      <h2 style="font-size:1.1rem;font-weight:700;color:#1e293b;margin:24px 0 8px;">3. Keamanan Data</h2>
      <p>Kami menerapkan standar keamanan industri untuk melindungi data Anda, termasuk enkripsi password dan proteksi CSRF.</p>
      <h2 style="font-size:1.1rem;font-weight:700;color:#1e293b;margin:24px 0 8px;">4. Hak Anda</h2>
      <p>Anda berhak mengakses, mengubah, atau menghapus data pribadi Anda melalui halaman Edit Profil atau dengan menghubungi kami.</p>
      <h2 style="font-size:1.1rem;font-weight:700;color:#1e293b;margin:24px 0 8px;">5. Kontak</h2>
      <p>Pertanyaan tentang privasi? <a href="{{ route('contact') }}" style="color:#2563EB;">Hubungi kami</a>.</p>
    </div>
  </div>
</div>
@endsection
