@extends('layouts.admin')
@section('title', 'Kelola Pengguna')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
  <div>
    <h1 style="font-size:1.3rem;font-weight:800;color:#f1f5f9;margin:0;">Kelola Pengguna</h1>
    <p style="color:#64748b;margin:2px 0 0;font-size:0.85rem;">Total {{ $users->total() }} pengguna terdaftar</p>
  </div>
</div>

{{-- Search --}}
<div class="adm-card" style="margin-bottom:20px;">
  <div class="adm-card-body">
    <form method="GET" action="{{ route('admin.users.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
      <div style="flex:1;min-width:200px;">
        <label class="adm-label">Cari Pengguna</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, username, atau email..." class="adm-input">
      </div>
      <div style="min-width:160px;">
        <label class="adm-label">Filter</label>
        <select name="filter" class="adm-input">
          <option value="">Semua Pengguna</option>
          <option value="admin" {{ request('filter')==='admin'?'selected':'' }}>Admin</option>
          <option value="banned" {{ request('filter')==='banned'?'selected':'' }}>Di-ban</option>
        </select>
      </div>
      <div style="display:flex;gap:8px;align-items:flex-end;">
        <button type="submit" class="adm-btn adm-btn-primary"><i class="fa fa-search"></i> Cari</button>
        <a href="{{ route('admin.users.index') }}" class="adm-btn adm-btn-secondary"><i class="fa fa-rotate-left"></i> Reset</a>
      </div>
    </form>
  </div>
</div>

<div class="adm-card">
  <table class="adm-table">
    <thead>
      <tr>
        <th>Pengguna</th>
        <th>Email</th>
        <th>Reputasi</th>
        <th>Status</th>
        <th>Bergabung</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($users as $user)
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:10px;">
            @if($user->avatar)
              <img src="{{ Storage::url($user->avatar) }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;flex-shrink:0;">
            @else
              <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#2563EB,#EAB308);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.85rem;flex-shrink:0;">
                {{ strtoupper(substr($user->name,0,1)) }}
              </div>
            @endif
            <div>
              <div style="font-weight:600;color:#f1f5f9;font-size:0.875rem;">{{ $user->name }}</div>
              <div style="color:#64748b;font-size:0.75rem;">@{{ $user->username }}</div>
            </div>
          </div>
        </td>
        <td style="color:#94a3b8;font-size:0.85rem;">{{ $user->email }}</td>
        <td style="color:#EAB308;font-weight:700;">{{ number_format($user->reputation) }}</td>
        <td>
          @if($user->is_admin)<span class="adm-badge adm-badge-blue"><i class="fa fa-shield"></i> Admin</span>
          @elseif($user->is_banned)<span class="adm-badge adm-badge-red"><i class="fa fa-ban"></i> Banned</span>
          @else<span class="adm-badge adm-badge-green"><i class="fa fa-check"></i> Aktif</span>
          @endif
        </td>
        <td style="color:#64748b;font-size:0.8rem;">{{ $user->created_at->format('d M Y') }}</td>
        <td>
          <div style="display:flex;gap:4px;flex-wrap:wrap;">
            @if($user->is_banned)
              <form method="POST" action="{{ route('admin.users.unban', $user) }}" style="display:inline;">@csrf @method('PUT')
                <button type="submit" class="adm-btn-sm-green" title="Unban"><i class="fa fa-unlock"></i></button>
              </form>
            @elseif($user->id !== auth()->id() && !$user->is_admin)
              <button onclick="openBanModal({{ $user->id }}, '{{ $user->username }}')" class="adm-btn-sm-red" title="Ban"><i class="fa fa-ban"></i></button>
            @endif
            @if($user->id !== auth()->id())
            <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" style="display:inline;">@csrf @method('PUT')
              <button type="submit" class="adm-btn-sm-{{ $user->is_admin ? 'yellow' : 'blue' }}" title="{{ $user->is_admin ? 'Cabut Admin' : 'Jadikan Admin' }}">
                <i class="fa {{ $user->is_admin ? 'fa-user-minus' : 'fa-user-shield' }}"></i>
              </button>
            </form>
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;" onsubmit="return confirmDel(event, '{{ $user->username }}')">
              @csrf @method('DELETE')
              <button type="submit" class="adm-btn-sm-red" title="Hapus"><i class="fa fa-trash"></i></button>
            </form>
            @endif
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" style="text-align:center;color:#64748b;padding:40px;"><i class="fa fa-users" style="font-size:2rem;margin-bottom:10px;display:block;"></i>Tidak ada pengguna ditemukan.</td></tr>
      @endforelse
    </tbody>
  </table>
  @if($users->hasPages())<div style="padding:16px 20px;border-top:1px solid #334155;">{{ $users->links() }}</div>@endif
</div>

{{-- Ban Modal --}}
<div id="ban-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:1000;align-items:center;justify-content:center;padding:16px;">
  <div style="background:#1e293b;border:1px solid #334155;border-radius:16px;padding:28px;width:100%;max-width:460px;">
    <h3 style="color:#f1f5f9;font-size:1.1rem;font-weight:700;margin:0 0 8px;">Ban Pengguna</h3>
    <p id="ban-info" style="color:#94a3b8;margin-bottom:20px;font-size:0.875rem;"></p>
    <form id="ban-form" method="POST">@csrf @method('PUT')
      <div class="adm-form-group">
        <label class="adm-label">Alasan Ban <span style="color:var(--adm-red);">*</span></label>
        <textarea name="reason" rows="3" class="adm-input" required placeholder="Jelaskan alasan ban..."></textarea>
      </div>
      <div style="display:flex;gap:10px;justify-content:flex-end;">
        <button type="button" onclick="closeBanModal()" class="adm-btn adm-btn-secondary">Batal</button>
        <button type="submit" class="adm-btn adm-btn-danger"><i class="fa fa-ban"></i> Ban Pengguna</button>
      </div>
    </form>
  </div>
</div>

<script>
function openBanModal(id, username) {
  document.getElementById('ban-form').action = '/webmin/pengguna/' + id + '/ban';
  document.getElementById('ban-info').textContent = 'Akan mem-ban pengguna: @' + username;
  document.getElementById('ban-modal').style.display = 'flex';
}
function closeBanModal() { document.getElementById('ban-modal').style.display = 'none'; }
function confirmDel(e, username) {
  e.preventDefault();
  Swal.fire({ title:'Hapus @' + username + '?', text:'Akun akan dihapus permanen!', icon:'warning',
    showCancelButton:true, confirmButtonText:'Hapus', cancelButtonText:'Batal',
    background:'#1e293b', color:'#f1f5f9', confirmButtonColor:'#ef4444'
  }).then(r => { if (r.isConfirmed) e.target.closest('form').submit(); });
  return false;
}
</script>
@endsection
