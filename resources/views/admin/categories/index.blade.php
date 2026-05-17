@extends('layouts.admin')
@section('title', 'Kelola Kategori')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
  <div>
    <h1 style="font-size:1.3rem;font-weight:800;color:#f1f5f9;margin:0;">Kelola Kategori</h1>
    <p style="color:#64748b;margin:2px 0 0;font-size:0.85rem;">{{ $categories->count() }} kategori forum</p>
  </div>
  <a href="{{ route('admin.categories.create') }}" class="adm-btn adm-btn-primary"><i class="fa fa-plus"></i> Tambah Kategori</a>
</div>

<div class="adm-card">
  <table class="adm-table">
    <thead>
      <tr>
        <th>Kategori</th>
        <th>Deskripsi</th>
        <th>Thread</th>
        <th>Status</th>
        <th>Urutan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($categories as $cat)
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:38px;height:38px;border-radius:10px;background:{{ $cat->color }}22;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <i class="fa {{ $cat->icon }}" style="color:{{ $cat->color }};"></i>
            </div>
            <div>
              <div style="font-weight:700;color:#f1f5f9;font-size:0.875rem;">{{ $cat->name }}</div>
              <div style="color:#64748b;font-size:0.72rem;">/{{ $cat->slug }}</div>
            </div>
          </div>
        </td>
        <td style="color:#94a3b8;font-size:0.82rem;max-width:200px;">{{ Str::limit($cat->description, 60) ?? '—' }}</td>
        <td style="color:#f1f5f9;font-weight:700;">{{ $cat->threads_count }}</td>
        <td>
          @if($cat->is_active)<span class="adm-badge adm-badge-green"><i class="fa fa-circle" style="font-size:0.55rem;"></i> Aktif</span>
          @else<span class="adm-badge adm-badge-red">Non-aktif</span>@endif
        </td>
        <td style="color:#94a3b8;">{{ $cat->sort_order }}</td>
        <td>
          <div style="display:flex;gap:4px;">
            <a href="{{ route('admin.categories.edit', $cat) }}" class="adm-btn-sm-yellow" title="Edit"><i class="fa fa-pencil"></i></a>
            <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" style="display:inline;" onsubmit="return confirmDel(event, '{{ $cat->name }}')">
              @csrf @method('DELETE')
              <button type="submit" class="adm-btn-sm-red" title="Hapus"><i class="fa fa-trash"></i></button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" style="text-align:center;color:#64748b;padding:40px;">
        Belum ada kategori. <a href="{{ route('admin.categories.create') }}" style="color:var(--adm-blue);">Tambah sekarang</a>
      </td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<script>
function confirmDel(e, name) {
  e.preventDefault();
  Swal.fire({ title:'Hapus "' + name + '"?', text:'Semua thread di kategori ini juga akan terhapus!', icon:'warning',
    showCancelButton:true, confirmButtonText:'Hapus', cancelButtonText:'Batal',
    background:'#1e293b', color:'#f1f5f9', confirmButtonColor:'#ef4444'
  }).then(r => { if (r.isConfirmed) e.target.closest('form').submit(); });
  return false;
}
</script>
@endsection
