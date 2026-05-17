@extends('layouts.app')

@section('title', 'Forum Diskusi - ForumKita')
@section('meta_description', 'Forum diskusi terlengkap di Indonesia. Bergabung dan diskusikan berbagai topik menarik bersama jutaan member ForumKita.')

@section('content')
<div class="forum-page">
  <div class="container" style="max-width:1200px;margin:0 auto;padding:24px 16px;">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
      <div>
        <h1 style="font-size:1.75rem;font-weight:700;color:#1e293b;margin:0;">Forum Diskusi</h1>
        <p style="color:#64748b;margin:4px 0 0;">Berbagi pengetahuan, diskusi seru, dan temukan jawaban terbaik</p>
      </div>
      @auth
      <a href="{{ route('threads.create') }}" class="btn-primary" style="display:inline-flex;align-items:center;gap:8px;text-decoration:none;">
        <i class="fas fa-plus"></i> Buat Thread Baru
      </a>
      @endauth
    </div>

    <div style="display:grid;grid-template-columns:1fr 280px;gap:24px;">
      {{-- Main Content --}}
      <div>
        {{-- Filter Bar --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:14px 16px;margin-bottom:16px;">
          {{-- Sort tabs --}}
          <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin-bottom:12px;">
            <a href="{{ route('forum.index') }}" class="{{ !request('sort') ? 'tab-active' : 'tab-btn' }}" style="text-decoration:none;">
              <i class="fas fa-list"></i> Semua
            </a>
            <a href="{{ route('forum.index', ['sort' => 'hot']) }}" class="{{ request('sort') === 'hot' ? 'tab-active' : 'tab-btn' }}" style="text-decoration:none;">
              <i class="fas fa-fire"></i> Hot
            </a>
            <a href="{{ route('forum.index', ['sort' => 'latest']) }}" class="{{ request('sort') === 'latest' ? 'tab-active' : 'tab-btn' }}" style="text-decoration:none;">
              <i class="fas fa-clock"></i> Terbaru
            </a>
            <a href="{{ route('forum.index', ['sort' => 'unanswered']) }}" class="{{ request('sort') === 'unanswered' ? 'tab-active' : 'tab-btn' }}" style="text-decoration:none;">
              <i class="fas fa-question-circle"></i> Belum Dijawab
            </a>
          </div>
          {{-- Category filter - separate row --}}
          <div style="border-top:1px solid #f1f5f9;padding-top:12px;">
            @php $filterCatId = request()->query('category'); @endphp
            <form method="GET" action="{{ route('forum.index') }}" style="display:flex;gap:8px;align-items:center;">
              @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
              <label style="font-size:0.8rem;color:#64748b;font-weight:600;white-space:nowrap;"><i class="fas fa-filter"></i> Filter:</label>
              <select name="category" onchange="this.form.submit()" class="form-select" style="max-width:220px;flex:1;">
                <option value="">Semua Kategori</option>
                @foreach(\App\Models\Category::where('is_active', true)->orderBy('sort_order')->get() as $cat)
                <option value="{{ $cat->id }}" {{ $filterCatId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
              </select>
              @if($filterCatId)
              <a href="{{ route('forum.index', request('sort') ? ['sort' => request('sort')] : []) }}" style="font-size:0.8rem;color:#ef4444;text-decoration:none;white-space:nowrap;"><i class="fas fa-times"></i> Reset</a>
              @endif
            </form>
          </div>
        </div>

        {{-- Thread List --}}
        <div style="display:flex;flex-direction:column;gap:12px;">
          @forelse($threads as $thread)
            @include('partials.thread-item', ['thread' => $thread])
          @empty
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:48px;text-align:center;">
              <i class="fas fa-inbox" style="font-size:3rem;color:#cbd5e1;margin-bottom:16px;display:block;"></i>
              <p style="color:#64748b;font-size:1.1rem;margin:0;">Belum ada thread di sini.</p>
              @auth
              <a href="{{ route('threads.create') }}" class="btn-primary" style="display:inline-block;margin-top:16px;text-decoration:none;">Buat Thread Pertama</a>
              @endauth
            </div>
          @endforelse
        </div>

        {{-- Pagination --}}
        @if($threads->hasPages())
        <div style="margin-top:24px;">{{ $threads->appends(request()->query())->links() }}</div>
        @endif
      </div>

      {{-- Sidebar --}}
      <div style="display:flex;flex-direction:column;gap:16px;">
        {{-- Categories --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
          <div style="padding:16px;border-bottom:1px solid #e2e8f0;background:linear-gradient(135deg,#2563EB,#1d4ed8);">
            <h3 style="font-size:0.9rem;font-weight:700;color:#fff;margin:0;text-transform:uppercase;letter-spacing:0.5px;">
              <i class="fas fa-th-large"></i> Kategori
            </h3>
          </div>
          <div style="padding:8px 0;">
            @foreach(\App\Models\Category::where('is_active', true)->withCount('threads')->orderBy('sort_order')->get() as $cat)
            <a href="{{ route('forum.category', $cat->slug) }}" style="display:flex;align-items:center;justify-content:space-between;padding:10px 16px;text-decoration:none;color:#374151;transition:background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
              <span style="display:flex;align-items:center;gap:10px;">
                <span style="width:32px;height:32px;background:{{ $cat->color }}20;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                  <i class="fas {{ $cat->icon }}" style="color:{{ $cat->color }};font-size:0.8rem;"></i>
                </span>
                <span style="font-size:0.9rem;font-weight:500;">{{ $cat->name }}</span>
              </span>
              <span style="font-size:0.75rem;background:#f1f5f9;color:#64748b;padding:2px 8px;border-radius:20px;">{{ $cat->threads_count }}</span>
            </a>
            @endforeach
          </div>
        </div>

        {{-- Popular Tags --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
          <div style="padding:14px 16px;border-bottom:1px solid #e2e8f0;">
            <h3 style="font-size:0.85rem;font-weight:700;color:#374151;margin:0;text-transform:uppercase;letter-spacing:0.5px;">
              <i class="fas fa-tags" style="color:#EAB308;"></i> Tag Populer
            </h3>
          </div>
          <div style="padding:16px;display:flex;flex-wrap:wrap;gap:8px;">
            @foreach(\App\Models\Tag::withCount('threads')->orderByDesc('threads_count')->take(15)->get() as $tag)
            <a href="{{ route('forum.tag', $tag->slug) }}" style="display:inline-block;padding:4px 12px;background:#f1f5f9;border-radius:20px;font-size:0.8rem;color:#475569;text-decoration:none;border:1px solid #e2e8f0;transition:all 0.2s;" onmouseover="this.style.background='#EAB308';this.style.color='#fff';this.style.borderColor='#EAB308'" onmouseout="this.style.background='#f1f5f9';this.style.color='#475569';this.style.borderColor='#e2e8f0'">
              #{{ $tag->name }}
            </a>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.tab-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 14px;
  border-radius: 8px;
  font-size: 0.875rem;
  font-weight: 500;
  color: #475569;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  cursor: pointer;
  transition: all 0.2s;
}
.tab-btn:hover { background: #e2e8f0; color: #1e293b; }
.tab-active {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 14px;
  border-radius: 8px;
  font-size: 0.875rem;
  font-weight: 500;
  color: #fff;
  background: #2563EB;
  border: 1px solid #2563EB;
}
@media (max-width: 768px) {
  .forum-page > .container > div:first-of-type + div {
    grid-template-columns: 1fr !important;
  }
}
</style>
@endsection
