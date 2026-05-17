<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - ForumKita Admin</title>
    <meta name="robots" content="noindex, nofollow">

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>

    <style>
        :root {
            --adm-bg: #0F172A;
            --adm-sidebar: #1E293B;
            --adm-border: #334155;
            --adm-text: #CBD5E1;
            --adm-text-muted: #64748B;
            --adm-white: #FFFFFF;
            --adm-blue: #3B82F6;
            --adm-yellow: #EAB308;
            --adm-green: #22C55E;
            --adm-red: #EF4444;
            --adm-purple: #8B5CF6;
            --adm-card: #1E293B;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Nunito', sans-serif; background: var(--adm-bg); color: var(--adm-text); margin: 0; display: flex; min-height: 100vh; }

        /* ===== SIDEBAR ===== */
        .adm-sidebar {
            width: 240px;
            background: var(--adm-sidebar);
            border-right: 1px solid var(--adm-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            overflow-y: auto;
        }
        .adm-sidebar-logo {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--adm-border);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .adm-sidebar-logo img { height: 32px; filter: brightness(0) invert(1); }
        .adm-sidebar-logo span {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            font-size: 0.85rem;
            color: var(--adm-yellow);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        .adm-nav-section {
            padding: 16px 12px 8px;
        }
        .adm-nav-label {
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--adm-text-muted);
            padding: 0 8px;
            margin-bottom: 6px;
        }
        .adm-nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: 8px;
            color: var(--adm-text);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s;
            margin-bottom: 2px;
        }
        .adm-nav-link:hover { background: rgba(255,255,255,0.07); color: var(--adm-white); text-decoration: none; }
        .adm-nav-link.active { background: var(--adm-blue); color: white; font-weight: 700; }
        .adm-nav-link .icon { width: 20px; text-align: center; font-size: 0.85rem; opacity: 0.7; }
        .adm-nav-link.active .icon { opacity: 1; }
        .adm-nav-badge {
            margin-left: auto;
            background: var(--adm-red);
            color: white;
            font-size: 0.65rem;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 10px;
        }

        /* ===== MAIN ===== */
        .adm-main {
            margin-left: 240px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ===== TOP BAR ===== */
        .adm-topbar {
            background: var(--adm-sidebar);
            border-bottom: 1px solid var(--adm-border);
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .adm-topbar-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--adm-white);
        }

        /* ===== CONTENT ===== */
        .adm-content { padding: 24px; flex: 1; }

        /* ===== CARDS ===== */
        .adm-card {
            background: var(--adm-card);
            border: 1px solid var(--adm-border);
            border-radius: 12px;
            overflow: hidden;
        }
        .adm-card-header {
            padding: 14px 20px;
            border-bottom: 1px solid var(--adm-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .adm-card-header h3 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--adm-white);
            margin: 0;
        }
        .adm-card-body { padding: 20px; }

        /* ===== STAT CARDS ===== */
        .stat-card {
            background: var(--adm-card);
            border: 1px solid var(--adm-border);
            border-radius: 12px;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
        }

        /* ===== BUTTONS ===== */
        .adm-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 0.8rem;
            padding: 8px 18px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.18s;
            white-space: nowrap;
            line-height: 1;
        }
        .adm-btn:focus { outline: none; }
        /* Primary / aliases */
        .adm-btn-primary, .adm-btn-blue { background: var(--adm-blue); color: white; }
        .adm-btn-primary:hover, .adm-btn-blue:hover { background: #2563EB; color: white; text-decoration: none; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59,130,246,0.35); }
        .adm-btn-secondary, .adm-btn-ghost { background: rgba(255,255,255,0.07); color: var(--adm-text); border: 1px solid var(--adm-border); }
        .adm-btn-secondary:hover, .adm-btn-ghost:hover { background: rgba(255,255,255,0.14); color: white; text-decoration: none; border-color: rgba(255,255,255,0.2); }
        .adm-btn-yellow { background: var(--adm-yellow); color: #0F172A; }
        .adm-btn-yellow:hover { background: #CA8A04; color: #0F172A; text-decoration: none; transform: translateY(-1px); }
        .adm-btn-green, .adm-btn-success { background: var(--adm-green); color: white; }
        .adm-btn-green:hover, .adm-btn-success:hover { background: #16A34A; color: white; text-decoration: none; transform: translateY(-1px); }
        .adm-btn-red, .adm-btn-danger { background: var(--adm-red); color: white; }
        .adm-btn-red:hover, .adm-btn-danger:hover { background: #DC2626; color: white; text-decoration: none; transform: translateY(-1px); }
        /* Sizes */
        .adm-btn-sm { padding: 5px 12px; font-size: 0.73rem; }
        .adm-btn-lg { padding: 11px 24px; font-size: 0.9rem; }
        /* Small icon buttons */
        .adm-btn-sm-blue { display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:7px;border:none;cursor:pointer;transition:all 0.15s;background:rgba(59,130,246,0.15);color:#60a5fa; }
        .adm-btn-sm-blue:hover { background:#3b82f6;color:white; }
        .adm-btn-sm-yellow { display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:7px;border:none;cursor:pointer;transition:all 0.15s;background:rgba(234,179,8,0.15);color:#eab308; }
        .adm-btn-sm-yellow:hover { background:#eab308;color:#0f172a; }
        .adm-btn-sm-green { display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:7px;border:none;cursor:pointer;transition:all 0.15s;background:rgba(34,197,94,0.15);color:#4ade80; }
        .adm-btn-sm-green:hover { background:#22c55e;color:white; }
        .adm-btn-sm-red { display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:7px;border:none;cursor:pointer;transition:all 0.15s;background:rgba(239,68,68,0.15);color:#f87171; }
        .adm-btn-sm-red:hover { background:#ef4444;color:white; }
        .adm-btn-sm-gray { display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:7px;border:none;cursor:pointer;transition:all 0.15s;background:rgba(255,255,255,0.07);color:#94a3b8; }
        .adm-btn-sm-gray:hover { background:rgba(255,255,255,0.14);color:white; }
        /* Input aliases */
        .adm-input { width:100%;background:rgba(255,255,255,0.05);border:1px solid var(--adm-border);border-radius:8px;padding:9px 14px;font-size:0.875rem;font-family:'Nunito',sans-serif;color:var(--adm-white);transition:all 0.2s;outline:none;display:block; }
        .adm-input:focus { border-color:var(--adm-blue);box-shadow:0 0 0 2px rgba(59,130,246,0.2); }
        .adm-input::placeholder { color:var(--adm-text-muted); }
        select.adm-input option { background:var(--adm-sidebar);color:var(--adm-white); }
        .adm-label { display:block;font-weight:600;font-size:0.78rem;color:var(--adm-text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px; }
        .adm-error { color:var(--adm-red);font-size:0.78rem;margin-top:4px; }
        .adm-badge { display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:0.72rem;font-weight:700;font-family:'Plus Jakarta Sans',sans-serif; }
        .adm-badge-blue { background:rgba(59,130,246,0.15);color:#60a5fa; }
        .adm-badge-green { background:rgba(34,197,94,0.15);color:#4ade80; }
        .adm-badge-red { background:rgba(239,68,68,0.15);color:#f87171; }
        .adm-badge-yellow { background:rgba(234,179,8,0.15);color:#eab308; }
        .adm-badge-purple { background:rgba(139,92,246,0.15);color:#a78bfa; }
        .adm-badge { background:rgba(255,255,255,0.07);color:var(--adm-text-muted); }

        /* ===== TABLE ===== */
        .adm-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .adm-table th {
            background: rgba(255,255,255,0.04);
            color: var(--adm-text-muted);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 10px 16px;
            border-bottom: 1px solid var(--adm-border);
            text-align: left;
        }
        .adm-table td {
            padding: 12px 16px;
            border-bottom: 1px solid rgba(51,65,85,0.5);
            vertical-align: middle;
            font-size: 0.875rem;
            color: var(--adm-text);
        }
        .adm-table tr:last-child td { border-bottom: none; }
        .adm-table tr:hover td { background: rgba(255,255,255,0.03); }

        /* ===== FORMS ===== */
        .adm-form-label {
            display: block;
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--adm-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
        }
        .adm-form-input,
        .adm-form-select,
        .adm-form-textarea {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--adm-border);
            border-radius: 8px;
            padding: 9px 14px;
            font-size: 0.875rem;
            font-family: 'Nunito', sans-serif;
            color: var(--adm-white);
            transition: all 0.2s;
            outline: none;
            display: block;
        }
        .adm-form-input:focus,
        .adm-form-select:focus,
        .adm-form-textarea:focus {
            border-color: var(--adm-blue);
            box-shadow: 0 0 0 2px rgba(59,130,246,0.2);
        }
        .adm-form-input::placeholder,
        .adm-form-textarea::placeholder { color: var(--adm-text-muted); }
        .adm-form-select option { background: var(--adm-sidebar); color: var(--adm-white); }
        .adm-form-textarea { min-height: 100px; resize: vertical; }
        .adm-form-group { margin-bottom: 16px; }
        .adm-form-error { color: var(--adm-red); font-size: 0.78rem; margin-top: 4px; }

        /* Checkbox & Radio */
        .adm-check {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            margin-bottom: 8px;
        }
        .adm-check input[type="checkbox"],
        .adm-check input[type="radio"] {
            width: 16px;
            height: 16px;
            border: 2px solid var(--adm-border);
            border-radius: 4px;
            background: rgba(255,255,255,0.05);
            cursor: pointer;
            appearance: none;
            flex-shrink: 0;
            transition: all 0.2s;
            position: relative;
        }
        .adm-check input[type="radio"] { border-radius: 50%; }
        .adm-check input[type="checkbox"]:checked,
        .adm-check input[type="radio"]:checked {
            background: var(--adm-blue);
            border-color: var(--adm-blue);
        }
        .adm-check input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            left: 3px; top: 0px;
            width: 6px; height: 9px;
            border: 2px solid white;
            border-top: none; border-left: none;
            transform: rotate(45deg);
        }
        .adm-check input[type="radio"]:checked::after {
            content: '';
            position: absolute;
            left: 3px; top: 3px;
            width: 6px; height: 6px;
            background: white;
            border-radius: 50%;
        }
        .adm-check label { font-size: 0.875rem; color: var(--adm-text); cursor: pointer; }

        /* ===== BADGES ===== */
        .adm-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
        }
        .adm-badge-blue { background: rgba(59,130,246,0.15); color: #93C5FD; }
        .adm-badge-green { background: rgba(34,197,94,0.15); color: #86EFAC; }
        .adm-badge-red { background: rgba(239,68,68,0.15); color: #FCA5A5; }
        .adm-badge-yellow { background: rgba(234,179,8,0.15); color: #FDE047; }
        .adm-badge-gray { background: rgba(100,116,139,0.2); color: #94A3B8; }
        .adm-badge-purple { background: rgba(139,92,246,0.15); color: #C4B5FD; }

        /* ===== ALERTS ===== */
        .adm-alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            border: 1px solid;
        }
        .adm-alert-success { background: rgba(34,197,94,0.1); border-color: rgba(34,197,94,0.3); color: #86EFAC; }
        .adm-alert-danger { background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.3); color: #FCA5A5; }

        /* ===== PAGINATION ===== */
        .adm-pagination { display: flex; gap: 4px; align-items: center; }
        .adm-page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 8px;
            border-radius: 6px;
            background: rgba(255,255,255,0.07);
            border: 1px solid var(--adm-border);
            color: var(--adm-text);
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
        }
        .adm-page-link:hover { background: rgba(255,255,255,0.12); color: white; text-decoration: none; }
        .adm-page-link.active { background: var(--adm-blue); border-color: var(--adm-blue); color: white; }
        .adm-page-link.disabled { opacity: 0.35; pointer-events: none; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--adm-bg); }
        ::-webkit-scrollbar-thumb { background: var(--adm-border); border-radius: 3px; }

        /* Avatar */
        .adm-avatar {
            border-radius: 50%;
            object-fit: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            flex-shrink: 0;
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- Sidebar --}}
    <aside class="adm-sidebar">
        <div class="adm-sidebar-logo">
            <img src="{{ asset('images/logo.svg') }}" alt="ForumKita">
            <span>Admin</span>
        </div>

        {{-- Admin profile mini --}}
        <div style="padding:14px 16px;border-bottom:1px solid var(--adm-border);display:flex;align-items:center;gap:10px;">
            <div class="adm-avatar" style="width:36px;height:36px;background:linear-gradient(135deg,#3B82F6,#8B5CF6);color:white;font-size:0.75rem;">
                {{ strtoupper(substr(auth()->user()->username,0,2)) }}
            </div>
            <div>
                <div style="font-weight:700;font-size:0.82rem;color:var(--adm-white);">{{ auth()->user()->username }}</div>
                <div style="font-size:0.7rem;color:var(--adm-text-muted);">Administrator</div>
            </div>
        </div>

        {{-- Nav --}}
        <div class="adm-nav-section">
            <div class="adm-nav-label">Overview</div>
            <a href="{{ route('admin.dashboard') }}" class="adm-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="icon"><i class="fa fa-gauge-high"></i></span> Dashboard
            </a>
        </div>

        <div class="adm-nav-section">
            <div class="adm-nav-label">Konten</div>
            <a href="{{ route('admin.threads.index') }}" class="adm-nav-link {{ request()->routeIs('admin.threads*') ? 'active' : '' }}">
                <span class="icon"><i class="fa fa-comments"></i></span> Thread
            </a>
            <a href="{{ route('admin.categories.index') }}" class="adm-nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                <span class="icon"><i class="fa fa-folder"></i></span> Kategori
            </a>
            <a href="{{ route('admin.reports.index') }}" class="adm-nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                <span class="icon"><i class="fa fa-flag"></i></span> Laporan
                @php $pendingReports = \App\Models\Report::where('status','pending')->count(); @endphp
                @if($pendingReports > 0)
                    <span class="adm-nav-badge">{{ $pendingReports }}</span>
                @endif
            </a>
        </div>

        <div class="adm-nav-section">
            <div class="adm-nav-label">Pengguna</div>
            <a href="{{ route('admin.users.index') }}" class="adm-nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <span class="icon"><i class="fa fa-users"></i></span> Pengguna
            </a>
        </div>

        <div class="adm-nav-section" style="margin-top:auto;">
            <a href="{{ route('home') }}" class="adm-nav-link" target="_blank">
                <span class="icon"><i class="fa fa-external-link-alt"></i></span> Lihat Website
            </a>
            <button onclick="confirmLogout()" class="adm-nav-link" style="border:none;cursor:pointer;width:100%;background:none;color:var(--adm-red);">
                <span class="icon"><i class="fa fa-right-from-bracket"></i></span> Keluar
            </button>
        </div>
    </aside>

    {{-- Main --}}
    <div class="adm-main">
        {{-- Top bar --}}
        <div class="adm-topbar">
            <h1 class="adm-topbar-title">@yield('title', 'Dashboard')</h1>
            <div style="display:flex;align-items:center;gap:10px;">
                <span style="font-size:0.78rem;color:var(--adm-text-muted);">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
                <div style="width:8px;height:8px;background:var(--adm-green);border-radius:50%;"></div>
                <span style="font-size:0.78rem;color:var(--adm-green);font-weight:600;">Online</span>
            </div>
        </div>

        <div class="adm-content">
            @if(session('success'))
            <div class="adm-alert adm-alert-success">
                <i class="fa fa-check-circle"></i>
                {{ session('success') }}
                <button onclick="this.parentElement.remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;"><i class="fa fa-xmark"></i></button>
            </div>
            @endif
            @if(session('error'))
            <div class="adm-alert adm-alert-danger">
                <i class="fa fa-circle-exclamation"></i>
                {{ session('error') }}
                <button onclick="this.parentElement.remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;"><i class="fa fa-xmark"></i></button>
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>

    <script>
    function confirmLogout() {
        Swal.fire({
            title: 'Keluar dari Admin?',
            text: 'Sesi admin kamu akan berakhir.',
            icon: 'question',
            background: '#1E293B',
            color: '#CBD5E1',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#475569',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal',
        }).then(r => { if (r.isConfirmed) document.getElementById('logoutForm').submit(); });
    }
    function confirmDelete(url, msg = 'Data akan dihapus permanen!') {
        Swal.fire({
            title: 'Hapus Data?',
            text: msg,
            icon: 'warning',
            background: '#1E293B',
            color: '#CBD5E1',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#475569',
            confirmButtonText: 'Hapus!',
            cancelButtonText: 'Batal',
        }).then(r => {
            if (r.isConfirmed) {
                const f = document.getElementById('deleteForm');
                f.action = url;
                f.submit();
            }
        });
    }
    </script>
    <form id="deleteForm" method="POST" style="display:none;">@csrf @method('DELETE')</form>

    @stack('scripts')
</body>
</html>
