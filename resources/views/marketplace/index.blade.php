@extends('layouts.app')
@section('title', $category ? $category->name . ' · CampusBazar' : 'Marketplace · CampusBazar')

@push('styles')
<style>
    /* The page header band (.page-hero) is shared — see layouts/app.blade.php */

    /* ============================================================
       Filter panel
       ============================================================ */
    .mk-filters{margin-top:1.2rem;padding:1.15rem 1.25rem}
    .filter-head{display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:.95rem}
    .filter-head .ft{display:inline-flex;align-items:center;gap:.45rem;font-weight:700;font-size:.9rem}
    .filter-head .ft svg{width:16px;height:16px;color:var(--primary)}
    .filter-grid{display:grid;gap:.9rem}
    .fg-primary{display:grid;grid-template-columns:2fr 1fr;gap:.9rem;align-items:end}
    .fg-advanced{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:.9rem;align-items:end}
    .filter-grid .field{margin-bottom:0}
    .filter-actions{display:flex;gap:.55rem;flex-wrap:wrap}
    .filter-toggle{
        display:none;align-items:center;gap:.45rem;padding:.45rem .8rem;border-radius:10px;
        border:1px solid var(--border);background:#fff;font-size:.82rem;font-weight:600;color:var(--text);transition:.18s;
    }
    .filter-toggle:hover{border-color:var(--emerald);color:var(--primary)}
    .filter-toggle svg{width:15px;height:15px}
    .filter-toggle .cnt{min-width:18px;padding:.05rem .35rem;border-radius:999px;background:var(--primary);color:#fff;
        font-size:.68rem;font-weight:700;text-align:center}

    /* ============================================================
       Active filter chips
       ============================================================ */
    .mk-active{display:flex;align-items:center;gap:.45rem;flex-wrap:wrap;margin-top:1rem}
    .mk-active .lbl{font-size:.76rem;font-weight:600;color:var(--muted)}
    .mk-active .chip{display:inline-flex;align-items:center;gap:.4rem;padding:.3rem .7rem;border-radius:999px;
        background:var(--emerald-soft);color:var(--primary);font-size:.78rem;font-weight:600;transition:.18s}
    .mk-active .chip:hover{background:#d3efe2}
    .mk-active .chip svg{width:13px;height:13px;opacity:.65}
    .mk-active .chip:hover svg{opacity:1}
    .mk-active .clear{font-size:.78rem;font-weight:600;color:var(--danger)}
    .mk-active .clear:hover{text-decoration:underline}

    /* ============================================================
       Results
       ============================================================ */
    .mk-results{display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin:1.25rem 0 1rem}
    .mk-results .rc{font-size:.86rem;color:var(--muted)}
    .mk-results .rc b{color:var(--text);font-weight:700}

    @media (max-width:960px){
        .fg-advanced{grid-template-columns:repeat(2,minmax(0,1fr))}
    }

    /* ---------- Mobile: minimal ---------- */
    @media (max-width:760px){
        .mk-filters{margin-top:.9rem;padding:1rem}
        .filter-head{margin-bottom:.8rem}
        .filter-grid{gap:.75rem}
        .fg-primary{grid-template-columns:1fr;gap:.75rem}
        .fg-advanced{grid-template-columns:1fr;gap:.75rem}

        /* Search and sort stay visible; the rest folds behind the Filters button.
           Without JS the button is hidden and every field stays on screen. */
        .has-js .filter-toggle{display:inline-flex}
        .has-js .filter-card:not(.advanced-open) .fg-advanced{display:none}
        .filter-actions .btn{flex:1 1 auto;justify-content:center}

        .mk-active{margin-top:.85rem;gap:.4rem}
        .mk-active .chip{font-size:.74rem;padding:.28rem .62rem}
        .mk-results{margin:1rem 0 .8rem}
        .mk-results .rc{font-size:.8rem}
    }
</style>
@endpush

@section('content')
@php
    $filterKeys = ['q', 'category', 'campus', 'condition', 'min_price', 'max_price'];
    $advancedKeys = ['category', 'campus', 'condition', 'min_price', 'max_price'];

    $activeQuery = collect(request()->query())
        ->only($filterKeys)
        ->reject(fn ($value) => $value === null || $value === '');

    $advancedCount = $activeQuery->only($advancedKeys)->count();

    $chipNames = [
        'q' => 'Search',
        'category' => 'Category',
        'campus' => 'Campus',
        'condition' => 'Condition',
        'min_price' => 'Min price',
        'max_price' => 'Max price',
    ];

    $formAction = $category ? route('marketplace.category', $category) : route('marketplace.index');
@endphp

<section class="page-hero">
    <div class="page-hero-inner">
        <div>
            <nav class="page-crumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span class="sep">/</span>
                @if($category)
                    <a href="{{ route('marketplace.index') }}">Marketplace</a>
                    <span class="sep">/</span>
                    <span>{{ $category->name }}</span>
                @else
                    <span>Marketplace</span>
                @endif
            </nav>
            <h1>{{ $category->name ?? 'All listings' }}</h1>
            <p class="page-hero-sub">{{ $category->description ?? 'Textbooks, gadgets and hostel gear from verified students — Cash on Delivery only.' }}</p>
        </div>
        <div class="page-stats">
            <div class="s"><b>{{ $products->total() }}</b><span>listing{{ $products->total() === 1 ? '' : 's' }}</span></div>
            <div class="s"><b>{{ $campuses->count() }}</b><span>campuses</span></div>
        </div>
    </div>
</section>

<form class="card mk-filters filter-card" id="mkFilterForm" method="GET" action="{{ $formAction }}" role="search">
    <div class="filter-head">
        <span class="ft">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 6h18M6 12h12M10 18h4"/></svg>
            Filters
        </span>
        <button class="filter-toggle" type="button" id="mkFilterToggle" aria-expanded="false" aria-controls="mkAdvanced">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 6h18M6 12h12M10 18h4"/></svg>
            <span class="lbl">{{ $advancedCount ? 'Edit filters' : 'More filters' }}</span>
            @if($advancedCount)<span class="cnt">{{ $advancedCount }}</span>@endif
        </button>
    </div>

    <div class="filter-grid">
        <div class="fg-primary">
            <div class="field">
                <label for="fq">Search</label>
                <input class="input" id="fq" type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Textbooks, calculators, cycles…">
            </div>
            <div class="field">
                <label for="fsort">Sort by</label>
                <select class="input" id="fsort" name="sort">
                    <option value="newest" @selected(($filters['sort'] ?? '') === 'newest')>Newest first</option>
                    <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Oldest first</option>
                    <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>Price: Low to High</option>
                    <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>Price: High to Low</option>
                </select>
            </div>
        </div>

        <div class="fg-advanced" id="mkAdvanced">
            @unless($category)
            <div class="field">
                <label for="fcat">Category</label>
                <select class="input" id="fcat" name="category">
                    <option value="">All categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" @selected(($filters['category'] ?? '') === $cat->slug)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            @endunless
            <div class="field">
                <label for="fcampus">Campus</label>
                <select class="input" id="fcampus" name="campus">
                    <option value="">All campuses</option>
                    @foreach($campuses as $campus)
                        <option value="{{ $campus->id }}" @selected((string)($filters['campus'] ?? '') === (string)$campus->id)>{{ $campus->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="fcond">Condition</label>
                <select class="input" id="fcond" name="condition">
                    <option value="">Any condition</option>
                    @foreach(\App\Enums\ProductCondition::all() as $condition)
                        <option value="{{ $condition }}" @selected(($filters['condition'] ?? '') === $condition)>{{ condition_label($condition) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="fmin">Price range (৳)</label>
                <div class="row" style="gap:.5rem">
                    <input class="input" id="fmin" type="number" min="0" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="Min">
                    <input class="input" type="number" min="0" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="Max" aria-label="Maximum price">
                </div>
            </div>
        </div>

        <div class="filter-actions">
            <button class="btn btn-primary" type="submit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                Apply filters
            </button>
            <a class="btn btn-outline" href="{{ $formAction }}">Reset</a>
        </div>
    </div>
</form>

@if($activeQuery->isNotEmpty())
<div class="mk-active">
    <span class="lbl">Filtering:</span>
    @foreach($activeQuery as $key => $value)
        @php
            $remaining = request()->query();
            unset($remaining[$key], $remaining['page']);
            $removeUrl = request()->url() . ($remaining ? '?' . http_build_query($remaining) : '');
        @endphp
        <a class="chip" href="{{ $removeUrl }}" title="Remove {{ $chipNames[$key] ?? $key }} filter">
            {{ $chipNames[$key] ?? $key }}:
            @if($key === 'category')
                {{ $categories->firstWhere('slug', $value)?->name ?? $value }}
            @elseif($key === 'campus')
                {{ $campuses->firstWhere('id', (int) $value)?->name ?? $value }}
            @elseif($key === 'condition')
                {{ condition_label($value) }}
            @elseif($key === 'min_price' || $key === 'max_price')
                ৳{{ $value }}
            @else
                {{ \Illuminate\Support\Str::limit($value, 28) }}
            @endif
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </a>
    @endforeach
    <a class="clear" href="{{ $formAction }}">Clear all</a>
</div>
@endif

@if($products->isEmpty())
    <div class="card empty reveal">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <h3>No listings matched</h3>
        <p>Try a different keyword, or widen your campus and price filters.</p>
        <a class="btn btn-primary mt-2" href="{{ route('marketplace.index') }}">Clear filters</a>
    </div>
@else
    <div class="mk-results">
        <p class="rc">Showing <b>{{ $products->firstItem() }}–{{ $products->lastItem() }}</b> of <b>{{ $products->total() }}</b> listing{{ $products->total() === 1 ? '' : 's' }}</p>
    </div>

    <div class="grid grid-4 product-grid">
        @foreach($products as $product)
            @include('partials.product-card', ['product' => $product, 'revealDelay' => min($loop->index, 7) * 45])
        @endforeach
    </div>

    {{ $products->links('pagination.campusbazar') }}
@endif
@endsection

@push('scripts')
<script>
    (function () {
        var toggle = document.getElementById('mkFilterToggle');
        var form = document.getElementById('mkFilterForm');
        var advanced = document.getElementById('mkAdvanced');
        if (!toggle || !form || !advanced) return;

        toggle.addEventListener('click', function () {
            var open = form.classList.toggle('advanced-open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            var label = toggle.querySelector('.lbl');
            if (label) {
                label.textContent = open ? 'Hide filters' : (toggle.querySelector('.cnt') ? 'Edit filters' : 'More filters');
            }
            if (open) {
                var first = advanced.querySelector('select, input');
                if (first) first.focus({ preventScroll: true });
            }
        });
    })();
</script>
@endpush
