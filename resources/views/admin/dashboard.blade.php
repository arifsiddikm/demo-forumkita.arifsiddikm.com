@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
{{-- Stat Cards --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
    @foreach([
        ['Pengguna', $stats['users'], 'fa-users', '#3B82F6', '+'.($stats['newUsersToday']).' hari ini'],
        ['Thread', $stats['threads'], 'fa-comments', '#8B5CF6', '+'.($stats['newThreadsToday']).' hari ini'],
        ['Balasan', $stats['replies'], 'fa-reply', '#22C55E', '+'.($stats['newRepliesToday']).' hari ini'],
        ['Laporan', $stats['pendingReports'], 'fa-flag', '#EF4444', 'menunggu review'],
    ] as $stat)
    <div class="stat-card" style="border-top:3px solid {{ $stat[3] }};">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;">
            <div>
                <div style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--adm-text-muted);margin-bottom:8px;">{{ $stat[0] }}</div>
                <div style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:2rem;color:var(--adm-white);">{{ number_format($stat[1]) }}</div>
                <div style="font-size:0.75rem;color:{{ $stat[3] }};margin-top:4px;font-weight:600;">{{ $stat[4] }}</div>
            </div>
            <div style="width:44px;height:44px;background:{{ $stat[3] }}20;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                <i class="fa {{ $stat[2] }}" style="color:{{ $stat[3] }};font-size:1.2rem;"></i>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:16px;margin-bottom:24px;">
    {{-- Chart --}}
    <div class="adm-card">
        <div class="adm-card-header">
            <h3><i class="fa fa-chart-line" style="color:var(--adm-blue);margin-right:8px;"></i>Aktivitas 30 Hari Terakhir</h3>
        </div>
        <div class="adm-card-body">
            <canvas id="activityChart" height="100"></canvas>
        </div>
    </div>

    {{-- Top Categories --}}
    <div class="adm-card">
        <div class="adm-card-header">
            <h3><i class="fa fa-folder" style="color:var(--adm-yellow);margin-right:8px;"></i>Top Kategori</h3>
        </div>
        <div class="adm-card-body" style="padding:12px;">
            @foreach($topCategories as $cat)
            <div style="display:flex;align-items:center;gap:10px;padding:8px;border-radius:8px;margin-bottom:4px;">
                <div style="width:8px;height:8px;border-radius:50%;background:{{ $cat->color ?? '#3B82F6' }};flex-shrink:0;"></div>
                <span style="flex:1;font-size:0.875rem;font-weight:500;">{{ $cat->name }}</span>
                <span class="adm-badge adm-badge-blue">{{ $cat->threads_count }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
    {{-- Latest Users --}}
    <div class="adm-card">
        <div class="adm-card-header">
            <h3><i class="fa fa-user-plus" style="color:var(--adm-green);margin-right:8px;"></i>Pengguna Terbaru</h3>
            <a href="{{ route('admin.users.index') }}" class="adm-btn adm-btn-ghost adm-btn-sm">Lihat Semua</a>
        </div>
        <div>
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Daftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($latestUsers as $user)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div class="adm-avatar" style="width:32px;height:32px;background:linear-gradient(135deg,#3B82F6,#8B5CF6);color:white;font-size:0.7rem;">
                                    {{ strtoupper(substr($user->username,0,2)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:0.82rem;color:var(--adm-white);">{{ $user->username }}</div>
                                    <div style="font-size:0.72rem;color:var(--adm-text-muted);">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:0.78rem;">{{ $user->created_at->diffForHumans() }}</td>
                        <td>
                            <span class="adm-badge {{ $user->is_banned ? 'adm-badge-red' : ($user->email_verified_at ? 'adm-badge-green' : 'adm-badge-yellow') }}">
                                {{ $user->is_banned ? 'Banned' : ($user->email_verified_at ? 'Verified' : 'Pending') }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.users.index') }}" class="adm-btn adm-btn-ghost adm-btn-sm"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pending Reports --}}
    <div class="adm-card">
        <div class="adm-card-header">
            <h3><i class="fa fa-flag" style="color:var(--adm-red);margin-right:8px;"></i>Laporan Terbaru</h3>
            <a href="{{ route('admin.reports.index') }}" class="adm-btn adm-btn-ghost adm-btn-sm">Lihat Semua</a>
        </div>
        <div>
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Reporter</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestReports as $report)
                    <tr>
                        <td>
                            <div style="font-weight:600;font-size:0.82rem;color:var(--adm-white);">{{ $report->reporter->username ?? 'N/A' }}</div>
                            <div style="font-size:0.72rem;color:var(--adm-text-muted);">{{ $report->reportable_type === 'App\Models\Thread' ? 'Thread' : 'Balasan' }}</div>
                        </td>
                        <td style="font-size:0.78rem;max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $report->reason }}</td>
                        <td>
                            <span class="adm-badge {{ $report->status === 'pending' ? 'adm-badge-yellow' : ($report->status === 'resolved' ? 'adm-badge-green' : 'adm-badge-red') }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.reports.show', $report->id) }}" class="adm-btn adm-btn-ghost adm-btn-sm"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;color:var(--adm-text-muted);padding:24px;">Tidak ada laporan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
const ctx = document.getElementById('activityChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [
            {
                label: 'Thread',
                data: @json($chartThreads),
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59,130,246,0.08)',
                tension: 0.4,
                fill: true,
                pointRadius: 3,
                pointBackgroundColor: '#3B82F6',
            },
            {
                label: 'Balasan',
                data: @json($chartReplies),
                borderColor: '#22C55E',
                backgroundColor: 'rgba(34,197,94,0.06)',
                tension: 0.4,
                fill: true,
                pointRadius: 3,
                pointBackgroundColor: '#22C55E',
            },
            {
                label: 'User Baru',
                data: @json($chartUsers),
                borderColor: '#EAB308',
                backgroundColor: 'rgba(234,179,8,0.06)',
                tension: 0.4,
                fill: false,
                pointRadius: 3,
                pointBackgroundColor: '#EAB308',
                borderDash: [4,4],
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: {
                labels: { color: '#94A3B8', font: { family: 'Nunito', size: 12 }, boxWidth: 12 }
            },
            tooltip: {
                backgroundColor: '#1E293B',
                borderColor: '#334155',
                borderWidth: 1,
                titleColor: '#CBD5E1',
                bodyColor: '#94A3B8',
            }
        },
        scales: {
            x: {
                ticks: { color: '#64748B', font: { size: 11 } },
                grid: { color: 'rgba(51,65,85,0.4)' }
            },
            y: {
                ticks: { color: '#64748B', font: { size: 11 } },
                grid: { color: 'rgba(51,65,85,0.4)' }
            }
        }
    }
});
</script>
@endpush
@endsection
