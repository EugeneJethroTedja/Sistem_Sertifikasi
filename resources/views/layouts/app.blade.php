<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Sistem Sertifikat') – BKPSDM Kota Yogyakarta</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600&family=Cinzel:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
/* ── Variables ── */
:root {
    --navy:        #0c1f3d;
    --navy-mid:    #162d54;
    --navy-light:  #1e3d6e;
    --gold:        #b8962e;
    --gold-light:  #d4af5a;
    --gold-pale:   #f0e6c8;
    --cream:       #faf8f4;
    --white:       #ffffff;
    --text-dark:   #1a2332;
    --text-mid:    #4a5568;
    --text-light:  #8a96a8;
    --border:      #e8e2d6;
    --sidebar-w:   260px;
    --radius:      10px;
    --shadow-sm:   0 1px 4px rgba(12,31,61,.06);
    --shadow-md:   0 4px 20px rgba(12,31,61,.10);
    --shadow-lg:   0 8px 40px rgba(12,31,61,.14);
    --transition:  all .22s cubic-bezier(.4,0,.2,1);
}

*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'DM Sans', sans-serif;
    background: var(--cream);
    color: var(--text-dark);
    min-height: 100vh;
    -webkit-font-smoothing: antialiased;
}

/* ══════════════════════════════
   SIDEBAR
══════════════════════════════ */
.sidebar {
    position: fixed;
    top: 0; left: 0;
    width: var(--sidebar-w);
    height: 100vh;
    background: var(--navy);
    display: flex;
    flex-direction: column;
    z-index: 100;
    overflow: hidden;
}

/* Gold line top accent */
.sidebar::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--gold), var(--gold-light), var(--gold));
}

/* Subtle pattern overlay */
.sidebar::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        radial-gradient(circle at 20% 80%, rgba(184,150,46,.06) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(30,61,110,.4) 0%, transparent 50%);
    pointer-events: none;
}

.sidebar-inner {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    height: 100%;
    padding: 0 0 20px;
}

/* Brand */
.sidebar-brand {
    padding: 24px 22px 20px;
    border-bottom: 1px solid rgba(255,255,255,.07);
    margin-bottom: 8px;
}

.brand-logo-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
}

.brand-icon {
    width: 40px; height: 40px;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px;
    color: var(--navy);
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(184,150,46,.35);
}

.brand-title {
    font-family: 'Cinzel', serif;
    font-size: 11px;
    font-weight: 600;
    color: var(--white);
    letter-spacing: 1.5px;
    text-transform: uppercase;
    line-height: 1.3;
}

.brand-sub {
    font-size: 10px;
    color: var(--gold-light);
    font-weight: 400;
    letter-spacing: .5px;
    margin-top: 1px;
}

.brand-divider {
    height: 1px;
    background: linear-gradient(90deg, var(--gold), transparent);
    margin-top: 4px;
    opacity: .5;
}

/* Nav section label */
.nav-section-label {
    font-size: 9.5px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: rgba(255,255,255,.3);
    padding: 14px 22px 6px;
}

/* Nav links */
.sidebar-nav {
    flex: 1;
    padding: 0 12px;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.nav-item-custom {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 10px 14px;
    border-radius: 8px;
    color: rgba(255,255,255,.55);
    text-decoration: none;
    font-size: 13.5px;
    font-weight: 400;
    letter-spacing: .2px;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.nav-item-custom::before {
    content: '';
    position: absolute;
    left: 0; top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 0;
    background: var(--gold);
    border-radius: 0 3px 3px 0;
    transition: height .22s ease;
}

.nav-item-custom:hover {
    background: rgba(255,255,255,.06);
    color: rgba(255,255,255,.85);
}

.nav-item-custom.active {
    background: rgba(184,150,46,.12);
    color: var(--gold-light);
    font-weight: 500;
}

.nav-item-custom.active::before {
    height: 60%;
}

.nav-item-custom .nav-icon {
    font-size: 15px;
    width: 18px;
    text-align: center;
    flex-shrink: 0;
}

/* Sidebar footer */
.sidebar-footer {
    padding: 14px 12px 0;
    border-top: 1px solid rgba(255,255,255,.07);
    margin: 0 0 0;
}

.user-card {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    background: rgba(255,255,255,.04);
    border-radius: 8px;
    border: 1px solid rgba(255,255,255,.07);
    margin-bottom: 10px;
}

.user-avatar {
    width: 32px; height: 32px;
    background: linear-gradient(135deg, var(--navy-light), var(--gold));
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px;
    color: white;
    flex-shrink: 0;
}

.user-info-name {
    font-size: 12px;
    font-weight: 500;
    color: rgba(255,255,255,.8);
    line-height: 1.2;
}

.user-info-role {
    font-size: 10px;
    color: rgba(255,255,255,.35);
}

.btn-logout {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 8px 14px;
    background: transparent;
    border: 1px solid rgba(220,53,69,.3);
    border-radius: 7px;
    color: rgba(220,53,69,.75);
    font-size: 12.5px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    font-family: 'DM Sans', sans-serif;
}

.btn-logout:hover {
    background: rgba(220,53,69,.1);
    border-color: rgba(220,53,69,.5);
    color: #e74c5e;
}

/* ══════════════════════════════
   TOPBAR
══════════════════════════════ */
.topbar {
    position: fixed;
    top: 0;
    left: var(--sidebar-w);
    right: 0;
    height: 58px;
    background: rgba(250,248,244,.92);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 28px;
    z-index: 90;
}

.topbar-title {
    font-family: 'Playfair Display', serif;
    font-size: 17px;
    font-weight: 600;
    color: var(--navy);
    letter-spacing: .2px;
}

.topbar-breadcrumb {
    font-size: 11.5px;
    color: var(--text-light);
    margin-top: 1px;
}

.topbar-right {
    display: flex;
    align-items: center;
    gap: 10px;
}

.topbar-badge {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    background: linear-gradient(135deg, var(--navy), var(--navy-mid));
    border-radius: 20px;
    font-size: 11px;
    color: var(--gold-light);
    font-weight: 500;
    letter-spacing: .5px;
}

/* ══════════════════════════════
   MAIN CONTENT
══════════════════════════════ */
.main-wrapper {
    margin-left: var(--sidebar-w);
    padding-top: 58px;
    min-height: 100vh;
}

.main-content {
    padding: 28px 32px;
}

/* ══════════════════════════════
   CARDS
══════════════════════════════ */
.card {
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    background: var(--white);
    transition: var(--transition);
}

.card:hover {
    box-shadow: var(--shadow-md);
}

.card-header {
    background: var(--white);
    border-bottom: 1px solid var(--border);
    padding: 14px 20px;
    border-radius: var(--radius) var(--radius) 0 0 !important;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-header-title {
    font-family: 'Playfair Display', serif;
    font-size: 15px;
    font-weight: 600;
    color: var(--navy);
}

/* ══════════════════════════════
   TABLE
══════════════════════════════ */
.table th {
    background: #f5f7fc;
    color: var(--navy);
    font-size: 11.5px;
    font-weight: 600;
    letter-spacing: .5px;
    text-transform: uppercase;
    border-bottom: 2px solid var(--border);
    padding: 10px 14px;
}

.table td {
    font-size: 13.5px;
    color: var(--text-dark);
    padding: 11px 14px;
    vertical-align: middle;
    border-color: #f0eee8;
}

.table tbody tr {
    transition: background .15s;
}

.table tbody tr:hover {
    background: #faf8f4;
}

/* ══════════════════════════════
   ALERTS / TOAST
══════════════════════════════ */
.alert {
    border: none;
    border-radius: 8px;
    font-size: 13.5px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-success {
    background: #edfaf3;
    color: #1a6640;
    border-left: 3px solid #2ecc71;
}

.alert-danger {
    background: #fdf0f2;
    color: #8b1a2a;
    border-left: 3px solid #e74c3c;
}

.alert-warning {
    background: #fdf8ed;
    color: #7a5a00;
    border-left: 3px solid var(--gold);
}

/* ══════════════════════════════
   BUTTONS
══════════════════════════════ */
.btn {
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    font-weight: 500;
    border-radius: 7px;
    padding: 7px 16px;
    transition: var(--transition);
    letter-spacing: .2px;
}

.btn-primary {
    background: var(--navy);
    border-color: var(--navy);
    color: white;
}

.btn-primary:hover {
    background: var(--navy-light);
    border-color: var(--navy-light);
    box-shadow: 0 4px 14px rgba(12,31,61,.25);
}

.btn-gold {
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    border: none;
    color: var(--navy);
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(184,150,46,.3);
}

.btn-gold:hover {
    box-shadow: 0 4px 16px rgba(184,150,46,.4);
    transform: translateY(-1px);
    color: var(--navy);
}

.btn-outline-secondary {
    border-color: var(--border);
    color: var(--text-mid);
}

.btn-outline-secondary:hover {
    background: #f5f7fc;
    border-color: #c8c2b8;
    color: var(--navy);
}

/* ══════════════════════════════
   FORM CONTROLS
══════════════════════════════ */
.form-control, .form-select {
    border: 1px solid var(--border);
    border-radius: 7px;
    font-size: 13.5px;
    font-family: 'DM Sans', sans-serif;
    color: var(--text-dark);
    background: var(--white);
    padding: 8px 12px;
    transition: var(--transition);
}

.form-control:focus, .form-select:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(184,150,46,.12);
    outline: none;
}

.form-label {
    font-size: 12.5px;
    font-weight: 600;
    color: var(--navy);
    letter-spacing: .2px;
    margin-bottom: 5px;
}

/* ══════════════════════════════
   BADGES
══════════════════════════════ */
.badge {
    font-family: 'DM Sans', sans-serif;
    font-size: 11px;
    font-weight: 500;
    padding: 4px 9px;
    border-radius: 5px;
    letter-spacing: .3px;
}

/* ══════════════════════════════
   NAV TABS
══════════════════════════════ */
.nav-tabs {
    border-bottom: 2px solid var(--border);
    gap: 2px;
}

.nav-tabs .nav-link {
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-mid);
    border: none;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    padding: 9px 16px;
    border-radius: 0;
    transition: var(--transition);
}

.nav-tabs .nav-link:hover {
    color: var(--navy);
    background: rgba(12,31,61,.03);
}

.nav-tabs .nav-link.active {
    color: var(--navy);
    font-weight: 600;
    border-bottom-color: var(--gold);
    background: transparent;
}

/* ══════════════════════════════
   PAGE HEADER HELPER
══════════════════════════════ */
.page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 22px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border);
}

.page-header-title {
    font-family: 'Playfair Display', serif;
    font-size: 22px;
    font-weight: 700;
    color: var(--navy);
    line-height: 1.2;
}

.page-header-sub {
    font-size: 13px;
    color: var(--text-light);
    margin-top: 3px;
}

/* ══════════════════════════════
   GOLD ACCENT STRIP (dl/card)
══════════════════════════════ */
.card-gold-accent {
    border-top: 3px solid var(--gold);
}

/* ══════════════════════════════
   SCROLLBAR
══════════════════════════════ */
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: #c8c2b8; border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: var(--gold); }

/* ══════════════════════════════
   MODAL
══════════════════════════════ */
.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}

.modal-header {
    background: var(--navy);
    color: white;
    border-bottom: none;
    padding: 16px 22px;
}

.modal-header .modal-title {
    font-family: 'Playfair Display', serif;
    font-size: 16px;
    font-weight: 600;
    color: white;
}

.modal-header .btn-close {
    filter: invert(1);
    opacity: .6;
}

.modal-header .btn-close:hover {
    opacity: 1;
}

.modal-body {
    padding: 22px;
}

.modal-footer {
    border-top: 1px solid var(--border);
    padding: 14px 22px;
    background: #faf8f4;
}

/* ══════════════════════════════
   ANIMATIONS
══════════════════════════════ */
@keyframes fadeSlideIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

.main-content { animation: fadeSlideIn .3s ease forwards; }

/* ══════════════════════════════
   RESPONSIVE
══════════════════════════════ */
@media (max-width: 768px) {
    :root { --sidebar-w: 0px; }
    .sidebar { transform: translateX(-100%); }
    .topbar { left: 0; }
}
</style>
@stack('styles')
</head>
<body>

<!-- ══ SIDEBAR ══ -->
<aside class="sidebar">
    <div class="sidebar-inner">

        <!-- Brand -->
        <div class="sidebar-brand">
            <div class="brand-logo-wrap">
                <div class="brand-icon">
                    <i class="bi bi-award-fill"></i>
                </div>
                <div>
                    <div class="brand-title">Sistem Sertifikat</div>
                    <div class="brand-sub">BKPSDM Kota Yogyakarta</div>
                </div>
            </div>
            <div class="brand-divider"></div>
        </div>

        <!-- Nav label -->
        <div class="nav-section-label">Menu Utama</div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <a href="{{ route('kegiatan.index') }}"
               class="nav-item-custom {{ request()->routeIs('kegiatan*') || request()->routeIs('peserta*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="bi bi-calendar2-event"></i></span>
                Kegiatan & Peserta
            </a>
            {{-- Tambahkan menu lain di sini --}}
        </nav>

        <!-- Footer -->
        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">
                    <i class="bi bi-person-fill" style="font-size:13px;"></i>
                </div>
                <div>
                    <div class="user-info-name">Administrator</div>
                    <div class="user-info-role">Super Admin</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i> Keluar dari Sistem
                </button>
            </form>
        </div>

    </div>
</aside>

<!-- ══ TOPBAR ══ -->
<div class="topbar">
    <div>
        <div class="topbar-title">@yield('page_title', 'Dashboard')</div>
        <div class="topbar-breadcrumb">@yield('breadcrumb', 'BKPSDM Kota Yogyakarta')</div>
    </div>
    <div class="topbar-right">
        <div class="topbar-badge">
            <i class="bi bi-shield-check" style="font-size:10px;"></i>
            Sistem Aktif
        </div>
    </div>
</div>

<!-- ══ MAIN ══ -->
<div class="main-wrapper">
    <div class="main-content">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>