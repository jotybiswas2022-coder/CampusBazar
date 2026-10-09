@extends('layouts.app')
@section('title', $category ? $category->name . ' · CampusBazar' : 'Marketplace · CampusBazar')
@push('styles')
<style>
    .filter-card{padding:1.1rem 1.2rem;margin-bottom:1.4rem}
    .filter-card .field{margin-bottom:0}
    .results-bar{display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1rem}
    .active-filters{display:flex;gap:.4rem;flex-wrap:wrap;margin-bottom:1rem}
    .active-filters .chip{display:inline-flex;align-items:center;gap:.35rem;padding:.25rem .65rem;border-radius:999px;background:var(--emerald-soft);color:var(--primary);font-size:.78rem;font-weight:600}
</style>
@endpush
@section('content')
<div class="section-head">
    <div>
        <span class="eyebrow">Marketplace</span>
        <h2>{{ $category->name ?? 'All listings' }}</h2>
    </div>
</div>

<form class="card filter-card" method="GET" action="{{ $category ? route('marketplace.category', $category) : route('marketplace.index') }}">
    <div class="filters">
        <div class="field">
            <label for="fq">Search</label>
            <input class="input" id="fq" type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Keyword…">
        </div>
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
            <label for="fsort">Sort by</label>
            <select class="input" id="fsort" name="sort">
                <option value="newest" @selected(($filters['sort'] ?? '') === 'newest')>Newest</option>
                <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Oldest</option>
                <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>Price: Low to High</option>
                <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>Price: High to Low</option>
            </select>
        </div>
        <div class="field">
            <label>Price range (৳)</label>
            <div class="row" style="gap:.5rem">
                <input class="input" type="number" min="0" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="Min">
                <input class="input" type="number" min="0" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="Max">
            </div>
        </div>
        <div class="row" style="gap:.5rem">
            <button class="btn btn-primary w-full" type="submit">Apply</button>
            <a class="btn btn-outline" href="{{ $category ? route('marketplace.category', $category) : route('marketplace.index') }}">Reset</a>
        </div>
    </div>
</form>

<div class="results-bar">
    <p class="muted small">{{ $products->total() }} result{{ $products->total() === 1 ? '' : 's' }} found</p>
</div>

@if($products->isEmpty())
    <div class="card empty">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <h3>No products matched your search</h3>
        <p>Try adjusting your filters or keywords.</p>
        <a class="btn btn-primary mt-2" href="{{ route('marketplace.index') }}">Clear filters</a>
    </div>
@else
    <div class="grid grid-4">
        @foreach($products as $product)
            @include('partials.product-card', ['product' => $product])
        @endforeach
    </div>
    {{ $products->links('pagination.campusbazar') }}
@endif
@endsection
