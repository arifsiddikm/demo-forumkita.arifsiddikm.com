@extends('layouts.app')
@section('title', $q ? 'Hasil pencarian "' . $q . '"' : 'Cari - ForumKita')
@section('content')
<div style="max-width:900px;margin:32px auto;padding:0 16px;">
  <h1 style="font-size:1.5rem;font-weight:700;color:#1e293b;margin-bottom:8px;">
    <i class="fas fa-search" style="color:#2563EB;"></i>
    @if($q) Hasil untuk "{{ $q }}" @else Pencarian @endif
  </h1>
  @if($q && $threads instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <p style="color:#64748b;margin-bottom:20px;">{{ $threads->total() }} hasil ditemukan</p>
  @endif

  <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:24px;">
    <form method="GET" action="{{ route('search') }}" style="display:flex;gap:10px;">
      <input type="text" name="q" value="{{ $q }}" placeholder="Cari thread, topik..." class="form-input" style="flex:1;" autofocus>
      <button type="submit" class="btn-primary"><i class="fas fa-search"></i> Cari</button>
    </form>
  </div>

  @if($q)
    @if($threads->isEmpty())
      <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:48px;text-align:center;">
        <i class="fas fa-search" style="font-size:3rem;color:#cbd5e1;margin-bottom:16px;display:block;"></i>
        <p style="color:#64748b;font-size:1.1rem;">Tidak ada hasil untuk "<strong>{{ $q }}</strong>"</p>
        <p style="color:#94a3b8;font-size:0.9rem;">Coba kata kunci yang berbeda atau lebih umum.</p>
      </div>
    @else
      <div style="display:flex;flex-direction:column;gap:12px;">
        @foreach($threads as $thread)
          @include('partials.thread-item', ['thread' => $thread])
        @endforeach
      </div>
      <div style="margin-top:20px;">{{ $threads->links() }}</div>
    @endif
  @else
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:48px;text-align:center;">
      <i class="fas fa-keyboard" style="font-size:3rem;color:#cbd5e1;margin-bottom:16px;display:block;"></i>
      <p style="color:#64748b;">Masukkan kata kunci untuk mulai mencari.</p>
    </div>
  @endif
</div>
@endsection
