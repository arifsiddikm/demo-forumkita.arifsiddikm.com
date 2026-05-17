@extends('layouts.app')
@section('title', 'Leaderboard - ForumKita')
@section('content')
<div style="max-width:900px;margin:32px auto;padding:0 16px;">
  <div style="text-align:center;margin-bottom:32px;">
    <h1 style="font-size:2rem;font-weight:800;color:#1e293b;margin:0;"><i class="fas fa-trophy" style="color:#EAB308;"></i> Leaderboard</h1>
    <p style="color:#64748b;margin:8px 0 0;">Member dengan reputasi tertinggi di ForumKita</p>
  </div>

  <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;">
    @foreach($members as $i => $member)
    <div style="display:flex;align-items:center;gap:16px;padding:16px 20px;{{ !$loop->last ? 'border-bottom:1px solid #f1f5f9;' : '' }}{{ $i < 3 ? 'background:' . ['#fffbeb','#f8fafc','#fff7ed'][$i] . ';' : '' }}">
      <div style="width:36px;text-align:center;flex-shrink:0;">
        @if($i === 0) <span style="font-size:1.5rem;">🥇</span>
        @elseif($i === 1) <span style="font-size:1.5rem;">🥈</span>
        @elseif($i === 2) <span style="font-size:1.5rem;">🥉</span>
        @else <span style="font-size:1rem;font-weight:700;color:#94a3b8;">#{{ $members->firstItem() + $i }}</span>
        @endif
      </div>
      @if($member->avatar)
        <img src="{{ Storage::url($member->avatar) }}" style="width:44px;height:44px;border-radius:50%;object-fit:cover;flex-shrink:0;">
      @else
        <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#2563EB,#EAB308);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.1rem;flex-shrink:0;">{{ strtoupper(substr($member->name,0,1)) }}</div>
      @endif
      <div style="flex:1;">
        <a href="{{ route('profile.show', $member->username) }}" style="font-weight:700;color:#1e293b;text-decoration:none;font-size:0.95rem;">{{ $member->name }}</a>
        <div style="color:#64748b;font-size:0.8rem;">@{{ $member->username }} · <span style="color:{{ $member->level_color }};font-weight:600;">{{ $member->level_label }}</span></div>
      </div>
      <div style="text-align:right;">
        <div style="font-size:1.2rem;font-weight:800;color:#EAB308;">{{ number_format($member->reputation) }}</div>
        <div style="color:#94a3b8;font-size:0.75rem;">reputasi</div>
      </div>
    </div>
    @endforeach
  </div>
  <div style="margin-top:20px;">{{ $members->links() }}</div>
</div>
@endsection
