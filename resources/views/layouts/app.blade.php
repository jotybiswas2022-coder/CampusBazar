<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CampusBazar') · Buy Smart. Sell Easy. Campus Connected.</title>
    <meta name="description" content="CampusBazar is a student-only marketplace for colleges and universities in Bangladesh. Buy and sell used textbooks, electronics, hostel essentials and more with Cash on Delivery.">
    @stack('meta')
    <script>document.documentElement.classList.add('has-js');</script>
    <style>
        :root{
            --primary:#0b5d3b;
            --primary-dark:#083d27;
            --primary-light:#127a4e;
            --emerald:#10b981;
            --emerald-light:#34d399;
            --emerald-soft:#e8f7f0;
            --bg:#f5f8f6;
            --surface:#ffffff;
            --text:#0f172a;
            --muted:#64748b;
            --border:#e4eae6;
            --danger:#dc2626;
            --warning:#d97706;
            --info:#2563eb;
            --radius:14px;
            --radius-sm:9px;
            --shadow-sm:0 1px 2px rgba(15,23,42,.05);
            --shadow:0 6px 20px rgba(11,93,59,.08);
            --shadow-lg:0 18px 45px rgba(11,93,59,.14);
            --maxw:1200px;
        }
        *{margin:0;padding:0;box-sizing:border-box}
        html{scroll-behavior:smooth}
        body{
            font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;
            background:var(--bg);color:var(--text);line-height:1.55;
            display:flex;flex-direction:column;min-height:100vh;-webkit-font-smoothing:antialiased;
        }
        a{color:inherit;text-decoration:none}
        img{max-width:100%;display:block}
        button{font:inherit;cursor:pointer}
        input,select,textarea{font:inherit}
        h1,h2,h3,h4{line-height:1.25;letter-spacing:-.01em}

        .container{width:100%;max-width:var(--maxw);margin:0 auto;padding:0 1rem}
        .page{flex:1;padding:1.75rem 0 3rem}
        .stack{display:flex;flex-direction:column;gap:1rem}
        .row{display:flex;gap:1rem;align-items:center}
        .row-between{display:flex;gap:1rem;align-items:center;justify-content:space-between;flex-wrap:wrap}
        .grid{display:grid;gap:1.1rem}
        .grid-3{grid-template-columns:repeat(3,1fr)}
        .grid-2{grid-template-columns:repeat(2,1fr)}
        .grid-4{grid-template-columns:repeat(4,1fr)}
        .muted{color:var(--muted)}
        .small{font-size:.82rem}
        .center{text-align:center}
        .mt-1{margin-top:.5rem}.mt-2{margin-top:1rem}.mt-3{margin-top:1.5rem}.mt-4{margin-top:2rem}
        .mb-1{margin-bottom:.5rem}.mb-2{margin-bottom:1rem}.mb-3{margin-bottom:1.5rem}
        .w-full{width:100%}
        .flex-1{flex:1}
        .hidden{display:none!important}

        /* Navbar */
        header.site-header{
            background:rgba(255,255,255,.92);backdrop-filter:blur(14px);
            border-bottom:1px solid var(--border);position:sticky;top:0;z-index:1000;
        }
        .nav-wrap{display:flex;align-items:center;gap:.75rem;height:66px;min-width:0}
        .brand{display:flex;align-items:center;gap:.6rem;font-weight:800;font-size:1.25rem;letter-spacing:-.03em;color:var(--primary);flex-shrink:0}
        .brand .logo{width:38px;height:38px;border-radius:11px;background:linear-gradient(135deg,var(--primary),var(--emerald));display:grid;place-items:center;color:#fff;flex-shrink:0;box-shadow:0 6px 14px rgba(11,93,59,.28)}
        .brand .logo svg{width:22px;height:22px}
        .brand-text{white-space:nowrap}
        .brand span em{color:var(--emerald);font-style:normal}
        .nav-search{flex:1 1 auto;min-width:0;max-width:420px;position:relative}
        .nav-search input{width:100%;padding:.6rem .9rem .6rem 2.3rem;border:1px solid var(--border);border-radius:999px;background:#f6faf8;outline:none;transition:.2s}
        .nav-search input:focus{border-color:var(--emerald);background:#fff;box-shadow:0 0 0 4px rgba(16,185,129,.12)}
        .nav-search svg{position:absolute;left:.8rem;top:50%;transform:translateY(-50%);width:16px;height:16px;color:var(--muted)}
        .nav-links{display:flex;align-items:center;gap:.15rem;flex-shrink:0}
        .nav-links a.navlink{display:inline-flex;align-items:center;gap:.4rem;padding:.5rem .7rem;border-radius:9px;font-size:.88rem;font-weight:500;color:#334155;transition:.18s}
        .nav-links a.navlink:hover{background:var(--emerald-soft);color:var(--primary)}
        .nav-links a.navlink.active{background:var(--emerald-soft);color:var(--primary);font-weight:600}
        .nav-links a.navlink svg{width:17px;height:17px}
        .nav-right{display:flex;align-items:center;gap:.4rem;margin-left:auto;flex-shrink:0}
        .nav-actions{display:flex;align-items:center;gap:.1rem}
        .avatar-btn{background:none;border:none;padding:0;gap:.5rem}
        .icon-btn{position:relative;display:inline-grid;place-items:center;width:40px;height:40px;border-radius:11px;border:1px solid transparent;background:transparent;color:#334155;transition:.18s}
        .icon-btn:hover{background:var(--emerald-soft);color:var(--primary)}
        .icon-btn svg{width:20px;height:20px}
        .badge-dot{position:absolute;top:4px;right:4px;min-width:17px;height:17px;padding:0 4px;border-radius:999px;background:var(--danger);color:#fff;font-size:.66rem;font-weight:700;display:grid;place-items:center;border:2px solid #fff}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:.45rem;padding:.6rem 1.1rem;border-radius:10px;border:1px solid transparent;font-weight:600;font-size:.88rem;transition:.18s;white-space:nowrap}
        .btn svg{width:17px;height:17px}
        .btn-primary{background:linear-gradient(135deg,var(--primary),var(--emerald));color:#fff;box-shadow:0 6px 16px rgba(11,93,59,.24)}
        .btn-primary:hover{transform:translateY(-1px);box-shadow:0 10px 22px rgba(11,93,59,.3)}
        .btn-outline{background:#fff;border-color:var(--border);color:var(--text)}
        .btn-outline:hover{border-color:var(--emerald);color:var(--primary)}
        .btn-ghost{background:var(--emerald-soft);color:var(--primary)}
        .btn-ghost:hover{background:#d6f0e4}
        .btn-danger{background:#fee2e2;color:var(--danger)}
        .btn-danger:hover{background:#fecaca}
        .btn-sm{padding:.4rem .7rem;font-size:.8rem}
        .btn:disabled{opacity:.55;cursor:not-allowed;transform:none}
        .menu{position:relative}
        .menu-panel{position:absolute;right:0;top:calc(100% + 8px);background:#fff;border:1px solid var(--border);border-radius:12px;box-shadow:var(--shadow-lg);min-width:220px;padding:.4rem;opacity:0;visibility:hidden;transform:translateY(-6px);transition:.16s;z-index:60}
        .menu.open .menu-panel{opacity:1;visibility:visible;transform:translateY(0)}
        .menu-panel a,.menu-panel button{display:flex;align-items:center;gap:.6rem;width:100%;padding:.6rem .7rem;border-radius:8px;font-size:.87rem;background:none;border:none;text-align:left;color:#334155}
        .menu-panel a:hover,.menu-panel button:hover{background:var(--emerald-soft);color:var(--primary)}
        .menu-panel svg{width:16px;height:16px}
        .menu-sep{height:1px;background:var(--border);margin:.35rem 0}
        .avatar{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--emerald));color:#fff;display:grid;place-items:center;font-weight:700;font-size:.85rem;flex-shrink:0}
        .mobile-toggle{display:none;background:none;border:none;color:var(--primary)}
        .mobile-nav{display:none;border-top:1px solid var(--border);margin-top:.1rem;padding:.85rem 0 .4rem}
        .mobile-search{position:relative;margin-bottom:.55rem}
        .mobile-search svg{position:absolute;left:.85rem;top:50%;transform:translateY(-50%);width:16px;height:16px;color:var(--muted)}
        .mobile-search input{width:100%;padding:.65rem .9rem .65rem 2.35rem;border:1px solid var(--border);border-radius:999px;background:#f6faf8;outline:none;transition:.2s}
        .mobile-search input:focus{border-color:var(--emerald);background:#fff;box-shadow:0 0 0 4px rgba(16,185,129,.12)}
        .mobile-links{display:flex;flex-direction:column}
        .mobile-links>a{display:flex;align-items:center;justify-content:space-between;gap:.5rem;padding:.62rem .55rem;border-radius:9px;font-size:.92rem;font-weight:500;color:#334155;transition:.16s}
        .mobile-links>a:hover{background:var(--emerald-soft);color:var(--primary)}
        .mobile-links>form>button{display:block;width:100%;text-align:left;padding:.62rem .55rem;border-radius:9px;font-size:.92rem;font-weight:600;color:var(--danger);background:none;border:none;transition:.16s}
        .mobile-links>form>button:hover{background:#fee2e2}
        .mobile-sep{height:1px;background:var(--border);margin:.4rem .1rem}
        .mobile-count{background:var(--emerald-soft);color:var(--primary);font-size:.72rem;font-weight:700;border-radius:999px;padding:.1rem .5rem}

        /* Cards & surfaces */
        .card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-sm)}
        .card-pad{padding:1.25rem}
        .card-hover{transition:.22s}
        .card-hover:hover{transform:translateY(-4px);box-shadow:var(--shadow);border-color:#c7e6d6}

        /* Product card */
        .product-card{overflow:hidden;display:flex;flex-direction:column;height:100%}
        .product-thumb{position:relative;aspect-ratio:4/3;background:linear-gradient(135deg,#eef6f1,#e2f1e9);overflow:hidden;display:grid;place-items:center;color:#9bb5a7}
        .product-thumb img{width:100%;height:100%;object-fit:cover;transition:.35s}
        .product-card:hover .product-thumb img{transform:scale(1.05)}
        .product-thumb .ph svg{width:52px;height:52px}
        .pill{display:inline-flex;align-items:center;gap:.3rem;padding:.22rem .6rem;border-radius:999px;font-size:.72rem;font-weight:600}
        .pill-green{background:var(--emerald-soft);color:var(--primary)}
        .pill-amber{background:#fef3c7;color:var(--warning)}
        .pill-red{background:#fee2e2;color:var(--danger)}
        .pill-blue{background:#dbeafe;color:var(--info)}
        .pill-gray{background:#eef2f0;color:#475569}
        .thumb-badges{position:absolute;top:.6rem;left:.6rem;display:flex;gap:.35rem;flex-wrap:wrap}
        .wish-toggle{position:absolute;top:.6rem;right:.6rem;width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,.92);display:grid;place-items:center;border:1px solid var(--border);color:#94a3b8}
        .wish-toggle:hover{color:var(--danger)}
        .wish-toggle.active{color:var(--danger)}
        .product-body{padding:.9rem 1rem 1.1rem;display:flex;flex-direction:column;gap:.4rem;flex:1}
        .product-title{font-weight:600;font-size:.98rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:2.5em}
        .product-price{font-weight:800;color:var(--primary);font-size:1.15rem}
        .product-meta{display:flex;justify-content:space-between;align-items:center;gap:.5rem;margin-top:auto;padding-top:.5rem;border-top:1px dashed var(--border);font-size:.78rem;color:var(--muted)}

        /* Scroll reveal — opt-in via .reveal. .has-js is set from the head so the
           initial hidden state applies before paint and never flashes. */
        .has-js .reveal{opacity:0;transform:translateY(26px);transition:opacity .75s cubic-bezier(.22,1,.36,1),transform .75s cubic-bezier(.22,1,.36,1);transition-delay:var(--d,0ms)}
        .has-js .reveal.in{opacity:1;transform:none}

        /* Forms */
        .field{display:flex;flex-direction:column;gap:.35rem;margin-bottom:1rem}
        .field label{font-weight:600;font-size:.85rem}
        .field .hint{font-size:.76rem;color:var(--muted)}
        .input,select.input,textarea.input{width:100%;padding:.62rem .8rem;border:1px solid var(--border);border-radius:10px;background:#fff;outline:none;transition:.18s}
        .input:focus,select.input:focus,textarea.input:focus{border-color:var(--emerald);box-shadow:0 0 0 4px rgba(16,185,129,.12)}
        textarea.input{resize:vertical;min-height:110px}
        .input-error{border-color:var(--danger)!important;box-shadow:0 0 0 4px rgba(220,38,38,.09)!important}
        .field-error{color:var(--danger);font-size:.78rem}
        .checkbox{display:flex;align-items:flex-start;gap:.6rem;font-size:.86rem}
        .checkbox input{margin-top:.2rem;width:17px;height:17px;accent-color:var(--primary)}

        /* Alerts */
        .alert{display:flex;gap:.65rem;align-items:flex-start;padding:.85rem 1rem;border-radius:12px;font-size:.88rem;border:1px solid transparent}
        .alert svg{width:18px;height:18px;flex-shrink:0;margin-top:1px}
        .alert-success{background:#e8f7f0;color:#065f46;border-color:#bbf7d0}
        .alert-error{background:#fee2e2;color:#991b1b;border-color:#fecaca}
        .alert-warning{background:#fef3c7;color:#92400e;border-color:#fde68a}
        .alert-info{background:#dbeafe;color:#1e40af;border-color:#bfdbfe}

        /* Section headings */
        .section-head{display:flex;align-items:flex-end;justify-content:space-between;gap:1rem;margin-bottom:1.1rem;flex-wrap:wrap}
        .section-head h2{font-size:1.35rem}
        .eyebrow{font-size:.75rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--emerald)}

        /* Table */
        .table-wrap{overflow-x:auto;border:1px solid var(--border);border-radius:var(--radius);background:#fff}
        table{width:100%;border-collapse:collapse;font-size:.86rem;min-width:640px}
        thead th{text-align:left;padding:.8rem 1rem;background:#f7faf8;font-weight:600;color:#334155;border-bottom:1px solid var(--border);white-space:nowrap}
        tbody td{padding:.8rem 1rem;border-bottom:1px solid var(--border);vertical-align:middle}
        tbody tr:last-child td{border-bottom:none}
        tbody tr:hover{background:#f9fcfb}

        /* Stats */
        .stat{background:#fff;border:1px solid var(--border);border-radius:var(--radius);padding:1.1rem 1.2rem;display:flex;flex-direction:column;gap:.3rem}
        .stat .label{font-size:.78rem;color:var(--muted);font-weight:500}
        .stat .value{font-size:1.7rem;font-weight:800;letter-spacing:-.02em;color:var(--primary)}
        .stat .value.amber{color:var(--warning)}
        .stat .value.blue{color:var(--info)}
        .stat .value.red{color:var(--danger)}

        /* Empty state */
        .empty{text-align:center;padding:3rem 1.5rem;color:var(--muted)}
        .empty svg{width:54px;height:54px;margin:0 auto .8rem;color:#cbd5cf}
        .empty h3{color:var(--text);margin-bottom:.3rem}

        /* Tabs */
        .tabs{display:flex;gap:.3rem;flex-wrap:wrap;border-bottom:1px solid var(--border);margin-bottom:1.25rem}
        .tabs a{padding:.55rem .9rem;font-size:.87rem;font-weight:500;color:var(--muted);border-bottom:2px solid transparent;margin-bottom:-1px}
        .tabs a:hover{color:var(--primary)}
        .tabs a.active{color:var(--primary);border-bottom-color:var(--emerald);font-weight:600}

        /* Pagination */
        .pager{display:flex;gap:.3rem;flex-wrap:wrap;justify-content:center;margin-top:1.5rem;list-style:none}
        .pager a,.pager span{display:grid;place-items:center;min-width:36px;height:36px;padding:0 .6rem;border:1px solid var(--border);border-radius:9px;font-size:.83rem;background:#fff;color:#475569}
        .pager a:hover{border-color:var(--emerald);color:var(--primary)}
        .pager .active span{background:var(--primary);border-color:var(--primary);color:#fff}
        .pager .disabled span{opacity:.45}

        /* Footer */
        footer.site-footer{background:#08301f;color:#c9ddd3;margin-top:auto}
        .footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:2rem;padding:2.5rem 0 1.5rem}
        footer.site-footer h4{color:#fff;font-size:.9rem;margin-bottom:.8rem}
        footer.site-footer a{display:block;padding:.22rem 0;font-size:.85rem;color:#a9c7b8}
        footer.site-footer a:hover{color:#fff}
        footer .brand{color:#fff}
        .footer-bottom{border-top:1px solid rgba(255,255,255,.12);padding:1rem 0;font-size:.8rem;display:flex;justify-content:space-between;gap:1rem;flex-wrap:wrap}
        .cod-note{display:inline-flex;align-items:center;gap:.45rem;background:rgba(16,185,129,.16);color:#7fe3bd;padding:.3rem .7rem;border-radius:999px;font-size:.78rem;font-weight:600}

        .hero{background:linear-gradient(135deg,#083d27,#0b5d3b 45%,#127a4e);color:#fff;border-radius:20px;padding:2.6rem;position:relative;overflow:hidden}
        .hero::after{content:"";position:absolute;right:-80px;top:-80px;width:320px;height:320px;border-radius:50%;background:radial-gradient(circle,rgba(16,185,129,.5),transparent 70%)}
        .hero h1{font-size:2.1rem;max-width:640px}
        .hero p{color:#c9ddd3;max-width:560px;margin-top:.6rem}

        .filters{display:grid;grid-template-columns:repeat(5,1fr);gap:.7rem;align-items:end}
        .gallery{display:grid;grid-template-columns:repeat(4,1fr);gap:.6rem}
        .gallery .main{grid-column:1/-1;aspect-ratio:16/10;border-radius:14px;overflow:hidden;background:#eef6f1;display:grid;place-items:center}
        .gallery .main img{width:100%;height:100%;object-fit:cover}
        .gallery .thumb{aspect-ratio:1;border-radius:10px;overflow:hidden;border:1px solid var(--border);cursor:pointer;background:#eef6f1;display:grid;place-items:center}
        .gallery .thumb img{width:100%;height:100%;object-fit:cover}

        @media (max-width:960px){
            .grid-4{grid-template-columns:repeat(3,1fr)}
            .filters{grid-template-columns:repeat(3,1fr)}
            .footer-grid{grid-template-columns:1fr 1fr}
        }
        @media (max-width:760px){
            .nav-search{display:none}
            .nav-links{display:none}
            .nav-actions,.nav-sell{display:none}
            .mobile-toggle{display:grid}
            .mobile-nav.open{display:block}
            .grid-3,.grid-2{grid-template-columns:1fr}
            .grid-4{grid-template-columns:repeat(2,1fr)}
            /* .grid-4 is only ~216px per card between 481px and 760px, so dial the
               product card down a notch. Keeps type monotonic across 480px. */
            .product-grid .product-body{padding:.8rem .85rem .9rem}
            .product-grid .product-title{font-size:.88rem}
            .product-grid .product-price{font-size:1.05rem}
            .product-grid .btn-sm{padding:.36rem .6rem;font-size:.75rem}
            .filters{grid-template-columns:1fr 1fr}
            .hero h1{font-size:1.55rem}
        }
        @media (max-width:520px){
            .brand{font-size:1.1rem}
            .nav-login,.nav-register{display:none}
        }
        /* .grid-4 falls to a single full-width card per row on phones, which makes
           a page of product cards several times longer to scroll. Product grids
           keep two compact columns instead. */
        @media (max-width:480px){
            .grid-4,.grid-2{grid-template-columns:1fr}
            .grid.product-grid{grid-template-columns:repeat(2,1fr);gap:.7rem}
            .product-grid .product-body{padding:.6rem .65rem .7rem;gap:.3rem}
            .product-grid .product-title{font-size:.82rem;line-height:1.35;min-height:2.7em}
            .product-grid .product-price{font-size:.98rem}
            .product-grid .product-meta{display:none}
            .product-grid .thumb-badges{gap:.25rem}
            .product-grid .thumb-badges .pill{font-size:.6rem;padding:.16rem .42rem}
            .product-grid .row-between{gap:.4rem}
            .product-grid .btn-sm{padding:.34rem .55rem;font-size:.72rem}
            .filters{grid-template-columns:1fr}
            .footer-grid{grid-template-columns:1fr}
        }
        @media (prefers-reduced-motion:reduce){
            *{transition:none!important;animation:none!important}
            .has-js .reveal{opacity:1!important;transform:none!important}
        }
    </style>
    @stack('styles')
</head>
<body>
    @include('partials.nav')

    @stack('fullbleed-top')

    <main class="page">
        <div class="container">
            @include('partials.flash')
            @yield('content')
        </div>
    </main>

    @include('partials.footer')

    <script>
        document.addEventListener('click', function (e) {
            document.querySelectorAll('.menu.open').forEach(function (menu) {
                if (!menu.contains(e.target)) menu.classList.remove('open');
            });
        });
        document.querySelectorAll('[data-menu-toggle]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                var menu = btn.closest('.menu');
                document.querySelectorAll('.menu.open').forEach(function (m) { if (m !== menu) m.classList.remove('open'); });
                menu.classList.toggle('open');
            });
        });
        var mt = document.getElementById('mobileToggle');
        var mn = document.getElementById('mobileNav');
        if (mt && mn) {
            mt.addEventListener('click', function (e) {
                e.preventDefault();
                var open = mn.classList.toggle('open');
                mt.setAttribute('aria-expanded', open ? 'true' : 'false');
            });
            mn.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () {
                    mn.classList.remove('open');
                    mt.setAttribute('aria-expanded', 'false');
                });
            });
        }

        /* Scroll reveal (shared) */
        (function () {
            var reveals = document.querySelectorAll('.reveal');
            if (!reveals.length) return;
            var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (reduce || !('IntersectionObserver' in window)) {
                reveals.forEach(function (el) { el.classList.add('in'); });
                return;
            }
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in');
                        io.unobserve(entry.target);
                    }
                });
            }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
            reveals.forEach(function (el) { io.observe(el); });
        })();
    </script>
    @stack('scripts')
</body>
</html>
