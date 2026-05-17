@extends('layouts.app')
@section('title', 'Tentang Kami - ForumKita')

@section('content')
<div style="max-width:800px;margin:40px auto;padding:0 16px;">
  <div style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;overflow:hidden;">
    <div style="background:linear-gradient(135deg,#2563EB,#1d4ed8);padding:48px;text-align:center;">
      <img src="{{ asset('images/logo.svg') }}" alt="ForumKita" style="height:64px;filter:brightness(0) invert(1);margin-bottom:16px;">
      <h1 style="font-size:2rem;font-weight:800;color:#fff;margin:0;">Tentang ForumKita</h1>
      <p style="color:#bfdbfe;margin:10px 0 0;font-size:1.05rem;">Forum Diskusi Terbesar di Indonesia</p>
    </div>
    <div style="padding:40px;">
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-bottom:40px;text-align:center;">
        <div style="background:#eff6ff;border-radius:16px;padding:24px;">
          <div style="font-size:2rem;font-weight:800;color:#2563EB;">{{ \App\Models\User::count() }}</div>
          <div style="color:#64748b;font-size:0.9rem;margin-top:4px;">Member</div>
        </div>
        <div style="background:#fefce8;border-radius:16px;padding:24px;">
          <div style="font-size:2rem;font-weight:800;color:#ca8a04;">{{ \App\Models\Thread::count() }}</div>
          <div style="color:#64748b;font-size:0.9rem;margin-top:4px;">Thread</div>
        </div>
        <div style="background:#f0fdf4;border-radius:16px;padding:24px;">
          <div style="font-size:2rem;font-weight:800;color:#16a34a;">{{ \App\Models\Reply::count() }}</div>
          <div style="color:#64748b;font-size:0.9rem;margin-top:4px;">Balasan</div>
        </div>
      </div>
      <h2 style="color:#1e293b;font-size:1.4rem;font-weight:700;margin-bottom:12px;">Apa itu ForumKita?</h2>
      <p style="color:#374151;line-height:1.8;margin-bottom:16px;">ForumKita adalah platform diskusi komunitas terbesar dan terlengkap di Indonesia. Kami hadir untuk menyediakan ruang yang aman, nyaman, dan inklusif bagi jutaan warga Indonesia untuk berdiskusi, berbagi pengetahuan, dan saling terhubung.</p>
      <p style="color:#374151;line-height:1.8;">Dari teknologi hingga hiburan, dari olahraga hingga gaya hidup — semua topik bisa Anda diskusikan di sini bersama jutaan member dari seluruh penjuru Indonesia.</p>

      <div style="background:#f8fafc;border-radius:12px;padding:24px;margin-top:28px;">
        <h3 style="color:#1e293b;font-size:1.1rem;font-weight:700;margin:0 0 12px;"><i class="fas fa-envelope" style="color:#2563EB;"></i> Hubungi Kami</h3>
        <p style="color:#64748b;margin:0;">Email: <a href="mailto:{{ config('mail.from.address') }}" style="color:#2563EB;">{{ config('mail.from.address') }}</a></p>
      </div>
    </div>
  </div>
</div>
@endsection
