@extends('layouts.app')
@section('title', 'Member - ForumKita')
@section('content')
<div style="max-width:1100px;margin:32px auto;padding:0 16px;">
  <h1 style="font-size:1.5rem;font-weight:700;color:#1e293b;margin-bottom:24px;"><i class="fas fa-users" style="color:#2563EB;"></i> Semua Member ({{ $members->total() }})</h1>
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;">
    @foreach($members as $member)
    <a href="{{ route('profile.show', $member->username) }}" style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:20px;text-align:center;text-decoration:none;display:block;transition:box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow='none'">
      @if($member->avatar)
        <img src="{{ Storage::url($member->avatar) }}" style="width:64px;height:64px;border-radius:50%;object-fit:cover;margin:0 auto 12px;">
      @else
        <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#2563EB,#EAB308);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.5rem;margin:0 auto 12px;">{{ strtoupper(substr($member->name,0,1)) }}</div>
      @endif
      <div style="font-weight:600;color:#1e293b;font-size:0.9rem;margin-bottom:4px;">{{ $member->name }}</div>
      <div style="color:#94a3b8;font-size:0.8rem;">@{{ $member->username }}</div>
      <div style="margin-top:8px;">
        <span style="background:{{ $member->level_color }};color:#fff;padding:2px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">{{ $member->level_label }}</span>
      </div>
      @if($member->isOnline())
        <div style="color:#10b981;font-size:0.75rem;margin-top:6px;"><i class="fas fa-circle" style="font-size:0.5rem;"></i> Online</div>
      @endif
    </a>
    @endforeach
  </div>
  <div style="margin-top:24px;">{{ $members->links() }}</div>
</div>
@endsection
