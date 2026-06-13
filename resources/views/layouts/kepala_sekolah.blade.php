<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — SIM Sekolah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0284c7; --primary-light: #e0f2fe; --blue: #3b82f6;
            --green: #22c55e; --purple: #a855f7; --orange: #f97316;
            --border: #e2e8f0; --bg: #ffffff; --bg-global: #f8fafc;
            --text-main: #1e293b; --text-muted: #64748b; --text-secondary: #475569;
        }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--bg-global); color: var(--text-main); margin: 0; display: flex; }
        .sidebar { width: 260px; background: #0f172a; color: #fff; min-height: 100vh; padding: 20px 0; box-sizing: border-box; }
        .sidebar-brand { padding: 0 24px 20px; font-size: 18px; font-weight: 700; border-bottom: 1px solid #1e293b; display: flex; align-items: center; gap: 10px; }
        .sidebar-menu { list-style: none; padding: 20px 12px; margin: 0; }
        .sidebar-item a { display: flex; align-items: center; gap: 12px; color: #94a3b8; padding: 12px 16px; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 500; transition: .2s; }
        .sidebar-item.active a, .sidebar-item a:hover { background: #1e293b; color: #fff; }
        .main-content { flex: 1; padding: 30px; box-sizing: border-box; }
        .page-header { margin-bottom: 24px; }
        .page-title { margin: 0 0 4px; font-size: 24px; font-weight: 700; }
        .page-subtitle { margin: 0; color: var(--text-muted); font-size: 14px; }
        .stat-card { background: var(--bg); border: 1px solid var(--border); border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .stat-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        .stat-blue .stat-icon { background: var(--primary-light); color: var(--primary); }
        .stat-green .stat-icon { background: #dcfce7; color: var(--green); }
        .stat-purple .stat-icon { background: #f3e5f5; color: var(--purple); }
        .stat-orange .stat-icon { background: #ffedd5; color: var(--orange); }
        .stat-number { display: block; font-size: 22px; font-weight: 700; }
        .stat-label { font-size: 12px; color: var(--text-muted); font-weight: 500; }
        .content-card { background: var(--bg); border: 1px solid var(--border); border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; }
        .card-header { padding: 16px 20px; border-bottom: 1px solid var(--border); }
        .card-header h3 { margin: 0; font-size: 15px; font-weight: 700; display: flex; align-items: center; }
        .card-body { padding: 20px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-graduation-cap" style="color:var(--primary);"></i> SIM SEKOLAH
        </div>
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('kepala_sekolah.dashboard') ? 'sidebar-item active' : 'sidebar-item' }}">
                <a href="{{ route('kepala_sekolah.dashboard') }}">
                    <i class="fas fa-chart-pie"></i> Dashboard
                </a>
            </li>
            <li class="{{ request()->routeIs('kepala_sekolah.laporan.kehadiran') ? 'sidebar-item active' : 'sidebar-item' }}">
                <a href="{{ route('kepala_sekolah.laporan.kehadiran') }}">
                    <i class="fas fa-calendar-check"></i> Kehadiran Bulanan
                </a>
            </li>
            <li class="{{ request()->routeIs('kepala_sekolah.laporan.nilai') ? 'sidebar-item active' : 'sidebar-item' }}">
                <a href="{{ route('kepala_sekolah.laporan.nilai') }}">
                    <i class="fas fa-star"></i> Laporan Nilai
                </a>
            </li>
            <li class="sidebar-item" style="margin-top: 50px;">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #ef4444;">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            </li>
        </ul>
    </div>
    <div class="main-content">
        @yield('content')
    </div>
    @stack('scripts')
</body>
</html>