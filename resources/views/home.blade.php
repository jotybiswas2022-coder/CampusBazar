@extends('layouts.app')
@section('title', 'CampusBazar · Student Marketplace in Bangladesh')
@push('styles')
<style>
    .hero-actions{display:flex;gap:.7rem;margin-top:1.4rem;flex-wrap:wrap}
    .hero-stats{display:flex;gap:1.6rem;margin-top:1.8rem;flex-wrap:wrap;position:relative;z-index:2}
    .hero-stats .hs{display:flex;flex-direction:column}
    .hero-stats .hs b{font-size:1.5rem;color:#fff}
    .hero-stats .hs span{font-size:.78rem;color:#c9ddd3}
    .cat-chip{display:flex;flex-direction:column;gap:.5rem;align-items:flex-start;padding:1.1rem;border-radius:14px;background:#fff;border:1px solid var(--border);transition:.2s}
    .cat-chip:hover{transform:translateY(-3px);border-color:#c7e6d6;box-shadow:var(--shadow)}
    .cat-chip .ic{width:42px;height:42px;border-radius:11px;background:var(--emerald-soft);display:grid;place-items:center;color:var(--primary)}
    .cat-chip .ic svg{width:22px;height:22px}
    .cat-chip b{font-size:.9rem}
    .cat-chip span{font-size:.75rem;color:var(--muted)}
    .steps{display:grid;grid-template-columns:repeat(3,1fr);gap:1.1rem}
    .step{padding:1.4rem;border-radius:16px;background:#fff;border:1px solid var(--border)}
    .step .n{width:34px;height:34px;border-radius:10px;background:var(--primary);color:#fff;display:grid;place-items:center;font-weight:800;margin-bottom:.7rem}
    .cta-band{background:linear-gradient(135deg,#083d27,#0b5d3b 50%,#127a4e);color:#fff;border-radius:18px;padding:2.2rem;display:flex;align-items:center;justify-content:space-between;gap:1.5rem;flex-wrap:wrap}
    @media (max-width:760px){.steps{grid-template-columns:1fr}}
</style>
@endpush
@section('content')
<section class="hero">
    <span class="cod-note">Cash on Delivery · Student-only marketplace</span>
    <h1 class="mt-2">Buy smart. Sell easy.<br>Stay campus connected.</h1>
    <p>Textbooks, calculators, electronics and hostel essentials from verified students at your campus — all with Cash on Delivery.</p>
    <div class="hero-actions">
        <a class="btn btn-primary" href="{{ route('marketplace.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            Browse Marketplace
        </a>
        <a class="btn btn-outline" href="{{ route('seller.products.create') }}" style="background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.3);color:#fff">Start Selling</a>
    </div>
    <div class="hero-stats">
        <div class="hs"><b>{{ number_format($stats['products']) }}+</b><span>Active listings</span></div>
        <div class="hs"><b>{{ number_format($stats['students']) }}+</b><span>Verified students</span></div>
        <div class="hs"><b>{{ number_format($stats['campuses']) }}+</b><span>Campuses</span></div>
    </div>
</section>

@if($categories->isNotEmpty())
<section class="mt-4">
    <div class="section-head">
        <div>
            <span class="eyebrow">Shop by category</span>
            <h2>What are you looking for?</h2>
        </div>
        <a class="btn btn-ghost" href="{{ route('marketplace.index') }}">All categories</a>
    </div>
    <div class="grid grid-4">
        @foreach($categories as $category)
            <a class="cat-chip" href="{{ route('marketplace.category', $category) }}">
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l1-5h16l1 5"/><path d="M4 9v11h16V9"/></svg></span>
                <b>{{ $category->name }}</b>
                <span>{{ $category->products_count }} listing{{ $category->products_count === 1 ? '' : 's' }}</span>
            </a>
        @endforeach
    </div>
</section>
@endif

<section class="mt-4">
    <div class="section-head">
        <div>
            <span class="eyebrow">Fresh picks</span>
            <h2>Recently listed on campus</h2>
        </div>
        <a class="btn btn-ghost" href="{{ route('marketplace.index') }}">View all</a>
    </div>
    @if($featured->isEmpty())
        <div class="card empty">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l1-5h16l1 5"/><path d="M4 9v11h16V9"/><path d="M9 13h6"/></svg>
            <h3>No listings yet</h3>
            <p>Be the first to list an item on CampusBazar.</p>
        </div>
    @else
        <div class="grid grid-4">
            @foreach($featured as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    @endif
</section>

@if($campuses->isNotEmpty())
<section class="mt-4">
    <div class="section-head">
        <div>
            <span class="eyebrow">By campus</span>
            <h2>Popular campuses</h2>
        </div>
        <a class="btn btn-ghost" href="{{ route('campuses.index') }}">Campus directory</a>
    </div>
    <div class="grid grid-3">
        @foreach($campuses as $campus)
            <a class="card card-hover card-pad" href="{{ route('campuses.show', $campus) }}">
                <div class="row-between">
                    <b>{{ $campus->name }}</b>
                    <span class="pill pill-green">{{ $campus->products_count }}</span>
                </div>
                <div class="small muted mt-1">{{ $campus->district }}</div>
            </a>
        @endforeach
    </div>
</section>
@endif

<section class="mt-4">
    <div class="section-head"><div><span class="eyebrow">How it works</span><h2>Sell in three easy steps</h2></div></div>
    <div class="steps">
        <div class="step">
            <span class="n">1</span>
            <b>Verify your Student ID</b>
            <p class="small muted mt-1">Upload your college or university ID. Our team reviews and approves it quickly.</p>
        </div>
        <div class="step">
            <span class="n">2</span>
            <b>List your item</b>
            <p class="small muted mt-1">Add photos, set a fair price in BDT and publish your listing to students on your campus.</p>
        </div>
        <div class="step">
            <span class="n">3</span>
            <b>Sell with COD</b>
            <p class="small muted mt-1">Buyers pay cash on delivery. Confirm the order and get paid directly — no online payment needed.</p>
        </div>
    </div>
</section>

<section class="mt-4">
    <div class="cta-band">
        <div>
            <h2>Have something to sell?</h2>
            <p style="color:#c9ddd3;margin-top:.3rem">Turn your unused books and gadgets into cash. List it on CampusBazar today.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('seller.products.create') }}">Post a listing</a>
    </div>
</section>
@endsection
