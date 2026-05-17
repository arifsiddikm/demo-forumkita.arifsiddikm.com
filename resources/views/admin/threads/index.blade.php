@extends('layouts.admin')
@section('title', 'Kelola Thread')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
  <div>
    <h1 style="font-size:1.3rem;font-weight:800;color:#f1f5f9;margin:0;">Kelola Thread</h1>
    <p style="color:#64748b;margin:2px 0 0;font-size:0.85rem;">Total {{ $threads->total() }} thread</p>
  </div>
</div>

{{-- Search --}}
<div class="adm-card" style="margin-bottom:20px;">
  <div class="adm-card-body">
    <form method="GET" action="{{ route('admin.threads.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
      <div style="flex:1;min-width:200px;">
        <label class="adm-label">Cari Thread</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul thread..." class="adm-input">
      </div>
      <div style="display:flex;gap:8px;align-items:flex-end;">
        <button type="submit" class="adm-btn adm-btn-primary"><i class="fa fa-search"></i> Cari</button>
        <a href="{{ route('admin.threads.index') }}" class="adm-btn adm-btn-secondary"><i class="fa fa-rotate-left"></i> Reset</a>
      </div>
    </form>
  </div>
</div>

<div class="adm-card">
  <table class="adm-table">
    <thead>
      <tr>
        <th>Thread</th>
        <th>Penulis</th>
        <th>Kategori</th>
        <th>Stats</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($threads as $thread)
      <tr>
        <td style="max-width:260px;">
          <a href="{{ route('threads.show', $thread->slug) }}" target="_blank" style="color:#93c5fd;text-decoration:none;font-weight:600;font-size:0.875rem;line-height:1.4;display:block;">
            {{ Str::limit($thread->title, 55) }}
          </a>
          <div style="color:#64748b;font-size:0.75rem;margin-top:3px;">{{ $thread->created_at->format('d M Y H:i') }}</div>
        </td>
        <td style="color:#cbd5e1;font-size:0.85rem;">{{ $thread->user->username }}</td>
        <td>
          <span style="background:{{ $thread->category->color }}22;color:{{ $thread->category->color }};padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;">
            {{ $thread->category->name }}
          </span>
        </td>
        <td>
          <div style="display:flex;gap:10px;font-size:0.78rem;color:#94a3b8;white-space:nowrap;">
            <span><i class="fa fa-eye"></i> {{ $thread->views_count }}</span>
            <span><i class="fa fa-comment"></i> {{ $thread->replies_count }}</span>
            <span><i class="fa fa-heart" style="color:#ef4444;"></i> {{ $thread->likes_count }}</span>
          </div>
        </td>
        <td>
          <div style="display:flex;flex-wrap:wrap;gap:4px;">
            @if($thread->is_pinned)<span class="adm-badge adm-badge-blue"><i class="fa fa-thumbtack"></i> Pin</span>@endif
            @if($thread->is_hot)<span class="adm-badge adm-badge-yellow"><i class="fa fa-fire"></i> Hot</span>@endif
            @if($thread->is_locked)<span class="adm-badge adm-badge-red"><i class="fa fa-lock"></i> Kunci</span>@endif
            @if($thread->is_announcement)<span class="adm-badge adm-badge-purple"><i class="fa fa-bullhorn"></i> Announce</span>@endif
            @if(!$thread->is_pinned && !$thread->is_hot && !$thread->is_locked && !$thread->is_announcement)
              <span class="adm-badge">Normal</span>
            @endif
          </div>
        </td>
        <td>
          <div style="display:flex;gap:4px;flex-wrap:wrap;">
            <form method="POST" action="{{ route('admin.threads.pin', $thread) }}" style="display:inline;">@csrf @method('PUT')
              <button type="submit" class="adm-btn-sm-{{ $thread->is_pinned ? 'blue' : 'gray' }}" title="{{ $thread->is_pinned ? 'Unpin' : 'Pin' }}"><i class="fa fa-thumbtack"></i></button>
            </form>
            <form method="POST" action="{{ route('admin.threads.hot', $thread) }}" style="display:inline;">@csrf @method('PUT')
              <button type="submit" class="adm-btn-sm-{{ $thread->is_hot ? 'yellow' : 'gray' }}" title="{{ $thread->is_hot ? 'Unhot' : 'Hot' }}"><i class="fa fa-fire"></i></button>
            </form>
            <form method="POST" action="{{ route('admin.threads.lock', $thread) }}" style="display:inline;">@csrf @method('PUT')
              <button type="submit" class="adm-btn-sm-{{ $thread->is_locked ? 'red' : 'gray' }}" title="{{ $thread->is_locked ? 'Buka Kunci' : 'Kunci' }}"><i class="fa fa-lock"></i></button>
            </form>
            <form method="POST" action="{{ route('admin.threads.destroy', $thread) }}" style="display:inline;" onsubmit="return confirmDel(event)">@csrf @method('DELETE')
              <button type="submit" class="adm-btn-sm-red" title="Hapus"><i class="fa fa-trash"></i></button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" style="text-align:center;color:#64748b;padding:40px;"><i class="fa fa-inbox" style="font-size:2rem;margin-bottom:10px;display:block;"></i>Tidak ada thread.</td></tr>
      @endforelse
    </tbody>
  </table>
  @if($threads->hasPages())
  <div style="padding:16px 20px;border-top:1px solid #334155;">{{ $threads->links() }}</div>
  @endif
</div>

<script>
function confirmDel(e) {
  e.preventDefault();
  Swal.fire({ title:'Hapus Thread?',text:'Semua balasan juga terhapus!',icon:'warning',showCancelButton:true,
    confirmButtonText:'Hapus',cancelButtonText:'Batal',background:'#1e293b',color:'#f1f5f9',confirmButtonColor:'#ef4444'
  }).then(r => { if (r.isConfirmed) e.target.closest('form').submit(); });
  return false;
}
</script>
@endsection
