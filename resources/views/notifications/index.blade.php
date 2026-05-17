@extends('layouts.app')
@section('title', 'Notifikasi - ForumKita')

@section('content')
<div style="max-width:760px;margin:32px auto;padding:0 16px;">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <h1 style="font-size:1.5rem;font-weight:700;color:#1e293b;margin:0;"><i class="fas fa-bell" style="color:#EAB308;"></i> Notifikasi</h1>
    <form method="POST" action="{{ route('notifications.read-all') }}">
      @csrf
      <button type="submit" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:white;border:2px solid #2563EB;color:#2563EB;border-radius:10px;font-weight:600;font-size:0.85rem;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#2563EB';this.style.color='white'" onmouseout="this.style.background='white';this.style.color='#2563EB'">
        <i class="fas fa-check-double"></i> Tandai Semua Dibaca
      </button>
    </form>
  </div>

  @if($notifications->isEmpty())
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:60px;text-align:center;">
      <i class="fas fa-bell-slash" style="font-size:3rem;color:#cbd5e1;margin-bottom:16px;display:block;"></i>
      <p style="color:#64748b;font-size:1.1rem;margin:0;">Tidak ada notifikasi.</p>
    </div>
  @else
    <div style="display:flex;flex-direction:column;gap:8px;">
      @foreach($notifications as $notif)
      <div style="background:{{ $notif->read_at ? '#fff' : '#eff6ff' }};border:1px solid {{ $notif->read_at ? '#e2e8f0' : '#bfdbfe' }};border-radius:12px;padding:16px;display:flex;align-items:flex-start;gap:14px;">
        @if($notif->actor->avatar)
          <img src="{{ Storage::url($notif->actor->avatar) }}" style="width:42px;height:42px;border-radius:50%;object-fit:cover;flex-shrink:0;">
        @else
          <div style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#2563EB,#EAB308);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1rem;flex-shrink:0;">
            {{ strtoupper(substr($notif->actor->name,0,1)) }}
          </div>
        @endif
        <div style="flex:1;">
          <p style="margin:0;color:#374151;font-size:0.9rem;line-height:1.5;">
            <strong>{{ $notif->actor->name }}</strong>
            {{ $notif->message }}
          </p>
          <span style="color:#94a3b8;font-size:0.8rem;margin-top:4px;display:block;">{{ $notif->created_at->locale('id')->diffForHumans() }}</span>
        </div>
        @if(!$notif->read_at)
          <span style="width:10px;height:10px;background:#2563EB;border-radius:50%;flex-shrink:0;margin-top:4px;"></span>
        @endif
      </div>
      @endforeach
    </div>
    <div style="margin-top:20px;">{{ $notifications->links() }}</div>
  @endif
</div>
@endsection
