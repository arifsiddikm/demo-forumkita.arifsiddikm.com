@extends('layouts.admin')
@section('title', 'Laporan Konten')

@section('content')
<div style="margin-bottom:24px;">
  <h1 style="font-size:1.3rem;font-weight:800;color:#f1f5f9;margin:0;">Laporan Konten</h1>
  <p style="color:#64748b;margin:4px 0 0;font-size:0.85rem;">Total {{ $reports->total() }} laporan</p>
</div>

<div class="adm-card">
  <table class="adm-table">
    <thead>
      <tr>
        <th>Pelapor</th>
        <th>Tipe</th>
        <th>Alasan</th>
        <th>Deskripsi</th>
        <th>Status</th>
        <th>Tanggal</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($reports as $report)
      <tr>
        <td>
          <div style="font-weight:600;color:#f1f5f9;font-size:0.875rem;">{{ $report->reporter->username }}</div>
        </td>
        <td>
          <span class="adm-badge adm-badge-purple">{{ class_basename($report->reportable_type) }}</span>
        </td>
        <td style="max-width:160px;">
          <span style="color:#e2e8f0;font-size:0.85rem;">{{ $report->reason }}</span>
        </td>
        <td style="max-width:180px;color:#94a3b8;font-size:0.8rem;">
          {{ Str::limit($report->description, 70) ?? '—' }}
        </td>
        <td>
          @if($report->status==='pending')<span class="adm-badge adm-badge-yellow"><i class="fa fa-clock"></i> Pending</span>
          @elseif($report->status==='reviewed')<span class="adm-badge adm-badge-green"><i class="fa fa-check"></i> Selesai</span>
          @else<span class="adm-badge">Diabaikan</span>@endif
        </td>
        <td style="color:#64748b;font-size:0.78rem;">{{ $report->created_at->format('d M Y') }}</td>
        <td>
          @if($report->status==='pending')
          <div style="display:flex;gap:4px;">
            <button onclick="openResolveModal({{ $report->id }})" class="adm-btn-sm-green" title="Selesaikan"><i class="fa fa-check"></i></button>
            <form method="POST" action="{{ route('admin.reports.dismiss', $report) }}" style="display:inline;">@csrf @method('PUT')
              <button type="submit" class="adm-btn-sm-gray" title="Abaikan"><i class="fa fa-xmark"></i></button>
            </form>
          </div>
          @else
          <span style="color:#64748b;font-size:0.78rem;">{{ $report->resolvedBy?->username ?? '—' }}</span>
          @endif
        </td>
      </tr>
      @empty
      <tr><td colspan="7" style="text-align:center;color:#64748b;padding:48px;">
        <i class="fa fa-check-circle" style="font-size:2.5rem;color:#22c55e;margin-bottom:12px;display:block;"></i>
        Tidak ada laporan pending!
      </td></tr>
      @endforelse
    </tbody>
  </table>
  @if($reports->hasPages())<div style="padding:16px 20px;border-top:1px solid #334155;">{{ $reports->links() }}</div>@endif
</div>

{{-- Resolve Modal --}}
<div id="resolve-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:1000;align-items:center;justify-content:center;padding:16px;">
  <div style="background:#1e293b;border:1px solid #334155;border-radius:16px;padding:28px;width:100%;max-width:460px;">
    <h3 style="color:#f1f5f9;font-size:1.1rem;font-weight:700;margin:0 0 16px;"><i class="fa fa-check-circle" style="color:var(--adm-green);"></i> Selesaikan Laporan</h3>
    <form id="resolve-form" method="POST">@csrf @method('PUT')
      <div class="adm-form-group">
        <label class="adm-label">Catatan Admin (opsional)</label>
        <textarea name="admin_note" rows="3" class="adm-input" placeholder="Tindakan yang diambil..."></textarea>
      </div>
      <div style="display:flex;gap:10px;justify-content:flex-end;">
        <button type="button" onclick="document.getElementById('resolve-modal').style.display='none'" class="adm-btn adm-btn-secondary">Batal</button>
        <button type="submit" class="adm-btn adm-btn-success"><i class="fa fa-check"></i> Selesaikan</button>
      </div>
    </form>
  </div>
</div>
<script>
function openResolveModal(id) {
  document.getElementById('resolve-form').action = '/webmin/laporan/' + id + '/resolve';
  document.getElementById('resolve-modal').style.display = 'flex';
}
</script>
@endsection
