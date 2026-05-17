@extends('layouts.app')

@section('title', $user->name . ' (@' . $user->username . ') - ForumKita')

@section('content')
<div style="max-width:1100px;margin:0 auto;padding:24px 16px;">

  {{-- Profile Header --}}
  <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;margin-bottom:24px;">
    <div style="height:120px;background:linear-gradient(135deg,#2563EB,#1d4ed8,#EAB308);position:relative;"></div>
    <div style="padding:0 24px 24px;display:flex;gap:20px;align-items:flex-end;flex-wrap:wrap;position:relative;">
      <div style="margin-top:-50px;flex-shrink:0;">
        @if($user->avatar)
          <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" style="width:100px;height:100px;border-radius:50%;border:4px solid #fff;object-fit:cover;box-shadow:0 4px 12px rgba(0,0,0,0.15);">
        @else
          <div style="width:100px;height:100px;border-radius:50%;border:4px solid #fff;background:linear-gradient(135deg,#2563EB,#EAB308);display:flex;align-items:center;justify-content:center;font-size:2.5rem;color:#fff;font-weight:700;box-shadow:0 4px 12px rgba(0,0,0,0.15);">
            {{ strtoupper(substr($user->name, 0, 1)) }}
          </div>
        @endif
      </div>
      <div style="flex:1;padding-bottom:4px;">
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
          <h1 style="font-size:1.5rem;font-weight:700;color:#1e293b;margin:0;">{{ $user->name }}</h1>
          @if($user->is_admin)
            <span style="background:#2563EB;color:#fff;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">ADMIN</span>
          @endif
          <span style="background:{{ $user->level_color }};color:#fff;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">{{ $user->level_label }}</span>
        </div>
        <p style="color:#64748b;margin:4px 0 0;font-size:0.9rem;">@<span>{{ $user->username }}</span></p>
        @if($user->bio)
          <p style="color:#374151;margin:8px 0 0;font-size:0.9rem;">{{ $user->bio }}</p>
        @endif
        <div style="display:flex;gap:16px;margin-top:12px;flex-wrap:wrap;">
          @if($user->location)
            <span style="color:#64748b;font-size:0.85rem;"><i class="fas fa-map-marker-alt" style="color:#EAB308;"></i> {{ $user->location }}</span>
          @endif
          @if($user->website)
            <a href="{{ $user->website }}" target="_blank" style="color:#2563EB;font-size:0.85rem;text-decoration:none;"><i class="fas fa-link"></i> Website</a>
          @endif
          <span style="color:#64748b;font-size:0.85rem;"><i class="fas fa-calendar" style="color:#2563EB;"></i> Bergabung {{ $user->created_at->locale('id')->diffForHumans() }}</span>
          @if($user->isOnline())
            <span style="color:#10b981;font-size:0.85rem;font-weight:600;"><i class="fas fa-circle" style="font-size:0.6rem;"></i> Online</span>
          @else
            <span style="color:#64748b;font-size:0.85rem;">Terakhir aktif {{ $user->last_seen_at?->locale('id')->diffForHumans() ?? 'Belum pernah' }}</span>
          @endif
        </div>
      </div>
      <div style="display:flex;gap:8px;">
        @auth
          @if(auth()->id() === $user->id)
            <a href="{{ route('profile.edit') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 18px;background:white;border:2px solid #2563EB;color:#2563EB;border-radius:10px;font-weight:600;font-size:0.875rem;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#2563EB';this.style.color='white'" onmouseout="this.style.background='white';this.style.color='#2563EB'">
              <i class="fas fa-edit"></i> Edit Profil
            </a>
          @endif
        @endauth
      </div>
    </div>

    {{-- Stats Bar --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);border-top:1px solid #e2e8f0;">
      <div style="padding:16px;text-align:center;border-right:1px solid #e2e8f0;">
        <div style="font-size:1.5rem;font-weight:700;color:#2563EB;">{{ $user->threads()->count() }}</div>
        <div style="font-size:0.8rem;color:#64748b;margin-top:2px;">Thread</div>
      </div>
      <div style="padding:16px;text-align:center;border-right:1px solid #e2e8f0;">
        <div style="font-size:1.5rem;font-weight:700;color:#10b981;">{{ $user->replies()->count() }}</div>
        <div style="font-size:0.8rem;color:#64748b;margin-top:2px;">Balasan</div>
      </div>
      <div style="padding:16px;text-align:center;border-right:1px solid #e2e8f0;">
        <div style="font-size:1.5rem;font-weight:700;color:#EAB308;">{{ $user->reputation }}</div>
        <div style="font-size:0.8rem;color:#64748b;margin-top:2px;">Reputasi</div>
      </div>
      <div style="padding:16px;text-align:center;">
        <div style="font-size:1.5rem;font-weight:700;color:#8b5cf6;">{{ $badges->count() }}</div>
        <div style="font-size:0.8rem;color:#64748b;margin-top:2px;">Badge</div>
      </div>
    </div>
  </div>

  {{-- Tabs --}}
  <div style="display:flex;gap:8px;margin-bottom:16px;">
    <button onclick="showTab('threads')" id="tab-threads" class="tab-active-btn" style="padding:10px 20px;border-radius:8px;border:none;font-weight:600;cursor:pointer;font-size:0.875rem;">
      <i class="fas fa-list"></i> Thread ({{ $threads->total() }})
    </button>
    <button onclick="showTab('replies')" id="tab-replies" class="tab-inactive-btn" style="padding:10px 20px;border-radius:8px;border:1px solid #e2e8f0;font-weight:500;cursor:pointer;font-size:0.875rem;background:#fff;color:#64748b;">
      <i class="fas fa-comments"></i> Balasan ({{ $replies->total() }})
    </button>
  </div>

  {{-- Threads Tab --}}
  <div id="content-threads">
    @forelse($threads as $thread)
      @include('partials.thread-item', ['thread' => $thread])
      @if(!$loop->last)<div style="height:12px;"></div>@endif
    @empty
      <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:40px;text-align:center;color:#64748b;">
        <i class="fas fa-inbox" style="font-size:2rem;margin-bottom:12px;display:block;"></i>
        Belum ada thread dari pengguna ini.
      </div>
    @endforelse
    @if($threads->hasPages())<div style="margin-top:16px;">{{ $threads->links() }}</div>@endif
  </div>

  {{-- Replies Tab --}}
  <div id="content-replies" style="display:none;">
    @forelse($replies as $reply)
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:12px;">
      <div style="margin-bottom:8px;">
        <a href="{{ route('threads.show', $reply->thread->slug) }}" style="color:#2563EB;text-decoration:none;font-weight:600;font-size:0.9rem;">
          <i class="fas fa-arrow-right" style="font-size:0.75rem;"></i> {{ $reply->thread->title }}
        </a>
        <span style="color:#94a3b8;font-size:0.8rem;margin-left:8px;">{{ $reply->created_at->locale('id')->diffForHumans() }}</span>
      </div>
      <div style="color:#374151;font-size:0.9rem;line-height:1.6;">{!! Str::limit(strip_tags($reply->body), 200) !!}</div>
    </div>
    @empty
      <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:40px;text-align:center;color:#64748b;">
        <i class="fas fa-comment-slash" style="font-size:2rem;margin-bottom:12px;display:block;"></i>
        Belum ada balasan dari pengguna ini.
      </div>
    @endforelse
    @if($replies->hasPages())<div style="margin-top:16px;">{{ $replies->links() }}</div>@endif
  </div>
</div>

<script>
function showTab(tab) {
  document.getElementById('content-threads').style.display = tab === 'threads' ? 'block' : 'none';
  document.getElementById('content-replies').style.display = tab === 'replies' ? 'block' : 'none';
  document.getElementById('tab-threads').className = tab === 'threads' ? 'tab-active-btn' : 'tab-inactive-btn';
  document.getElementById('tab-replies').className = tab === 'replies' ? 'tab-active-btn' : 'tab-inactive-btn';
  // Fix styles
  ['tab-threads', 'tab-replies'].forEach(id => {
    const el = document.getElementById(id);
    if (el.className === 'tab-active-btn') {
      el.style.cssText = 'padding:10px 20px;border-radius:8px;border:none;font-weight:600;cursor:pointer;font-size:0.875rem;background:#2563EB;color:#fff;';
    } else {
      el.style.cssText = 'padding:10px 20px;border-radius:8px;border:1px solid #e2e8f0;font-weight:500;cursor:pointer;font-size:0.875rem;background:#fff;color:#64748b;';
    }
  });
}
document.getElementById('tab-threads').style.cssText = 'padding:10px 20px;border-radius:8px;border:none;font-weight:600;cursor:pointer;font-size:0.875rem;background:#2563EB;color:#fff;';
</script>
@endsection
