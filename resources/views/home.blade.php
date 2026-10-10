@extends('layouts.app')
@section('title', 'CampusBazar · Student Marketplace in Bangladesh')

@push('styles')
<style>
    /* ============================================================
       Home — full-bleed editorial hero
       ============================================================ */
    .home-hero{
        position:relative;isolation:isolate;overflow:hidden;color:#fff;text-align:center;
        padding:4.6rem 1rem 5.6rem;
        background:linear-gradient(165deg,#041f14 0%,#07331f 54%,#0a4a2e 100%);
    }
    .home-hero .hero-glow{
        position:absolute;left:50%;top:-42%;width:min(1180px,130vw);height:min(1180px,130vw);
        transform:translateX(-50%);border-radius:50%;z-index:0;pointer-events:none;
        background:radial-gradient(circle,rgba(16,185,129,.3),rgba(16,185,129,.07) 46%,transparent 68%);
    }
    .home-hero .hero-lines{
        position:absolute;inset:0;z-index:0;pointer-events:none;opacity:.55;
        background-image:linear-gradient(rgba(255,255,255,.06) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.06) 1px,transparent 1px);
        background-size:64px 64px;
        -webkit-mask-image:radial-gradient(88% 72% at 50% 8%,#000,transparent 74%);
        mask-image:radial-gradient(88% 72% at 50% 8%,#000,transparent 74%);
    }
    .home-hero .hero-sweep{
        position:absolute;top:-40%;left:-35%;width:55%;height:180%;z-index:0;pointer-events:none;
        background:linear-gradient(90deg,transparent,rgba(52,211,153,.15),transparent);
        filter:blur(26px);transform:rotate(16deg);
        animation:sweep 18s ease-in-out infinite;
    }
    @keyframes sweep{0%,100%{transform:translateX(0) rotate(16deg)}50%{transform:translateX(140vw) rotate(16deg)}}
    @keyframes auraA{0%,100%{transform:translate(0,0) scale(1)}50%{transform:translate(32px,26px) scale(1.12)}}
    @keyframes auraB{0%,100%{transform:translate(0,0) scale(1.05)}50%{transform:translate(-36px,20px) scale(.94)}}

    .hero-inner{position:relative;z-index:2;max-width:840px;margin:0 auto}

    .hero-badge{display:inline-flex;align-items:center;gap:.5rem;padding:.4rem .85rem;border-radius:999px;background:rgba(16,185,129,.16);border:1px solid rgba(52,211,153,.32);color:#8ff0c6;font-size:.78rem;font-weight:600;letter-spacing:.02em}
    .hero-badge .dot{width:7px;height:7px;border-radius:50%;background:#34d399;animation:pulseDot 2.2s infinite}
    @keyframes pulseDot{0%{box-shadow:0 0 0 0 rgba(52,211,153,.65)}70%{box-shadow:0 0 0 9px rgba(52,211,153,0)}100%{box-shadow:0 0 0 0 rgba(52,211,153,0)}}

    .home-hero h1{font-size:3.85rem;line-height:1.03;letter-spacing:-.04em;font-weight:800;margin-top:1.15rem}
    .home-hero h1 .grad{background:linear-gradient(100deg,#6ee7b7,#34d399 45%,#a7f3d0);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;color:transparent}
    .home-hero .lede{color:#bfe0d2;max-width:56ch;margin:1.15rem auto 0;font-size:1.06rem}

    .hero-search{display:flex;align-items:center;gap:.55rem;margin:2.1rem auto 0;max-width:640px;padding:.45rem .45rem .45rem 1.25rem;border-radius:999px;background:rgba(255,255,255,.09);border:1px solid rgba(255,255,255,.2);backdrop-filter:blur(10px);transition:.25s}
    .hero-search:focus-within{border-color:rgba(52,211,153,.65);background:rgba(255,255,255,.15);box-shadow:0 0 0 5px rgba(16,185,129,.15)}
    .hero-search>svg{width:19px;height:19px;color:#8ff0c6;flex-shrink:0}
    .hero-search input{flex:1;min-width:0;border:none;outline:none;background:none;color:#fff;padding:.6rem 0;font-size:1rem}
    .hero-search input::placeholder{color:rgba(191,224,210,.72)}
    .hero-search .go{padding:.7rem 1.5rem;border-radius:999px;border:none;background:linear-gradient(135deg,#10b981,#0b5d3b);color:#fff;font-weight:700;font-size:.88rem;white-space:nowrap;transition:.2s}
    .hero-search .go:hover{filter:brightness(1.12)}

    .hero-actions{display:flex;gap:.7rem;flex-wrap:wrap;justify-content:center;margin-top:1.5rem}
    .hero-actions .btn-primary{box-shadow:0 10px 26px -10px rgba(16,185,129,.6)}
    .hero-actions .btn-outline{background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.28);color:#fff}
    .hero-actions .btn-outline:hover{border-color:#34d399;background:rgba(52,211,153,.14);color:#fff}

    /* Floating stat card straddling the hero's bottom edge */
    .hero-strip-wrap{position:relative;z-index:3;max-width:940px;margin:-2.7rem auto 0;padding:0 1rem;animation:riseIn .9s cubic-bezier(.22,1,.36,1) .32s both}
    .hero-strip{display:grid;grid-template-columns:repeat(3,1fr);background:#fff;border:1px solid var(--border);border-radius:18px;overflow:hidden;box-shadow:0 26px 54px -30px rgba(8,61,39,.45)}
    .hero-strip .hs{display:flex;flex-direction:column;align-items:center;gap:.12rem;padding:1.2rem 1rem}
    .hero-strip .hs + .hs{border-left:1px solid var(--border)}
    .hero-strip .hs b{font-size:1.7rem;font-weight:800;letter-spacing:-.02em;color:var(--primary);line-height:1.1}
    .hero-strip .hs span{font-size:.76rem;color:var(--muted);font-weight:500}

    /* Hero entrances */
    @keyframes riseIn{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:none}}
    .hero-inner>*{animation:riseIn .85s cubic-bezier(.22,1,.36,1) both}
    .hero-inner>*:nth-child(2){animation-delay:.08s}
    .hero-inner>*:nth-child(3){animation-delay:.16s}
    .hero-inner>*:nth-child(4){animation-delay:.24s}
    .hero-inner>*:nth-child(5){animation-delay:.32s}

    /* ============================================================
       Trust marquee
       ============================================================ */
    .trust-strip{
        margin-top:1.6rem;background:#fff;border:1px solid var(--border);border-radius:16px;padding:.85rem 0;overflow:hidden;
        -webkit-mask-image:linear-gradient(90deg,transparent,#000 7%,#000 93%,transparent);
        mask-image:linear-gradient(90deg,transparent,#000 7%,#000 93%,transparent);
    }
    .trust-track{display:flex;align-items:center;gap:2.6rem;width:max-content;animation:marquee 30s linear infinite}
    .trust-strip:hover .trust-track{animation-play-state:paused}
    .trust-item{display:inline-flex;align-items:center;gap:.5rem;font-size:.83rem;font-weight:600;color:#334155;white-space:nowrap}
    .trust-item svg{width:16px;height:16px;color:var(--emerald);flex-shrink:0}
    @keyframes marquee{from{transform:translateX(0)}to{transform:translateX(-50%)}}

    /* ============================================================
       Scroll reveal
       ============================================================ */
    .home-sec{margin-top:2rem}
    .section-head h2{font-size:1.5rem;letter-spacing:-.02em}
    .has-js .reveal{opacity:0;transform:translateY(26px);transition:opacity .75s cubic-bezier(.22,1,.36,1),transform .75s cubic-bezier(.22,1,.36,1);transition-delay:var(--d,0ms)}
    .has-js .reveal.in{opacity:1;transform:none}

    /* ============================================================
       Categories
       ============================================================ */
    .cat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1.05rem}
    .cat-chip{position:relative;display:flex;flex-direction:column;gap:.7rem;align-items:flex-start;padding:1.15rem;border-radius:16px;background:#fff;border:1px solid var(--border);overflow:hidden;transition:transform .3s cubic-bezier(.22,1,.36,1),box-shadow .3s,border-color .3s}
    .cat-chip::before{content:"";position:absolute;inset:0;background:linear-gradient(135deg,rgba(16,185,129,.1),transparent 62%);opacity:0;transition:.3s}
    .cat-chip:hover{transform:translateY(-5px);border-color:#c7e6d6;box-shadow:0 22px 42px -24px rgba(11,93,59,.42)}
    .cat-chip:hover::before{opacity:1}
    .cat-chip .ic{width:46px;height:46px;border-radius:13px;background:linear-gradient(135deg,var(--emerald-soft),#d6f0e4);display:grid;place-items:center;color:var(--primary);transition:.35s}
    .cat-chip:hover .ic{transform:scale(1.06) rotate(-4deg);background:linear-gradient(135deg,var(--primary),var(--emerald));color:#fff}
    .cat-chip .ic svg{width:23px;height:23px}
    .cat-chip b{font-size:.92rem;line-height:1.3}
    .cat-chip .cnt{font-size:.75rem;color:var(--muted)}
    .cat-chip .arw{position:absolute;right:1rem;top:1.2rem;color:var(--emerald);opacity:0;transform:translateX(-6px);transition:.3s}
    .cat-chip:hover .arw{opacity:1;transform:none}
    .cat-chip .arw svg{width:16px;height:16px}

    /* ============================================================
       Campus cards
       ============================================================ */
    .campus-card{position:relative;overflow:hidden;display:flex;flex-direction:column;gap:.5rem}
    .campus-card .bar{position:absolute;top:0;bottom:0;left:0;width:4px;background:linear-gradient(180deg,var(--emerald),var(--primary));transform:scaleY(.32);transform-origin:top;transition:transform .45s cubic-bezier(.22,1,.36,1)}
    .campus-card:hover .bar{transform:scaleY(1)}
    .campus-card .mono{width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--emerald));color:#fff;display:grid;place-items:center;font-weight:800;font-size:.95rem;flex-shrink:0}

    /* ============================================================
       How it works
       ============================================================ */
    .steps{display:grid;grid-template-columns:repeat(3,1fr);gap:1.2rem;position:relative}
    .steps::before{content:"";position:absolute;top:2.6rem;left:12%;right:12%;height:2px;background:repeating-linear-gradient(90deg,var(--emerald) 0 8px,transparent 8px 16px);opacity:.35;z-index:0}
    .step{position:relative;z-index:1;padding:1.5rem;border-radius:18px;background:#fff;border:1px solid var(--border);transition:transform .3s cubic-bezier(.22,1,.36,1),box-shadow .3s}
    .step:hover{transform:translateY(-5px);box-shadow:0 24px 48px -26px rgba(11,93,59,.45)}
    .step .n{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--emerald));color:#fff;display:grid;place-items:center;font-weight:800;margin-bottom:.85rem;box-shadow:0 12px 24px -12px rgba(11,93,59,.75)}
    .step b{font-size:1rem}
    .step p{margin-top:.35rem}

    /* ============================================================
       Feature grid
       ============================================================ */
    .feature-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1.05rem}
    .feature{padding:1.35rem;border-radius:16px;background:#fff;border:1px solid var(--border);display:flex;flex-direction:column;gap:.65rem;transition:transform .3s cubic-bezier(.22,1,.36,1),box-shadow .3s,border-color .3s}
    .feature:hover{transform:translateY(-5px);border-color:#c7e6d6;box-shadow:0 22px 44px -26px rgba(11,93,59,.42)}
    .feature .fi{width:42px;height:42px;border-radius:12px;background:var(--emerald-soft);color:var(--primary);display:grid;place-items:center;flex-shrink:0}
    .feature .fi svg{width:21px;height:21px}
    .feature b{font-size:.95rem}
    .feature p{font-size:.83rem;color:var(--muted);line-height:1.5}

    /* ============================================================
       CTA band
       ============================================================ */
    .cta-band{position:relative;overflow:hidden;background:linear-gradient(120deg,#052a1a,#0b5d3b 45%,#0f7a51);color:#fff;border-radius:24px;padding:2.6rem;display:flex;align-items:center;justify-content:space-between;gap:1.6rem;flex-wrap:wrap}
    .cta-band::before{content:"";position:absolute;width:520px;height:520px;right:-160px;top:-250px;border-radius:50%;background:radial-gradient(circle,rgba(52,211,153,.4),transparent 65%);animation:auraB 20s ease-in-out infinite}
    .cta-band::after{content:"";position:absolute;width:340px;height:340px;left:-140px;bottom:-220px;border-radius:50%;background:radial-gradient(circle,rgba(16,185,129,.35),transparent 65%);animation:auraA 24s ease-in-out infinite}
    .cta-band>*{position:relative;z-index:1}
    .cta-band h2{font-size:1.7rem;letter-spacing:-.02em}
    .cta-band p{color:#bfe0d2;margin-top:.35rem;max-width:48ch}

    /* ============================================================
       Responsive
       ============================================================ */
    @media (max-width:1000px){
        .home-hero h1{font-size:2.9rem}
        .feature-grid{grid-template-columns:repeat(2,1fr)}
    }
    @media (max-width:860px){
        .cat-grid{grid-template-columns:repeat(3,1fr)}
    }

    /* ---------- Mobile: keep it minimal & clean ---------- */
    @media (max-width:760px){
        .home-hero{padding:2.4rem 1.1rem 3.8rem}
        .home-hero .hero-glow,.home-hero .hero-lines,.home-hero .hero-sweep{display:none}
        .hero-badge{font-size:.72rem;padding:.32rem .7rem}
        .home-hero h1{font-size:1.8rem;margin-top:.85rem;letter-spacing:-.03em}
        .home-hero .lede{font-size:.9rem;margin-top:.75rem}
        .hero-search{margin-top:1.25rem;padding:.3rem .3rem .3rem .9rem;gap:.4rem;max-width:none}
        .hero-search input{font-size:.88rem;padding:.55rem 0}
        .hero-search .go{padding:.55rem .95rem;font-size:.82rem}
        .hero-actions{margin-top:1rem;gap:.55rem}
        .hero-actions .btn{flex:1 1 auto;justify-content:center}
        .hero-strip-wrap{margin-top:-2rem;padding:0 .9rem}
        .hero-strip{border-radius:14px}
        .hero-strip .hs{padding:.85rem .5rem}
        .hero-strip .hs b{font-size:1.2rem}
        .hero-strip .hs span{font-size:.65rem}

        /* decorative bits stay out of the way */
        .trust-strip{display:none}

        .home-sec{margin-top:1.6rem}
        .section-head{margin-bottom:.85rem}
        .section-head h2{font-size:1.18rem}
        .section-head .eyebrow{font-size:.7rem}

        .cat-grid{grid-template-columns:repeat(2,1fr);gap:.7rem}
        .cat-chip{padding:.85rem;border-radius:14px;gap:.5rem}
        .cat-chip:hover{transform:none;box-shadow:none}
        .cat-chip .ic{width:38px;height:38px;border-radius:11px}
        .cat-chip .ic svg{width:19px;height:19px}
        .cat-chip b{font-size:.82rem}
        .cat-chip .cnt{font-size:.7rem}
        .cat-chip .arw{display:none}

        .campus-card .bar{transform:scaleY(1)}
        .campus-card .mono{width:36px;height:36px;border-radius:10px;font-size:.85rem}

        .steps{grid-template-columns:1fr;gap:.7rem}
        .steps::before{display:none}
        .step{padding:1.05rem 1.15rem;border-radius:14px}
        .step:hover{transform:none;box-shadow:none}
        .step .n{width:32px;height:32px;border-radius:10px;font-size:.85rem;margin-bottom:.6rem}
        .step b{font-size:.93rem}

        .feature-grid{grid-template-columns:1fr;gap:.7rem}
        .feature{flex-direction:row;align-items:flex-start;gap:.85rem;padding:1.05rem 1.1rem;border-radius:14px}
        .feature:hover{transform:none;box-shadow:none;border-color:var(--border)}
        .feature .fi{width:38px;height:38px;border-radius:11px}
        .feature .fi svg{width:19px;height:19px}
        .feature b{font-size:.9rem}
        .feature p{font-size:.79rem;margin-top:.15rem}

        .cta-band{padding:1.8rem 1.35rem;border-radius:18px;flex-direction:column;align-items:flex-start;gap:1.1rem}
        .cta-band::before,.cta-band::after{display:none}
        .cta-band h2{font-size:1.3rem}
        .cta-band p{font-size:.87rem}
        .cta-band .btn{width:100%;justify-content:center}
    }
    /* .grid-4 is already two columns between 481px and 760px, but the card is
       only ~216px wide there, so dial it down a notch. This tier exists to keep
       type sizes monotonic across the 480px boundary below. */
    @media (max-width:760px){
        .featured-grid .product-body{padding:.8rem .85rem .9rem}
        .featured-grid .product-title{font-size:.88rem}
        .featured-grid .product-price{font-size:1.05rem}
        .featured-grid .btn-sm{padding:.36rem .6rem;font-size:.75rem}
    }

    /* ---------- "Recently listed" grid on phones ----------
       The shared .grid-4 collapses to a single full-width card per row below
       480px, which stacks the four listings into ~2000px of scrolling. Keep two
       compact columns here instead; scoped to this section so the dashboards,
       wishlist and marketplace grids that share .grid-4 are left alone. */
    @media (max-width:480px){
        .grid.featured-grid{grid-template-columns:repeat(2,1fr);gap:.7rem}
        .featured-grid .product-body{padding:.6rem .65rem .7rem;gap:.3rem}
        .featured-grid .product-title{font-size:.82rem;line-height:1.35;min-height:2.7em}
        .featured-grid .product-price{font-size:.98rem}
        .featured-grid .product-meta{display:none}
        .featured-grid .thumb-badges{gap:.25rem}
        .featured-grid .thumb-badges .pill{font-size:.6rem;padding:.16rem .42rem}
        .featured-grid .row-between{gap:.4rem}
        .featured-grid .btn-sm{padding:.34rem .55rem;font-size:.72rem}
    }

    @media (max-width:400px){
        .home-hero h1{font-size:1.6rem}
        .hero-strip .hs b{font-size:1.05rem}
        .hero-strip .hs span{font-size:.6rem}
    }

    /* Reduced motion: nothing hidden, no looping animation */
    @media (prefers-reduced-motion:reduce){
        .has-js .reveal{opacity:1!important;transform:none!important}
        .home-hero .hero-sweep,.trust-track,.cta-band::before,.cta-band::after{animation:none!important}
    }
</style>
@endpush

@push('fullbleed-top')
<section class="home-hero">
    <span class="hero-glow"></span>
    <span class="hero-lines"></span>
    <span class="hero-sweep"></span>

    <div class="hero-inner">
        <span class="hero-badge"><span class="dot"></span> Student-only marketplace · Bangladesh</span>
        <h1>Buy smart. Sell easy.<br><span class="grad">Stay campus connected.</span></h1>
        <p class="lede">Textbooks, calculators, electronics and hostel essentials from verified students at your campus — all with Cash on Delivery.</p>

        <form class="hero-search" action="{{ route('marketplace.index') }}" method="GET" role="search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search books, calculators, cycles…" aria-label="Search products">
            <button class="go" type="submit">Search</button>
        </form>

        <div class="hero-actions">
            <a class="btn btn-primary" href="{{ route('marketplace.index') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l1-5h16l1 5"/><path d="M4 9v11h16V9"/><path d="M9 13h6"/></svg>
                Browse Marketplace
            </a>
            <a class="btn btn-outline" href="{{ route('seller.products.create') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                Start Selling
            </a>
        </div>
    </div>
</section>

<div class="hero-strip-wrap">
    <div class="hero-strip">
        <div class="hs"><b><span data-count-to="{{ $stats['products'] }}">{{ number_format($stats['products']) }}</span>+</b><span>Active listings</span></div>
        <div class="hs"><b><span data-count-to="{{ $stats['students'] }}">{{ number_format($stats['students']) }}</span>+</b><span>Verified students</span></div>
        <div class="hs"><b><span data-count-to="{{ $stats['campuses'] }}">{{ number_format($stats['campuses']) }}</span>+</b><span>Campuses</span></div>
    </div>
</div>
@endpush

@section('content')
<script>document.documentElement.classList.add('has-js');</script>

@php
    $catIcons = [
        'book' => '<path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H20v16H6.5A2.5 2.5 0 0 0 4 20.5z"/><path d="M8 7h8M8 11h5"/>',
        'device' => '<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M2 20h20"/>',
        'calculator' => '<rect x="4" y="2" width="16" height="20" rx="2.5"/><path d="M8 6h8v3H8z"/><path d="M8.5 13h.01M12 13h.01M15.5 13h.01M8.5 17h.01M12 17h.01M15.5 17h.01"/>',
        'bike' => '<circle cx="5.5" cy="17.5" r="3.5"/><circle cx="18.5" cy="17.5" r="3.5"/><path d="M14.5 6H18l-3.2 11.2"/><path d="M8.5 6H12l-2.6 8.6"/><path d="M9.5 6h5"/>',
        'home' => '<path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/><path d="M10 21v-5.5h4V21"/>',
        'shirt' => '<path d="M16 2.5 20 5l-1.8 4.6-2.2-1V21H8V8.6L5.8 9.6 4 5l4-2.5 4 3z"/>',
        'ball' => '<circle cx="12" cy="12" r="9"/><path d="M12 3v6.5M4.6 8.2l5.5 3.1M19.4 8.2l-5.5 3.1M7.2 20.4l2.9-6.6M16.8 20.4l-2.9-6.6"/>',
        'pen' => '<path d="M3 21l4.2-1.1L19 8.1a2.8 2.8 0 0 0-4-4L3.2 16z"/><path d="M14.6 6.6l2.8 2.8"/>',
        'box' => '<path d="M21 8v8a2 2 0 0 1-1 1.7l-7 4a2 2 0 0 1-2 0l-7-4A2 2 0 0 1 3 16V8a2 2 0 0 1 1-1.7l7-4a2 2 0 0 1 2 0l7 4A2 2 0 0 1 21 8z"/><path d="M3.3 7 12 12l8.7-5"/><path d="M12 22V12"/>',
        '__default' => '<path d="M20.6 13.4 12 22l-9-9V4h9z"/><circle cx="7.5" cy="7.5" r="1.5"/>',
    ];
    $trust = [
        ['icon' => '<path d="M20 6 9 17l-5-5"/>', 'text' => 'Cash on Delivery only'],
        ['icon' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>', 'text' => 'Verified student sellers'],
        ['icon' => '<path d="M3 9.5 12 4l9 5.5-9 5.5z"/><path d="M7 12v6l5 3 5-3v-6"/>', 'text' => 'Campus-only listings'],
        ['icon' => '<path d="M12 5v14M5 12h14"/>', 'text' => 'Free to list an item'],
        ['icon' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>', 'text' => 'Chat with the seller'],
        ['icon' => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>', 'text' => 'Deals near your campus'],
    ];

    $features = [
        ['icon' => '<rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="3"/><path d="M6 12h.01M18 12h.01"/>', 'title' => 'Cash on Delivery', 'text' => 'Pay only when the item is in your hands. No online payment, no risk.'],
        ['icon' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>', 'title' => 'Verified students', 'text' => 'Every seller uploads a valid student ID before their listing goes live.'],
        ['icon' => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>', 'title' => 'Campus-first', 'text' => 'Filter by your own college or university and deal with people nearby.'],
        ['icon' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>', 'title' => 'Free & unlimited', 'text' => 'Listing and messaging cost nothing. Keep all of what you sell.'],
    ];
@endphp

<div class="trust-strip" aria-hidden="true">
    <div class="trust-track">
        @for($i = 0; $i < 2; $i++)
            @foreach($trust as $item)
                <span class="trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">{!! $item['icon'] !!}</svg>
                    {{ $item['text'] }}
                </span>
            @endforeach
        @endfor
    </div>
</div>

@if($categories->isNotEmpty())
<section class="home-sec">
    <div class="section-head reveal">
        <div>
            <span class="eyebrow">Shop by category</span>
            <h2>What are you looking for?</h2>
        </div>
        <a class="btn btn-ghost" href="{{ route('marketplace.index') }}">All categories</a>
    </div>
    <div class="cat-grid">
        @foreach($categories as $category)
            <a class="cat-chip reveal" style="--d:{{ min($loop->index, 7) * 55 }}ms" href="{{ route('marketplace.category', $category) }}">
                <span class="arw"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span>
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">{!! $catIcons[$category->icon] ?? $catIcons['__default'] !!}</svg></span>
                <b>{{ $category->name }}</b>
                <span class="cnt">{{ $category->products_count }} listing{{ $category->products_count === 1 ? '' : 's' }}</span>
            </a>
        @endforeach
    </div>
</section>
@endif

<section class="home-sec">
    <div class="section-head reveal">
        <div>
            <span class="eyebrow">Fresh picks</span>
            <h2>Recently listed on campus</h2>
        </div>
        <a class="btn btn-ghost" href="{{ route('marketplace.index') }}">View all</a>
    </div>
    @if($featured->isEmpty())
        <div class="card empty reveal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l1-5h16l1 5"/><path d="M4 9v11h16V9"/><path d="M9 13h6"/></svg>
            <h3>No listings yet</h3>
            <p>Be the first to list an item on CampusBazar.</p>
        </div>
    @else
        <div class="grid grid-4 featured-grid">
            @foreach($featured as $product)
                @include('partials.product-card', ['product' => $product, 'revealDelay' => min($loop->index, 7) * 55])
            @endforeach
        </div>
    @endif
</section>

@if($campuses->isNotEmpty())
<section class="home-sec">
    <div class="section-head reveal">
        <div>
            <span class="eyebrow">By campus</span>
            <h2>Popular campuses</h2>
        </div>
        <a class="btn btn-ghost" href="{{ route('campuses.index') }}">Campus directory</a>
    </div>
    <div class="grid grid-3">
        @foreach($campuses as $campus)
            <a class="card card-hover card-pad campus-card reveal" style="--d:{{ min($loop->index, 5) * 60 }}ms" href="{{ route('campuses.show', $campus) }}">
                <span class="bar"></span>
                <div class="row-between">
                    <div class="row" style="gap:.7rem">
                        <span class="mono">{{ strtoupper(substr($campus->name, 0, 1)) }}</span>
                        <b>{{ $campus->name }}</b>
                    </div>
                    <span class="pill pill-green">{{ $campus->products_count }}</span>
                </div>
                <div class="small muted" style="padding-left:3.1rem">{{ $campus->district }}</div>
            </a>
        @endforeach
    </div>
</section>
@endif

<section class="home-sec">
    <div class="section-head reveal"><div><span class="eyebrow">How it works</span><h2>Sell in three easy steps</h2></div></div>
    <div class="steps">
        <div class="step reveal" style="--d:0ms">
            <span class="n">1</span>
            <b>Verify your Student ID</b>
            <p class="small muted">Upload your college or university ID. Our team reviews and approves it quickly.</p>
        </div>
        <div class="step reveal" style="--d:90ms">
            <span class="n">2</span>
            <b>List your item</b>
            <p class="small muted">Add photos, set a fair price in BDT and publish your listing to students on your campus.</p>
        </div>
        <div class="step reveal" style="--d:180ms">
            <span class="n">3</span>
            <b>Sell with COD</b>
            <p class="small muted">Buyers pay cash on delivery. Confirm the order and get paid directly — no online payment needed.</p>
        </div>
    </div>
</section>

<section class="home-sec">
    <div class="section-head reveal"><div><span class="eyebrow">Why CampusBazar</span><h2>Built around student life</h2></div></div>
    <div class="feature-grid">
        @foreach($features as $feature)
            <div class="feature reveal" style="--d:{{ $loop->index * 70 }}ms">
                <span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">{!! $feature['icon'] !!}</svg></span>
                <div>
                    <b>{{ $feature['title'] }}</b>
                    <p>{{ $feature['text'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>

<section class="home-sec">
    <div class="cta-band reveal">
        <div>
            <h2>Have something to sell?</h2>
            <p>Turn your unused books and gadgets into cash. List it on CampusBazar today.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('seller.products.create') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Post a listing
        </a>
    </div>
</section>
@endsection

@push('scripts')
<script>
    (function () {
        var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        /* Scroll reveal */
        var reveals = document.querySelectorAll('.reveal');
        if (reduce || !('IntersectionObserver' in window)) {
            reveals.forEach(function (el) { el.classList.add('in'); });
        } else {
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in');
                        io.unobserve(entry.target);
                    }
                });
            }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
            reveals.forEach(function (el) { io.observe(el); });
        }

        /* Stat counters */
        function animateCount(el) {
            var to = parseFloat(el.getAttribute('data-count-to')) || 0;
            if (!to) { el.textContent = '0'; return; }
            var start = null, duration = 1400;
            function step(ts) {
                if (start === null) start = ts;
                var p = Math.min((ts - start) / duration, 1);
                var eased = 1 - Math.pow(1 - p, 3);
                el.textContent = Math.round(to * eased).toLocaleString('en-US');
                if (p < 1) requestAnimationFrame(step);
            }
            requestAnimationFrame(step);
        }

        var counters = document.querySelectorAll('[data-count-to]');
        if (!reduce && counters.length) {
            if (!('IntersectionObserver' in window)) {
                counters.forEach(animateCount);
            } else {
                var cio = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            animateCount(entry.target);
                            cio.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.6 });
                counters.forEach(function (el) { cio.observe(el); });
            }
        }
    })();
</script>
@endpush
