<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') · CampusBazar Admin</title>
    <style>
        :root{
            --primary:#0b5d3b;--primary-dark:#08301f;--emerald:#10b981;--emerald-soft:#e8f7f0;
            --bg:#f4f7f5;--surface:#fff;--text:#0f172a;--muted:#64748b;--border:#e4eae6;
            --danger:#dc2626;--warning:#d97706;--info:#2563eb;--radius:14px;
        }
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;background:var(--bg);color:var(--text);line-height:1.55}
        a{color:inherit;text-decoration:none}
        button,input,select,textarea{font:inherit}
        img{max-width:100%;display:block}
        .admin{display:grid;grid-template-columns:250px 1fr;min-height:100vh}
        .sidebar{background:var(--primary-dark);color:#c9ddd3;padding:1.1rem .8rem;display:flex;flex-direction:column;gap:.25rem;position:sticky;top:0;height:100vh;overflow-y:auto}
        .sidebar .brand{display:flex;align-items:center;gap:.6rem;color:#fff;font-weight:800;font-size:1.15rem;padding:.4rem .6rem 1rem}
        .sidebar .brand .logo{width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,var(--emerald),#0f9d6e);display:grid;place-items:center;color:#fff}
        .sidebar .brand .logo svg{width:20px;height:20px}
        .sidebar .grp{font-size:.68rem;text-transform:uppercase;letter-spacing:.09em;color:#5f8574;padding:.9rem .7rem .3rem;font-weight:700}
        .sidebar a.nav{display:flex;align-items:center;gap:.6rem;padding:.55rem .7rem;border-radius:9px;font-size:.86rem;color:#bcd6c9;transition:.16s}
        .sidebar a.nav:hover{background:rgba(255,255,255,.08);color:#fff}
        .sidebar a.nav.active{background:var(--emerald);color:#04331f;font-weight:700}
        .sidebar a.nav svg{width:17px;height:17px;flex-shrink:0}
        .main{display:flex;flex-direction:column;min-width:0}
        .topbar{background:#fff;border-bottom:1px solid var(--border);padding:.8rem 1.5rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;position:sticky;top:0;z-index:50}
        .topbar h1{font-size:1.15rem}
        .content{padding:1.5rem;max-width:1250px;width:100%}
        .badge-stack{display:flex;gap:.4rem;flex-wrap:wrap}

        .row-between{display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap}
        .grid{display:grid;gap:1rem}
        .grid-2{grid-template-columns:repeat(2,1fr)}
        .grid-3{grid-template-columns:repeat(3,1fr)}
        .grid-4{grid-template-columns:repeat(4,1fr)}
        .card{background:#fff;border:1px solid var(--border);border-radius:var(--radius);box-shadow:0 1px 2px rgba(15,23,42,.04)}
        .card-pad{padding:1.2rem}
        .stat{padding:1rem 1.1rem;display:flex;flex-direction:column;gap:.25rem}
        .stat .label{font-size:.76rem;color:var(--muted);font-weight:500}
        .stat .value{font-size:1.6rem;font-weight:800;color:var(--primary)}
        .stat .value.amber{color:var(--warning)}.stat .value.blue{color:var(--info)}.stat .value.red{color:var(--danger)}
        .muted{color:var(--muted)}.small{font-size:.8rem}
        .mt-1{margin-top:.5rem}.mt-2{margin-top:1rem}.mt-3{margin-top:1.5rem}.mb-2{margin-bottom:1rem}.mb-3{margin-bottom:1.5rem}
        .btn{display:inline-flex;align-items:center;gap:.4rem;padding:.5rem .9rem;border-radius:9px;border:1px solid transparent;font-weight:600;font-size:.83rem;transition:.16s;cursor:pointer}
        .btn svg{width:15px;height:15px}
        .btn-primary{background:var(--primary);color:#fff}.btn-primary:hover{background:#094a2f}
        .btn-ghost{background:var(--emerald-soft);color:var(--primary)}.btn-ghost:hover{background:#d5efe3}
        .btn-danger{background:#fee2e2;color:var(--danger)}.btn-danger:hover{background:#fecaca}
        .btn-outline{background:#fff;border-color:var(--border);color:var(--text)}.btn-outline:hover{border-color:var(--emerald);color:var(--primary)}
        .btn-sm{padding:.34rem .6rem;font-size:.76rem}
        .pill{display:inline-flex;align-items:center;gap:.3rem;padding:.2rem .6rem;border-radius:999px;font-size:.72rem;font-weight:600}
        .pill-green{background:var(--emerald-soft);color:var(--primary)}
        .pill-amber{background:#fef3c7;color:#92400e}
        .pill-red{background:#fee2e2;color:#991b1b}
        .pill-blue{background:#dbeafe;color:#1e40af}
        .pill-gray{background:#eef2f0;color:#475569}
        .table-wrap{overflow-x:auto;border:1px solid var(--border);border-radius:var(--radius);background:#fff}
        table{width:100%;border-collapse:collapse;font-size:.84rem;min-width:600px}
        thead th{text-align:left;padding:.7rem .9rem;background:#f7faf8;font-weight:600;color:#334155;border-bottom:1px solid var(--border);white-space:nowrap}
        tbody td{padding:.7rem .9rem;border-bottom:1px solid var(--border);vertical-align:middle}
        tbody tr:last-child td{border-bottom:none}
        tbody tr:hover{background:#f9fcfb}
        .field{margin-bottom:.9rem;display:flex;flex-direction:column;gap:.3rem}
        .field label{font-weight:600;font-size:.83rem}
        .input,select.input,textarea.input{width:100%;padding:.55rem .75rem;border:1px solid var(--border);border-radius:9px;background:#fff;outline:none;transition:.16s}
        .input:focus{border-color:var(--emerald);box-shadow:0 0 0 3px rgba(16,185,129,.13)}
        textarea.input{resize:vertical;min-height:90px}
        .field-error{color:var(--danger);font-size:.76rem}
        .alert{padding:.8rem 1rem;border-radius:11px;font-size:.85rem;margin-bottom:1rem;border:1px solid transparent}
        .alert-success{background:#e8f7f0;color:#065f46;border-color:#bbf7d0}
        .alert-error{background:#fee2e2;color:#991b1b;border-color:#fecaca}
        .alert-warning{background:#fef3c7;color:#92400e;border-color:#fde68a}
        .tabs{display:flex;gap:.3rem;flex-wrap:wrap;border-bottom:1px solid var(--border);margin-bottom:1.1rem}
        .tabs a{padding:.5rem .85rem;font-size:.84rem;color:var(--muted);border-bottom:2px solid transparent;margin-bottom:-1px}
        .tabs a.active{color:var(--primary);border-bottom-color:var(--emerald);font-weight:600}
        .empty{text-align:center;padding:2.5rem 1rem;color:var(--muted)}
        .empty svg{width:48px;height:48px;margin:0 auto .6rem;color:#cbd5cf}
        .pager{display:flex;gap:.3rem;flex-wrap:wrap;justify-content:center;margin-top:1.25rem;list-style:none}
        .pager a,.pager span{display:grid;place-items:center;min-width:34px;height:34px;padding:0 .55rem;border:1px solid var(--border);border-radius:8px;font-size:.8rem;background:#fff;color:#475569}
        .pager a:hover{border-color:var(--emerald);color:var(--primary)}
        .pager .active span{background:var(--primary);border-color:var(--primary);color:#fff}
        .pager .disabled span{opacity:.45}
        .thumb-sm{width:46px;height:46px;border-radius:9px;object-fit:cover;background:#eef6f1}
        @media (max-width:820px){
            .admin{grid-template-columns:1fr}
            .sidebar{position:static;height:auto;flex-direction:row;flex-wrap:wrap}
            .sidebar .grp{display:none}
            .grid-3,.grid-4{grid-template-columns:repeat(2,1fr)}
            .grid-2{grid-template-columns:1fr}
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="admin">
    <aside class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="brand">
            <span class="logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9.5 12 4l9 5.5-9 5.5z"/><path d="M7 12v6l5 3 5-3v-6"/></svg>
            </span>
            CampusBazar
        </a>
        @php
            $nav = [
                ['label' => 'Overview', 'items' => [
                    ['admin.dashboard', 'Dashboard', 'grid'],
                ]],
                ['label' => 'Moderation', 'items' => [
                    ['admin.verifications.index', 'ID Verifications', 'check'],
                    ['admin.products.index', 'Products', 'box'],
                    ['admin.orders.index', 'Orders', 'cart'],
                    ['admin.cod.index', 'COD Payments', 'cash'],
                    ['admin.reports.index', 'Reports', 'flag'],
                    ['admin.reviews.index', 'Reviews', 'star'],
                ]],
                ['label' => 'Directory', 'items' => [
                    ['admin.users.index', 'Users', 'users'],
                    ['admin.campuses.index', 'Campuses', 'building'],
                    ['admin.categories.index', 'Categories', 'tag'],
                    ['admin.inquiries.index', 'Inquiries', 'mail'],
                    ['admin.audit.index', 'Audit Logs', 'log'],
                ]],
            ];
            $icons = [
                'grid' => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
                'check' => '<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>',
                'box' => '<path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/>',
                'cart' => '<circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.4 12.4a1 1 0 0 0 1 .8h9.2a1 1 0 0 0 1-.8L21 7H6"/>',
                'cash' => '<rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/><path d="M6 12h.01M18 12h.01"/>',
                'flag' => '<path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V4s-1 1-4 1-5-2-8-2-4 1-4 1z"/><path d="M4 22V4"/>',
                'star' => '<path d="m12 3 2.7 5.5 6.1.9-4.4 4.3 1 6-5.4-2.8L6.6 20l1-6L3.2 9.4l6.1-.9z"/>',
                'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.9"/>',
                'building' => '<rect x="4" y="2" width="16" height="20" rx="1"/><path d="M9 7h.01M15 7h.01M9 11h.01M15 11h.01M9 15h.01M15 15h.01"/>',
                'tag' => '<path d="M20.6 13.4 12 22l-9-9V3h10l7.6 7.6a2 2 0 0 1 0 2.8z"/><circle cx="7.5" cy="7.5" r="1.3"/>',
                'mail' => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6 10-6"/>',
                'log' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 13h6M9 17h6"/>',
            ];
        @endphp
        @foreach($nav as $group)
            <div class="grp">{{ $group['label'] }}</div>
            @foreach($group['items'] as $item)
                <a class="nav {{ request()->routeIs(explode('.', $item[0])[0] . '.' . (explode('.', $item[0])[1] ?? '') . '*') ? 'active' : '' }}" href="{{ route($item[0]) }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $icons[$item[2]] !!}</svg>
                    {{ $item[1] }}
                </a>
            @endforeach
        @endforeach
        <div class="grp">Account</div>
        <a class="nav" href="{{ route('home') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11l9-8 9 8"/><path d="M5 10v10h14V10"/></svg>
            View Site
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="nav" type="submit" style="width:100%;background:none;border:none;cursor:pointer;text-align:left">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5M21 12H9"/></svg>
                Log out
            </button>
        </form>
    </aside>

    <div class="main">
        <div class="topbar">
            <h1>@yield('heading', 'Admin')</h1>
            <div class="row-between" style="gap:.6rem">
                <a class="btn btn-outline btn-sm" href="{{ route('home') }}">View Site</a>
                <span class="avatar" style="width:34px;height:34px;border-radius:50%;background:var(--primary);color:#fff;display:grid;place-items:center;font-weight:700;font-size:.8rem">{{ auth()->user()->initials() }}</span>
            </div>
        </div>
        <div class="content">
            @include('partials.flash')
            @yield('content')
        </div>
    </div>
</div>
<script>
    document.querySelectorAll('form[data-confirm]').forEach(function (f) {
        f.addEventListener('submit', function (e) {
            if (!confirm(f.getAttribute('data-confirm'))) e.preventDefault();
        });
    });
</script>
@stack('scripts')
</body>
</html>
