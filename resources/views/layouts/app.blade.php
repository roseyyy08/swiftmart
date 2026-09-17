<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - SwiftMart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.bunny.net/css?family=nunito:400,600,700,800" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=playfair-display:700,800" rel="stylesheet">

    <style>
        :root {
            --bg-1: #0b1512;
            --bg-2: #060f0c;
            --sidebar-bg: #16221b;
            --sidebar-active: #22392b;
            --card-bg: #131f19;
            --card-border: rgba(255,255,255,.06);
            --gold: #cda45a;
            --gold-light: #e8cf94;
            --teal: #1f7a6c;
            --teal-light: #34a893;
            --text: #e7f1ee;
            --text-muted: #a9c2b6;
            --text-faint: #5f7469;
            --danger: #d97757;
            --danger-bg: rgba(217,119,87,.16);
            --success: #34c281;
            --success-bg: rgba(52,194,129,.16);
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: radial-gradient(circle at top left, var(--bg-1), var(--bg-2));
            color: var(--text);
            min-height: 100vh;
        }

        a { text-decoration: none; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: 260px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--card-border);
            padding: 28px 18px;
            overflow-y: auto;
            z-index: 100;
        }

        .sidebar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 2px;
        }
        .sidebar-brand .accent { color: var(--gold); }
        .sidebar-sub {
            font-size: .72rem;
            color: var(--text-faint);
            letter-spacing: .04em;
            margin-bottom: 30px;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-muted);
            font-weight: 600;
            font-size: .92rem;
            padding: 11px 14px;
            border-radius: 10px;
            margin-bottom: 4px;
            transition: background .15s, color .15s;
        }
        .sidebar-nav .nav-link i { font-size: 1.05rem; width: 20px; text-align: center; }
        .sidebar-nav .nav-link:hover { background: rgba(255,255,255,.04); color: var(--text); }
        .sidebar-nav .nav-link.active { background: var(--sidebar-active); color: var(--gold-light); }

        /* ===== CONTENT ===== */
        .content-wrapper { margin-left: 260px; min-height: 100vh; }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 22px 36px;
            border-bottom: 1px solid var(--card-border);
        }
        .topbar-title { font-size: 1.05rem; font-weight: 700; color: var(--text); }

        .btn-swift-outline-gold {
            border: 1px solid var(--gold);
            color: var(--gold-light);
            background: transparent;
            border-radius: 30px;
            padding: 7px 18px;
            font-weight: 700;
            font-size: .85rem;
        }
        .btn-swift-outline-gold:hover { background: rgba(205,164,90,.1); color: var(--gold-light); }

        .avatar-badge {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: var(--teal);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .8rem;
        }

        .page-body { padding: 32px 36px; }
        .page-eyebrow {
            color: var(--gold);
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
        }
        .page-heading {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 2px;
        }
        .page-subtext { color: var(--text-muted); font-size: .9rem; margin-bottom: 24px; }

        /* ===== STAT CARDS ===== */
        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-top: 3px solid;
            border-image: linear-gradient(to right, var(--gold), var(--teal-light)) 1;
            border-radius: 10px;
            padding: 20px 22px;
            height: 100%;
        }
        .stat-card-label { color: var(--text-muted); font-size: .82rem; font-weight: 600; margin-bottom: 10px; }
        .stat-card-value { font-family: 'Nunito', sans-serif; font-size: 1.7rem; font-weight: 800; color: var(--text); }
        .stat-card-icon {
            width: 36px; height: 36px;
            border-radius: 8px;
            background: rgba(205,164,90,.12);
            color: var(--gold-light);
            display: flex; align-items: center; justify-content: center;
        }

        /* ===== CARD (containers, forms) ===== */
        .swift-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 12px;
            padding: 24px;
        }

        /* ===== BUTTONS ===== */
        .btn-swift-primary {
            background: var(--teal);
            color: #fff;
            border: none;
            border-radius: 30px;
            padding: 10px 22px;
            font-weight: 700;
            font-size: .9rem;
        }
        .btn-swift-primary:hover { background: var(--teal-light); color: #fff; }

        .btn-swift-ghost {
            color: var(--teal-light);
            font-weight: 700;
            font-size: .85rem;
            border: none;
            background: none;
            padding: 4px 8px;
        }
        .btn-swift-ghost-danger {
            color: var(--danger);
            font-weight: 700;
            font-size: .85rem;
            border: none;
            background: none;
            padding: 4px 8px;
        }

        /* ===== FORM INPUTS ===== */
        .form-control, .form-select {
            background: var(--bg-2);
            border: 1px solid var(--card-border);
            color: var(--text);
            border-radius: 8px;
        }
        .form-control::placeholder { color: var(--text-faint); }
        .form-control:focus, .form-select:focus {
            background: var(--bg-2);
            color: var(--text);
            border-color: var(--teal-light);
            box-shadow: 0 0 0 .2rem rgba(31,122,108,.2);
        }
        .form-label { color: var(--text-muted); font-weight: 600; font-size: .85rem; }

        /* ===== TABLE ===== */
        .swift-table { width: 100%; color: var(--text); }
        .swift-table > :not(caption) > * > * {
            color: var(--text) !important;
        }
        .swift-table thead th {
            color: var(--text-faint) !important;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            border-bottom: 1px solid var(--card-border);
            padding-bottom: 12px;
        }
        .swift-table tbody td {
            padding: 14px 8px;
            border-bottom: 1px solid var(--card-border);
            vertical-align: middle;
        }
        .swift-table tbody tr:hover { background: rgba(255,255,255,.02); }
        body .text-muted { color: var(--text-muted) !important; }

        /* ===== BADGES ===== */
        .pill { border-radius: 30px; padding: 4px 12px; font-size: .78rem; font-weight: 700; display: inline-block; }
        .pill-success { background: var(--success-bg); color: var(--success); }
        .pill-danger { background: var(--danger-bg); color: var(--danger); }
        .pill-gold { background: rgba(205,164,90,.15); color: var(--gold-light); }
        .pill-neutral { background: rgba(255,255,255,.06); color: var(--text-muted); }
    </style>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand">Swift<span class="accent">Mart</span></div>
        <div class="sidebar-sub">Self Payment System</div>

        <nav class="sidebar-nav">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                <i class="bi bi-grid-3x3-gap-fill"></i> Kategori
            </a>
            <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                <i class="bi bi-box-seam-fill"></i> Produk
            </a>
            <a class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}" href="{{ route('members.index') }}">
                <i class="bi bi-person-fill"></i> Member
            </a>
            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                <i class="bi bi-bar-chart-fill"></i> Laporan
            </a>
        </nav>
    </div>

    <div class="content-wrapper">
        <div class="topbar">
            <div class="topbar-title">@yield('title', 'Dashboard')</div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('checkout.index') }}" target="_blank" class="btn-swift-outline-gold">
                    <i class="bi bi-cart3 me-1"></i>Self Checkout
                </a>

                @auth
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center gap-2" role="button" data-bs-toggle="dropdown">
                        <div class="avatar-badge">
                            @php
                                $initials = collect(explode(' ', Auth::user()->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('');
                            @endphp
                            {{ $initials }}
                        </div>
                        <i class="bi bi-chevron-down text-muted small"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" style="background: var(--card-bg); border-color: var(--card-border);">
                        <a class="dropdown-item text-light" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </div>
                </div>
                @endauth
            </div>
        </div>

        <div class="page-body">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>