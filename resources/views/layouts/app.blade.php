<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta --}}
    <title>@yield('title', 'ForumKita') - Forum Diskusi Indonesia</title>
    <meta name="description" content="@yield('meta_description', 'ForumKita adalah forum diskusi online terbesar di Indonesia. Bergabunglah dan diskusikan berbagai topik menarik bersama jutaan pengguna.')">
    <meta name="keywords" content="@yield('meta_keywords', 'forum diskusi, indonesia, komunitas, kaskus, diskusi online, forumkita')">
    <meta name="author" content="ForumKita">
    <meta name="robots" content="index, follow">

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('title', 'ForumKita') - Forum Diskusi Indonesia">
    <meta property="og:description" content="@yield('meta_description', 'Forum diskusi online terbesar di Indonesia')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/og-image.png') }}">
    <meta property="og:site_name" content="ForumKita">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'ForumKita')">
    <meta name="twitter:description" content="@yield('meta_description', 'Forum diskusi online terbesar di Indonesia')">

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.svg') }}">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- CKEditor 5 --}}
    {{-- CKEditor loaded per-page via @push('head') --}}
    @stack('head')

    <style>
        :root {
            --fk-blue: #2563EB;
            --fk-blue-dark: #1D4ED8;
            --fk-blue-light: #EFF6FF;
            --fk-yellow: #EAB308;
            --fk-yellow-dark: #CA8A04;
            --fk-yellow-light: #FEFCE8;
            --fk-white: #FFFFFF;
            --fk-gray-50: #F8FAFC;
            --fk-gray-100: #F1F5F9;
            --fk-gray-200: #E2E8F0;
            --fk-gray-300: #CBD5E1;
            --fk-gray-400: #94A3B8;
            --fk-gray-500: #64748B;
            --fk-gray-600: #475569;
            --fk-gray-700: #334155;
            --fk-gray-800: #1E293B;
            --fk-gray-900: #0F172A;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--fk-gray-50);
            color: var(--fk-gray-800);
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: var(--fk-white);
            border-bottom: 3px solid var(--fk-yellow);
            box-shadow: 0 2px 12px rgba(37,99,235,0.08);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .navbar-brand img { height: 38px; }
        .nav-link {
            color: var(--fk-gray-700);
            font-weight: 600;
            font-size: 0.875rem;
            padding: 6px 14px;
            border-radius: 8px;
            transition: all 0.2s;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .nav-link:hover { background: var(--fk-blue-light); color: var(--fk-blue); }
        .nav-link.active { background: var(--fk-blue-light); color: var(--fk-blue); }

        /* ===== SEARCH BAR ===== */
        .search-input {
            background: var(--fk-gray-100);
            border: 2px solid var(--fk-gray-200);
            border-radius: 24px;
            padding: 8px 18px 8px 42px;
            font-size: 0.875rem;
            font-family: 'Nunito', sans-serif;
            color: var(--fk-gray-800);
            width: 100%;
            transition: all 0.2s;
            outline: none;
        }
        .search-input:focus {
            border-color: var(--fk-blue);
            background: white;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 0.875rem;
            padding: 9px 20px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            white-space: nowrap;
        }
        .btn:focus { outline: none; box-shadow: 0 0 0 3px rgba(37,99,235,0.2); }
        .btn-primary {
            background: var(--fk-blue);
            color: white;
        }
        .btn-primary:hover {
            background: var(--fk-blue-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37,99,235,0.3);
            color: white;
            text-decoration: none;
        }
        .btn-yellow {
            background: var(--fk-yellow);
            color: var(--fk-gray-900);
        }
        .btn-yellow:hover {
            background: var(--fk-yellow-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(234,179,8,0.3);
            color: var(--fk-gray-900);
            text-decoration: none;
        }
        .btn-outline {
            background: transparent;
            color: var(--fk-blue);
            border: 2px solid var(--fk-blue);
        }
        .btn-outline:hover {
            background: var(--fk-blue);
            color: white;
            text-decoration: none;
        }
        .btn-outline-yellow {
            background: transparent;
            color: var(--fk-yellow-dark);
            border: 2px solid var(--fk-yellow);
        }
        .btn-outline-yellow:hover {
            background: var(--fk-yellow);
            color: var(--fk-gray-900);
            text-decoration: none;
        }
        .btn-danger {
            background: #EF4444;
            color: white;
        }
        .btn-danger:hover {
            background: #DC2626;
            color: white;
            text-decoration: none;
        }
        .btn-success {
            background: #22C55E;
            color: white;
        }
        .btn-success:hover {
            background: #16A34A;
            color: white;
            text-decoration: none;
        }
        .btn-sm { padding: 6px 14px; font-size: 0.8rem; border-radius: 8px; }
        .btn-lg { padding: 12px 28px; font-size: 1rem; border-radius: 12px; }
        .btn-icon { width: 36px; height: 36px; padding: 0; border-radius: 8px; }

        /* ===== FORMS ===== */
        .form-label {
            display: block;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--fk-gray-700);
            margin-bottom: 6px;
        }
        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            background: var(--fk-white);
            border: 2px solid var(--fk-gray-200);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.9rem;
            font-family: 'Nunito', sans-serif;
            color: var(--fk-gray-800);
            transition: all 0.2s;
            outline: none;
            display: block;
        }
        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            border-color: var(--fk-blue);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        .form-input::placeholder,
        .form-textarea::placeholder { color: var(--fk-gray-400); }
        .form-input.is-invalid,
        .form-select.is-invalid,
        .form-textarea.is-invalid {
            border-color: #EF4444;
            box-shadow: 0 0 0 3px rgba(239,68,68,0.1);
        }
        .form-error {
            color: #EF4444;
            font-size: 0.8rem;
            margin-top: 4px;
            font-weight: 500;
        }
        .form-group { margin-bottom: 18px; }
        .form-textarea { min-height: 100px; resize: vertical; }
        .form-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748B'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; background-size: 18px; padding-right: 40px; }

        /* ===== CHECKBOX & RADIO ===== */
        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            margin-bottom: 10px;
        }
        .form-check input[type="checkbox"],
        .form-check input[type="radio"] {
            width: 18px;
            height: 18px;
            border: 2px solid var(--fk-gray-300);
            border-radius: 4px;
            background: white;
            cursor: pointer;
            appearance: none;
            flex-shrink: 0;
            transition: all 0.2s;
            position: relative;
        }
        .form-check input[type="radio"] { border-radius: 50%; }
        .form-check input[type="checkbox"]:checked,
        .form-check input[type="radio"]:checked {
            background: var(--fk-blue);
            border-color: var(--fk-blue);
        }
        .form-check input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            left: 4px;
            top: 1px;
            width: 6px;
            height: 10px;
            border: 2px solid white;
            border-top: none;
            border-left: none;
            transform: rotate(45deg);
        }
        .form-check input[type="radio"]:checked::after {
            content: '';
            position: absolute;
            left: 3px;
            top: 3px;
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
        }
        .form-check label {
            font-size: 0.9rem;
            color: var(--fk-gray-700);
            font-weight: 500;
            cursor: pointer;
        }

        /* ===== CARDS ===== */
        .card {
            background: var(--fk-white);
            border-radius: 14px;
            border: 1px solid var(--fk-gray-200);
            box-shadow: 0 1px 6px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .card-header {
            background: var(--fk-gray-50);
            border-bottom: 2px solid var(--fk-gray-200);
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-header h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--fk-gray-800);
            margin: 0;
        }
        .card-body { padding: 20px; }

        /* ===== THREAD ITEM ===== */
        .thread-item {
            background: white;
            border: 1px solid var(--fk-gray-200);
            border-radius: 12px;
            padding: 16px 20px;
            transition: all 0.2s;
            display: block;
            text-decoration: none;
            color: inherit;
        }
        .thread-item:hover {
            border-color: var(--fk-blue);
            box-shadow: 0 4px 16px rgba(37,99,235,0.1);
            transform: translateY(-1px);
            text-decoration: none;
        }
        .thread-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            color: var(--fk-gray-900);
            line-height: 1.4;
        }
        .thread-title:hover { color: var(--fk-blue); }
        .thread-meta {
            font-size: 0.78rem;
            color: var(--fk-gray-500);
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .thread-meta span { display: flex; align-items: center; gap: 4px; }

        /* ===== BADGE ===== */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .badge-blue { background: var(--fk-blue-light); color: var(--fk-blue); }
        .badge-yellow { background: var(--fk-yellow-light); color: var(--fk-yellow-dark); }
        .badge-green { background: #F0FDF4; color: #16A34A; }
        .badge-red { background: #FEF2F2; color: #DC2626; }
        .badge-gray { background: var(--fk-gray-100); color: var(--fk-gray-600); }
        .badge-hot { background: linear-gradient(135deg, #FF6B35, #EF4444); color: white; }

        /* ===== SIDEBAR ===== */
        .sidebar-section {
            background: white;
            border: 1px solid var(--fk-gray-200);
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 16px;
        }
        .sidebar-header {
            background: var(--fk-blue);
            color: white;
            padding: 12px 16px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .sidebar-body { padding: 12px; }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 8px;
            color: var(--fk-gray-700);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s;
        }
        .sidebar-link:hover { background: var(--fk-blue-light); color: var(--fk-blue); text-decoration: none; }
        .sidebar-link.active { background: var(--fk-blue-light); color: var(--fk-blue); font-weight: 700; }
        .sidebar-link .icon { width: 20px; text-align: center; color: var(--fk-gray-400); }
        .sidebar-link.active .icon, .sidebar-link:hover .icon { color: var(--fk-blue); }

        /* ===== AVATAR ===== */
        .avatar {
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }
        .avatar-sm { width: 32px; height: 32px; }
        .avatar-md { width: 40px; height: 40px; }
        .avatar-lg { width: 56px; height: 56px; }
        .avatar-xl { width: 80px; height: 80px; }
        .avatar-placeholder {
            background: linear-gradient(135deg, var(--fk-blue), var(--fk-blue-dark));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
        }

        /* ===== PAGINATION ===== */
        .pagination {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
        }
        .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            border-radius: 8px;
            border: 2px solid var(--fk-gray-200);
            color: var(--fk-gray-700);
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.2s;
            background: white;
        }
        .page-link:hover { border-color: var(--fk-blue); color: var(--fk-blue); background: var(--fk-blue-light); text-decoration: none; }
        .page-link.active { background: var(--fk-blue); border-color: var(--fk-blue); color: white; }
        .page-link.disabled { opacity: 0.4; pointer-events: none; }

        /* ===== ALERTS ===== */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 16px;
        }
        .alert-success { background: #F0FDF4; border: 1px solid #86EFAC; color: #15803D; }
        .alert-danger { background: #FEF2F2; border: 1px solid #FCA5A5; color: #DC2626; }
        .alert-warning { background: var(--fk-yellow-light); border: 1px solid #FDE047; color: var(--fk-yellow-dark); }
        .alert-info { background: var(--fk-blue-light); border: 1px solid #BFDBFE; color: var(--fk-blue-dark); }

        /* ===== USER DROPDOWN ===== */
        .user-dropdown { position: relative; }
        .user-dropdown-menu {
            position: absolute;
            right: 0;
            top: calc(100% + 4px);
            background: white;
            border: 1px solid var(--fk-gray-200);
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            min-width: 220px;
            display: none;
            z-index: 300;
            overflow: hidden;
            animation: fadeInDown 0.15s ease;
        }
        @keyframes fadeInDown {
            from { opacity:0; transform:translateY(-6px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .user-dropdown.open .user-dropdown-menu { display: block; }
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            color: var(--fk-gray-700);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }
        .dropdown-item:hover { background: var(--fk-gray-50); color: var(--fk-gray-900); text-decoration: none; }
        .dropdown-item.danger:hover { background: #FEF2F2; color: #DC2626; }
        .dropdown-divider { border-top: 1px solid var(--fk-gray-100); margin: 4px 0; }

        /* ===== CATEGORY CHIP ===== */
        .category-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
            border: 2px solid transparent;
        }
        .category-chip:hover { transform: translateY(-1px); text-decoration: none; }

        /* ===== STICKY TOP BAR ===== */
        .top-bar {
            background: var(--fk-blue);
            color: white;
            font-size: 0.78rem;
            padding: 5px 0;
            font-weight: 500;
        }

        /* ===== TABLE ===== */
        .fk-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .fk-table th {
            background: var(--fk-gray-50);
            color: var(--fk-gray-600);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 12px 16px;
            border-bottom: 2px solid var(--fk-gray-200);
            text-align: left;
        }
        .fk-table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--fk-gray-100);
            vertical-align: middle;
            color: var(--fk-gray-700);
            font-size: 0.875rem;
        }
        .fk-table tr:last-child td { border-bottom: none; }
        .fk-table tr:hover td { background: var(--fk-gray-50); }

        /* ===== MOBILE MENU ===== */
        .mobile-menu {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 500;
            background: rgba(0,0,0,0.5);
        }
        .mobile-menu.open { display: flex; }
        .mobile-menu-panel {
            background: white;
            width: 280px;
            height: 100%;
            padding: 20px;
            overflow-y: auto;
        }

        /* ===== NOTIFICATION BELL ===== */
        .notif-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #EF4444;
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        /* ===== QUOTE BLOCK ===== */
        .quote-block {
            border-left: 4px solid var(--fk-blue);
            background: var(--fk-blue-light);
            padding: 12px 16px;
            border-radius: 0 8px 8px 0;
            margin: 12px 0;
            font-size: 0.9rem;
            color: var(--fk-gray-700);
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--fk-gray-100); }
        ::-webkit-scrollbar-thumb { background: var(--fk-gray-300); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--fk-blue); }

        /* ===== FOOTER ===== */
        .footer {
            background: var(--fk-gray-900);
            color: var(--fk-gray-400);
            margin-top: 48px;
        }
        .footer-link { color: var(--fk-gray-400); text-decoration: none; font-size: 0.875rem; transition: color 0.2s; }
        .footer-link:hover { color: var(--fk-yellow); text-decoration: none; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .hide-mobile { display: none !important; }
            .show-mobile { display: flex !important; }
        }
        @media (min-width: 769px) {
            .show-mobile { display: none !important; }
        }

        /* CKEditor Wrapper */
        .ck-editor__editable { min-height: 200px !important; border-radius: 0 0 10px 10px !important; }
        .ck-toolbar { border-radius: 10px 10px 0 0 !important; }
        .ck.ck-editor { border-radius: 10px; border: 2px solid var(--fk-gray-200) !important; }
        .ck.ck-editor:focus-within { border-color: var(--fk-blue) !important; }

        /* ===== LOADING ===== */
        .skeleton {
            background: linear-gradient(90deg, var(--fk-gray-100) 25%, var(--fk-gray-200) 50%, var(--fk-gray-100) 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s infinite;
            border-radius: 8px;
        }
        @keyframes skeleton-loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Online indicator */
        .online-dot { width: 10px; height: 10px; background: #22C55E; border-radius: 50%; border: 2px solid white; }
    </style>

    @stack('styles')
</head>
<body>

    {{-- Top Info Bar --}}
    <div class="top-bar">
        <div style="max-width:1280px;margin:0 auto;padding:0 16px;display:flex;justify-content:space-between;align-items:center;">
            <span><i class="fa fa-bullhorn" style="margin-right:6px"></i> Selamat datang di <strong>ForumKita</strong> - Forum Diskusi Indonesia!</span>
            <span class="hide-mobile">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
        </div>
    </div>

    {{-- Navbar --}}
    <nav class="navbar">
        <div style="max-width:1280px;margin:0 auto;padding:10px 16px;display:flex;align-items:center;gap:12px;">
            <a href="{{ route('home') }}" class="navbar-brand" style="flex-shrink:0;">
                <img src="{{ asset('images/logo.svg') }}" alt="ForumKita" style="height:38px;">
            </a>

            {{-- Search --}}
            <div style="flex:1;max-width:400px;position:relative;" class="hide-mobile">
                <i class="fa fa-search" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--fk-gray-400);font-size:0.85rem;"></i>
                <form action="{{ route('search') }}" method="GET">
                    <input type="text" name="q" class="search-input" placeholder="Cari thread, topik, pengguna..." value="{{ request('q') }}">
                </form>
            </div>

            {{-- Nav Links --}}
            <div style="display:flex;align-items:center;gap:4px;margin-left:auto;" class="hide-mobile">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fa fa-home"></i> Home
                </a>
                <a href="{{ route('forum.index') }}" class="nav-link {{ request()->routeIs('forum.*') ? 'active' : '' }}">
                    <i class="fa fa-comments"></i> Forum
                </a>
                <a href="{{ route('forum.index', ['sort' => 'hot']) }}" class="nav-link {{ request()->routeIs('forum.*') && request('sort') === 'hot' ? 'active' : '' }}">
                    <i class="fa fa-fire" style="color:#EF4444"></i> Hot
                </a>
            </div>

            {{-- User Area --}}
            <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
                @auth
                    <a href="{{ route('threads.create') }}" class="btn btn-yellow btn-sm hide-mobile">
                        <i class="fa fa-plus"></i> Buat Thread
                    </a>

                    {{-- Notification --}}
                    <div style="position:relative;">
                        <a href="{{ route('notifications.index') }}" class="btn btn-icon" style="background:var(--fk-gray-100);color:var(--fk-gray-700);position:relative;">
                            <i class="fa fa-bell"></i>
                            @if(auth()->user()->unreadNotificationsCount() > 0)
                                <span class="notif-badge">{{ auth()->user()->unreadNotificationsCount() > 9 ? '9+' : auth()->user()->unreadNotificationsCount() }}</span>
                            @endif
                        </a>
                    </div>

                    {{-- User Dropdown --}}
                    <div class="user-dropdown" id="userDropdown">
                        <button onclick="toggleDropdown()" style="display:flex;align-items:center;gap:8px;background:none;border:none;cursor:pointer;padding:6px 10px;border-radius:10px;transition:background 0.2s;" onmouseover="this.style.background='var(--fk-gray-100)'" onmouseout="this.style.background='none'">
                            @if(auth()->user()->avatar)
                                <img src="{{ Storage::url(auth()->user()->avatar) }}" class="avatar avatar-sm" alt="" style="object-fit:cover;">
                            @else
                                <div class="avatar avatar-sm avatar-placeholder" style="font-size:0.75rem;">
                                    {{ strtoupper(substr(auth()->user()->username,0,2)) }}
                                </div>
                            @endif
                            <span style="font-weight:600;font-size:0.875rem;color:var(--fk-gray-800);" class="hide-mobile">{{ auth()->user()->username }}</span>
                            <i class="fa fa-chevron-down" style="font-size:0.7rem;color:var(--fk-gray-500);"></i>
                        </button>
                        <div class="user-dropdown-menu">
                            <div style="padding:12px 16px;border-bottom:1px solid var(--fk-gray-100);">
                                <div style="font-weight:700;font-size:0.875rem;color:var(--fk-gray-900);">{{ auth()->user()->username }}</div>
                                <div style="font-size:0.78rem;color:var(--fk-gray-500);">{{ auth()->user()->email }}</div>
                            </div>
                            <a href="{{ route('profile.show', auth()->user()->username) }}" class="dropdown-item"><i class="fa fa-user" style="width:18px;text-align:center;color:var(--fk-blue)"></i> Profil Saya</a>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item"><i class="fa fa-cog" style="width:18px;text-align:center;color:var(--fk-gray-500)"></i> Edit Profil</a>
                            @if(auth()->user()->is_admin)
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('admin.dashboard') }}" class="dropdown-item"><i class="fa fa-shield-halved" style="width:18px;text-align:center;color:var(--fk-yellow-dark)"></i> Admin Panel</a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <button class="dropdown-item danger" onclick="confirmLogout()"><i class="fa fa-right-from-bracket" style="width:18px;text-align:center;"></i> Keluar</button>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm hide-mobile">Daftar</a>
                @endauth

                {{-- Mobile Hamburger --}}
                <button class="show-mobile btn btn-icon" style="background:var(--fk-gray-100);color:var(--fk-gray-700);" onclick="openMobileMenu()">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main style="max-width:1280px;margin:0 auto;padding:24px 16px;">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa fa-circle-check"></i>
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;"><i class="fa fa-xmark"></i></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fa fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;"><i class="fa fa-xmark"></i></button>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="footer">
        <div style="max-width:1280px;margin:0 auto;padding:40px 16px;">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:32px;margin-bottom:32px;">
                <div>
                    <img src="{{ asset('images/logo.svg') }}" alt="ForumKita" style="height:36px;margin-bottom:12px;filter:brightness(0) invert(1);">
                    <p style="font-size:0.875rem;line-height:1.6;margin-top:8px;">Forum diskusi online terbesar di Indonesia. Tempat berbagi, berdiskusi, dan bertukar pikiran.</p>
                </div>
                <div>
                    <h4 style="color:white;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-bottom:12px;">Forum</h4>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <a href="{{ route('forum.index') }}" class="footer-link">Semua Kategori</a>
                        <a href="{{ route('forum.index', ['sort' => 'hot']) }}" class="footer-link">Thread Hot</a>
                        <a href="{{ route('forum.index', ['sort' => 'latest']) }}" class="footer-link">Thread Terbaru</a>
                    </div>
                </div>
                <div>
                    <h4 style="color:white;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-bottom:12px;">Komunitas</h4>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <a href="{{ route('members') }}" class="footer-link">Anggota</a>
                        <a href="{{ route('leaderboard') }}" class="footer-link">Leaderboard</a>
                    </div>
                </div>
                <div>
                    <h4 style="color:white;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-bottom:12px;">Informasi</h4>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <a href="{{ route('about') }}" class="footer-link">Tentang Kami</a>
                        <a href="{{ route('tos') }}" class="footer-link">Syarat & Ketentuan</a>
                        <a href="{{ route('privacy') }}" class="footer-link">Kebijakan Privasi</a>
                        <a href="{{ route('contact') }}" class="footer-link">Kontak</a>
                    </div>
                </div>
            </div>
            <div style="border-top:1px solid rgba(255,255,255,0.1);padding-top:24px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
                <p style="font-size:0.8rem;">&copy; {{ date('Y') }} ForumKita. Hak cipta dilindungi.</p>
                <div style="display:flex;gap:12px;">
                    <a href="#" class="footer-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="footer-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="footer-link"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="footer-link"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Mobile Menu --}}
    <div class="mobile-menu" id="mobileMenu" onclick="closeMobileMenu(event)">
        <div class="mobile-menu-panel">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                <img src="{{ asset('images/logo.svg') }}" alt="ForumKita" style="height:32px;">
                <button onclick="closeMobileMenu()" style="background:none;border:none;font-size:1.2rem;color:var(--fk-gray-500);cursor:pointer;"><i class="fa fa-xmark"></i></button>
            </div>
            @auth
                <div style="display:flex;align-items:center;gap:10px;padding:12px;background:var(--fk-blue-light);border-radius:10px;margin-bottom:16px;">
                    <div class="avatar avatar-md avatar-placeholder" style="font-size:0.875rem;">{{ strtoupper(substr(auth()->user()->username,0,2)) }}</div>
                    <div>
                        <div style="font-weight:700;font-size:0.875rem;">{{ auth()->user()->username }}</div>
                        <div style="font-size:0.75rem;color:var(--fk-gray-500);">{{ auth()->user()->level_label }}</div>
                    </div>
                </div>
            @endauth
            <div style="display:flex;flex-direction:column;gap:4px;">
                <a href="{{ route('home') }}" class="sidebar-link"><span class="icon"><i class="fa fa-home"></i></span> Home</a>
                <a href="{{ route('forum.index') }}" class="sidebar-link"><span class="icon"><i class="fa fa-comments"></i></span> Forum</a>
                <a href="{{ route('forum.index', ['sort' => 'hot']) }}" class="sidebar-link"><span class="icon"><i class="fa fa-fire"></i></span> Hot</a>
                <a href="{{ route('forum.index', ['sort' => 'latest']) }}" class="sidebar-link"><span class="icon"><i class="fa fa-clock"></i></span> Terbaru</a>
                @auth
                    <a href="{{ route('threads.create') }}" class="sidebar-link"><span class="icon"><i class="fa fa-plus"></i></span> Buat Thread</a>
                    <a href="{{ route('profile.show', auth()->user()->username) }}" class="sidebar-link"><span class="icon"><i class="fa fa-user"></i></span> Profil</a>
                    <button onclick="confirmLogout()" class="sidebar-link" style="border:none;background:none;text-align:left;cursor:pointer;color:#EF4444;"><span class="icon" style="color:#EF4444;"><i class="fa fa-right-from-bracket"></i></span> Keluar</button>
                @else
                    <a href="{{ route('login') }}" class="sidebar-link"><span class="icon"><i class="fa fa-sign-in-alt"></i></span> Masuk</a>
                    <a href="{{ route('register') }}" class="sidebar-link"><span class="icon"><i class="fa fa-user-plus"></i></span> Daftar</a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Logout Form --}}
    @auth
    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    @endauth

    <script>
        // Dropdown toggle (click-based)
        function toggleDropdown() {
            document.getElementById('userDropdown').classList.toggle('open');
        }
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dd = document.getElementById('userDropdown');
            if (dd && !dd.contains(e.target)) dd.classList.remove('open');
        });

        // Mobile Menu
        function openMobileMenu() { document.getElementById('mobileMenu').classList.add('open'); }
        function closeMobileMenu(e) {
            if (!e || e.target === document.getElementById('mobileMenu')) {
                document.getElementById('mobileMenu').classList.remove('open');
            }
        }

        // Logout
        function confirmLogout() {
            Swal.fire({
                title: 'Keluar dari ForumKita?',
                text: 'Kamu akan keluar dari akun ini.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#64748B',
                confirmButtonText: '<i class="fa fa-right-from-bracket"></i> Ya, Keluar',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('logoutForm').submit();
            });
        }

        // Auto dismiss flash alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(el => {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            });
        }, 4000);
    </script>

    @stack('scripts')
</body>
</html>
