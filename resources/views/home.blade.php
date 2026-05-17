@extends('layouts.app')

@section('title', 'Beranda')
@section('meta_description', 'ForumKita - Temukan diskusi terhangat, thread terpopuler, dan komunitas aktif di Indonesia.')

@section('content')
<div style="display:grid;grid-template-columns:1fr 300px;gap:24px;align-items:start;">

    {{-- Main Content --}}
    <div>
        {{-- Hero Banner --}}
        <div style="background:linear-gradient(135deg,#1D4ED8 0%,#2563EB 50%,#3B82F6 100%);border-radius:16px;padding:28px 32px;margin-bottom:24px;position:relative;overflow:hidden;">
            <div style="position:absolute;right:-20px;top:-20px;width:180px;height:180px;background:rgba(255,255,255,0.05);border-radius:50%;"></div>
            <div style="position:absolute;right:60px;bottom:-40px;width:120px;height:120px;background:rgba(234,179,8,0.15);border-radius:50%;"></div>
            <div style="position:relative;z-index:1;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                    <span class="badge" style="background:rgba(234,179,8,0.2);color:#FDE047;border:1px solid rgba(234,179,8,0.3);">
                        <i class="fa fa-fire"></i> Forum Terpopuler
                    </span>
                </div>
                <h1 style="color:white;font-size:1.8rem;font-weight:800;margin:0 0 10px;line-height:1.2;">
                    Diskusi Apa Aja, <span style="color:#FDE047;">Bareng ForumKita</span>
                </h1>
                <p style="color:rgba(255,255,255,0.8);font-size:0.9rem;margin:0 0 20px;max-width:500px;">
                    Bergabunglah dengan {{ number_format($totalUsers) }}+ pengguna dan diskusikan berbagai topik seru bersama komunitas Indonesia.
                </p>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-yellow">
                            <i class="fa fa-user-plus"></i> Bergabung Sekarang
                        </a>
                        <a href="{{ route('forum.index') }}" class="btn" style="background:rgba(255,255,255,0.15);color:white;border:2px solid rgba(255,255,255,0.3);">
                            Lihat Forum <i class="fa fa-arrow-right"></i>
                        </a>
                    @else
                        <a href="{{ route('threads.create') }}" class="btn btn-yellow">
                            <i class="fa fa-plus"></i> Buat Thread Baru
                        </a>
                        <a href="{{ route('forum.index') }}" class="btn" style="background:rgba(255,255,255,0.15);color:white;border:2px solid rgba(255,255,255,0.3);">
                            Jelajahi Forum <i class="fa fa-compass"></i>
                        </a>
                    @endguest
                </div>
            </div>
        </div>

        {{-- Category Grid --}}
        <div class="card" style="margin-bottom:24px;">
            <div class="card-header">
                <h3><i class="fa fa-th-large" style="color:var(--fk-blue);margin-right:8px;"></i> Kategori Forum</h3>
                <a href="{{ route('forum.index') }}" style="font-size:0.8rem;color:var(--fk-blue);font-weight:600;text-decoration:none;">Lihat Semua <i class="fa fa-arrow-right"></i></a>
            </div>
            <div class="card-body" style="padding:16px;">
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:10px;">
                    @foreach($categories as $cat)
                    <a href="{{ route('forum.category', $cat->slug) }}" style="display:flex;align-items:center;gap:10px;padding:12px;border:2px solid var(--fk-gray-200);border-radius:10px;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.borderColor='{{ $cat->color ?? '#2563EB' }}';this.style.background='{{ $cat->color ?? '#2563EB' }}10'" onmouseout="this.style.borderColor='var(--fk-gray-200)';this.style.background='transparent'">
                        <div style="width:36px;height:36px;background:{{ $cat->color ?? '#2563EB' }}20;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="{{ $cat->icon ?? 'fa fa-folder' }}" style="color:{{ $cat->color ?? '#2563EB' }};font-size:0.9rem;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:0.82rem;color:var(--fk-gray-800);font-family:'Plus Jakarta Sans',sans-serif;">{{ $cat->name }}</div>
                            <div style="font-size:0.72rem;color:var(--fk-gray-500);">{{ $cat->threads_count }} thread</div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Tabs: Hot / Latest / Unanswered --}}
        <div style="margin-bottom:16px;">
            <div style="display:flex;gap:4px;background:var(--fk-gray-100);padding:4px;border-radius:12px;margin-bottom:16px;">
                <button onclick="switchTab('hot')" id="tab-hot" class="tab-btn active-tab" style="flex:1;padding:8px 16px;border-radius:8px;border:none;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:0.875rem;transition:all 0.2s;">
                    <i class="fa fa-fire" style="color:#EF4444;"></i> Hot
                </button>
                <button onclick="switchTab('latest')" id="tab-latest" class="tab-btn" style="flex:1;padding:8px 16px;border-radius:8px;border:none;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:0.875rem;transition:all 0.2s;background:transparent;">
                    <i class="fa fa-clock"></i> Terbaru
                </button>
                <button onclick="switchTab('unanswered')" id="tab-unanswered" class="tab-btn" style="flex:1;padding:8px 16px;border-radius:8px;border:none;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:0.875rem;transition:all 0.2s;background:transparent;">
                    <i class="fa fa-question-circle"></i> Belum Dijawab
                </button>
            </div>

            {{-- Hot Threads --}}
            <div id="content-hot">
                <div style="display:flex;flex-direction:column;gap:10px;">
                    @forelse($hotThreads as $thread)
                        @include('partials.thread-item', ['thread' => $thread])
                    @empty
                        <div style="text-align:center;padding:40px;color:var(--fk-gray-400);">
                            <i class="fa fa-fire" style="font-size:2rem;margin-bottom:8px;"></i>
                            <p>Belum ada thread hot.</p>
                        </div>
                    @endforelse
                </div>
                <div style="text-align:center;margin-top:16px;">
                    <a href="{{ route('forum.index', ['sort'=>'hot']) }}" style="display:inline-flex;align-items:center;gap:6px;padding:10px 24px;background:#f8fafc;border:1px solid #e2e8f0;color:#2563EB;border-radius:10px;font-weight:600;font-size:0.875rem;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#2563EB';this.style.color='white';this.style.borderColor='#2563EB'" onmouseout="this.style.background='#f8fafc';this.style.color='#2563EB';this.style.borderColor='#e2e8f0'">
                        Lihat Semua Thread Hot <i class="fa fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            {{-- Latest Threads --}}
            <div id="content-latest" style="display:none;">
                <div style="display:flex;flex-direction:column;gap:10px;">
                    @forelse($latestThreads as $thread)
                        @include('partials.thread-item', ['thread' => $thread])
                    @empty
                        <div style="text-align:center;padding:40px;color:var(--fk-gray-400);">
                            <i class="fa fa-clock" style="font-size:2rem;margin-bottom:8px;"></i>
                            <p>Belum ada thread terbaru.</p>
                        </div>
                    @endforelse
                </div>
                <div style="text-align:center;margin-top:16px;">
                    <a href="{{ route('forum.index', ['sort'=>'latest']) }}" style="display:inline-flex;align-items:center;gap:6px;padding:10px 24px;background:#f8fafc;border:1px solid #e2e8f0;color:#2563EB;border-radius:10px;font-weight:600;font-size:0.875rem;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#2563EB';this.style.color='white';this.style.borderColor='#2563EB'" onmouseout="this.style.background='#f8fafc';this.style.color='#2563EB';this.style.borderColor='#e2e8f0'">
                        Lihat Semua Thread Terbaru <i class="fa fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            {{-- Unanswered Threads --}}
            <div id="content-unanswered" style="display:none;">
                <div style="display:flex;flex-direction:column;gap:10px;">
                    @forelse($unansweredThreads as $thread)
                        @include('partials.thread-item', ['thread' => $thread])
                    @empty
                        <div style="text-align:center;padding:40px;color:var(--fk-gray-400);">
                            <i class="fa fa-check-circle" style="font-size:2rem;color:#22C55E;margin-bottom:8px;"></i>
                            <p>Semua thread sudah dijawab!</p>
                        </div>
                    @endforelse
                </div>
                <div style="text-align:center;margin-top:16px;">
                    <a href="{{ route('forum.index', ['sort'=>'unanswered']) }}" style="display:inline-flex;align-items:center;gap:6px;padding:10px 24px;background:#f8fafc;border:1px solid #e2e8f0;color:#2563EB;border-radius:10px;font-weight:600;font-size:0.875rem;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#2563EB';this.style.color='white';this.style.borderColor='#2563EB'" onmouseout="this.style.background='#f8fafc';this.style.color='#2563EB';this.style.borderColor='#e2e8f0'">
                        Lihat Semua Thread Belum Dijawab <i class="fa fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <aside>
        {{-- Online Users --}}
        <div class="sidebar-section">
            <div class="sidebar-header"><i class="fa fa-circle" style="color:#4ADE80;font-size:0.6rem;"></i> Sedang Online ({{ $onlineUsers }})</div>
            <div class="sidebar-body">
                <div style="display:flex;flex-wrap:wrap;gap:6px;">
                    @foreach($recentOnlineUsers as $u)
                        <a href="{{ route('profile.show', $u->username) }}" title="{{ $u->username }}" style="text-decoration:none;">
                            <div class="avatar avatar-sm avatar-placeholder" style="font-size:0.65rem;position:relative;">
                                {{ strtoupper(substr($u->username,0,2)) }}
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Top Members --}}
        <div class="sidebar-section">
            <div class="sidebar-header"><i class="fa fa-trophy"></i> Member Terbaik</div>
            <div class="sidebar-body" style="padding:8px;">
                @foreach($topMembers as $i => $member)
                <a href="{{ route('profile.show', $member->username) }}" style="display:flex;align-items:center;gap:10px;padding:8px;border-radius:8px;text-decoration:none;transition:background 0.15s;" onmouseover="this.style.background='var(--fk-gray-50)'" onmouseout="this.style.background='transparent'">
                    <span style="width:20px;font-weight:800;font-size:0.78rem;color:{{ $i===0?'#EAB308':($i===1?'#94A3B8':($i===2?'#CD7F32':'var(--fk-gray-400)')) }};text-align:center;">{{ $i+1 }}</span>
                    <div class="avatar avatar-sm avatar-placeholder" style="font-size:0.65rem;">{{ strtoupper(substr($member->username,0,2)) }}</div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:700;font-size:0.82rem;color:var(--fk-gray-800);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $member->username }}</div>
                        <div style="font-size:0.72rem;color:var(--fk-gray-500);">{{ $member->reputation }} poin</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Latest Tags --}}
        <div class="sidebar-section">
            <div class="sidebar-header"><i class="fa fa-tags"></i> Tag Populer</div>
            <div class="sidebar-body">
                <div style="display:flex;flex-wrap:wrap;gap:6px;">
                    @foreach($popularTags as $tag)
                        <a href="{{ route('forum.tag', $tag->slug) }}" class="badge badge-blue" style="text-decoration:none;cursor:pointer;">
                            #{{ $tag->name }} <span style="opacity:0.7;">({{ $tag->threads_count }})</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Announcements --}}
        @if($announcements->count() > 0)
        <div class="sidebar-section">
            <div class="sidebar-header" style="background:var(--fk-yellow-dark);"><i class="fa fa-bullhorn"></i> Pengumuman</div>
            <div class="sidebar-body" style="padding:8px;">
                @foreach($announcements as $ann)
                <a href="{{ route('threads.show', $ann->slug) }}" style="display:block;padding:8px;border-radius:8px;text-decoration:none;margin-bottom:4px;" onmouseover="this.style.background='var(--fk-yellow-light)'" onmouseout="this.style.background='transparent'">
                    <div style="font-weight:700;font-size:0.82rem;color:var(--fk-gray-800);line-height:1.3;">{{ Str::limit($ann->title, 60) }}</div>
                    <div style="font-size:0.72rem;color:var(--fk-gray-400);margin-top:2px;">{{ $ann->created_at->diffForHumans() }}</div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </aside>
</div>

<style>
.active-tab { background: var(--fk-blue) !important; color: white !important; }
.tab-btn { color: var(--fk-gray-600); }
</style>

@push('scripts')
<script>
function switchTab(tab) {
    ['hot','latest','unanswered'].forEach(t => {
        document.getElementById('content-'+t).style.display = t===tab ? 'block' : 'none';
        const btn = document.getElementById('tab-'+t);
        btn.classList.toggle('active-tab', t===tab);
        btn.style.background = t===tab ? 'var(--fk-blue)' : 'transparent';
        btn.style.color = t===tab ? 'white' : 'var(--fk-gray-600)';
    });
}
</script>
@endpush
@endsection
