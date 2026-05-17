@extends('layouts.app')
@section('title', 'Syarat & Ketentuan - ForumKita')
@section('content')
<div style="max-width:800px;margin:40px auto;padding:0 16px;">
  <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:40px;">
    <h1 style="font-size:1.75rem;font-weight:800;color:#1e293b;margin:0 0 8px;">Syarat & Ketentuan</h1>
    <p style="color:#94a3b8;font-size:0.875rem;margin-bottom:28px;">Terakhir diperbarui: Januari 2025</p>
    <div style="color:#374151;line-height:1.8;">
      <h2 style="font-size:1.1rem;font-weight:700;color:#1e293b;margin:24px 0 8px;">1. Penggunaan Layanan</h2>
      <p>Dengan mendaftar dan menggunakan ForumKita, Anda menyetujui untuk mematuhi semua peraturan dan ketentuan yang berlaku. ForumKita berhak memodifikasi ketentuan ini sewaktu-waktu.</p>
      <h2 style="font-size:1.1rem;font-weight:700;color:#1e293b;margin:24px 0 8px;">2. Konten Pengguna</h2>
      <p>Anda bertanggung jawab penuh atas konten yang Anda posting. Dilarang keras memposting konten yang mengandung SARA, pornografi, kekerasan, atau melanggar hukum Indonesia.</p>
      <h2 style="font-size:1.1rem;font-weight:700;color:#1e293b;margin:24px 0 8px;">3. Hak Kekayaan Intelektual</h2>
      <p>Seluruh konten di ForumKita yang bukan dibuat oleh pengguna adalah milik ForumKita. Anda memberikan lisensi kepada ForumKita untuk menampilkan konten yang Anda buat.</p>
      <h2 style="font-size:1.1rem;font-weight:700;color:#1e293b;margin:24px 0 8px;">4. Penangguhan Akun</h2>
      <p>ForumKita berhak menangguhkan atau menghapus akun yang melanggar ketentuan ini tanpa pemberitahuan sebelumnya.</p>
      <h2 style="font-size:1.1rem;font-weight:700;color:#1e293b;margin:24px 0 8px;">5. Kontak</h2>
      <p>Untuk pertanyaan terkait syarat & ketentuan, silakan <a href="{{ route('contact') }}" style="color:#2563EB;">hubungi kami</a>.</p>
    </div>
  </div>
</div>
@endsection
