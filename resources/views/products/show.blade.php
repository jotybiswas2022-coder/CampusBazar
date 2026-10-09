@extends('layouts.app')
@section('title', $product->title . ' · CampusBazar')
@push('styles')
<style>
    .detail{display:grid;grid-template-columns:1.3fr 1fr;gap:2rem;align-items:start}
    .buy-panel{position:sticky;top:86px}
    .buy-price{font-size:2rem;font-weight:800;color:var(--primary)}
    .spec{display:grid;grid-template-columns:auto 1fr;gap:.4rem 1rem;font-size:.88rem}
    .spec dt{color:var(--muted)}
    .spec dd{text-align:right;font-weight:600}
    .seller-box{display:flex;gap:.8rem;align-items:center;padding:1rem;border-radius:12px;background:#f8fbf9;border:1px solid var(--border)}
    .review{padding:1rem 0;border-bottom:1px solid var(--border)}
    .review:last-child{border-bottom:none}
    .stars{color:#f59e0b;letter-spacing:.1em}
    .desc{white-space:pre-line;color:#334155}
    @media (max-width:860px){.detail{grid-template-columns:1fr}.buy-panel{position:static}}
</style>
@endpush
@section('content')
<nav class="small muted mb-2" aria-label="Breadcrumb">
    <a href="{{ route('marketplace.index') }}">Marketplace</a>
    @if($product->category) · <a href="{{ route('marketplace.category', $product->category) }}">{{ $product->category->name }}</a>@endif
    · <span>{{ $product->title }}</span>
</nav>

<div class="detail">
    <div>
        <div class="gallery">
            <div class="main">
                @if($product->images->isNotEmpty())
                    <img id="mainImage" src="{{ $product->images->first()->url }}" alt="{{ $product->title }}">
                @else
                    <svg width="70" height="70" viewBox="0 0 24 24" fill="none" stroke="#9bb5a7" stroke-width="1.4"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9.5" r="1.5"/><path d="m21 16-5-5L5 21"/></svg>
                @endif
            </div>
            @foreach($product->images->take(8) as $image)
                <button type="button" class="thumb" data-src="{{ $image->url }}" style="border:none;padding:0">
                    <img src="{{ $image->url }}" alt="{{ $product->title }}">
                </button>
            @endforeach
        </div>

        <div class="card card-pad mt-3">
            <h3>Description</h3>
            <p class="desc mt-1">{{ $product->description }}</p>
        </div>

        <div class="card card-pad mt-3">
            <h3>Reviews @if($product->averageRating())<span class="stars"> · ★ {{ $product->averageRating() }}</span>@endif</h3>
            @forelse($product->reviews->where('status', 'published') as $review)
                <div class="review">
                    <div class="row-between">
                        <b>{{ $review->reviewer->name ?? 'Student' }}</b>
                        <span class="stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                    </div>
                    @if($review->comment)<p class="small mt-1">{{ $review->comment }}</p>@endif
                    <span class="small muted">{{ $review->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <p class="small muted mt-1">No reviews yet for this listing.</p>
            @endforelse
        </div>
    </div>

    <aside>
        <div class="card card-pad buy-panel">
            <div class="row-between">
                <span class="pill pill-green">{{ condition_label($product->condition) }}</span>
                <span class="small muted">{{ $product->created_at->diffForHumans() }}</span>
            </div>
            <h1 class="mt-2" style="font-size:1.4rem">{{ $product->title }}</h1>
            <div class="buy-price mt-2">{{ bdt($product->price) }}</div>
            <p class="small muted">Cash on Delivery only · Delivery charge {{ bdt(config('campusbazar.delivery_charge')) }}</p>

            <dl class="spec mt-3">
                <dt>Category</dt><dd>{{ $product->category->name ?? '—' }}</dd>
                <dt>Condition</dt><dd>{{ condition_label($product->condition) }}</dd>
                <dt>Campus</dt><dd>{{ $product->campus->name ?? '—' }}</dd>
                <dt>Views</dt><dd>{{ number_format($product->views_count) }}</dd>
            </dl>

            @auth
                @if(auth()->id() === $product->user_id)
                    <div class="alert alert-info mt-3" style="margin-bottom:0">This is your listing.</div>
                    <a class="btn btn-outline w-full mt-2" href="{{ route('seller.products.edit', $product) }}">Edit listing</a>
                @elseif($product->isPurchasable())
                    @if(auth()->user()->isVerified())
                        <a class="btn btn-primary w-full mt-3" href="{{ route('checkout.create', $product) }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            Buy with COD
                        </a>
                    @else
                        <div class="alert alert-warning mt-3" style="margin-bottom:0">
                            Verify your Student ID to buy this item.
                        </div>
                        <a class="btn btn-primary w-full mt-2" href="{{ route('verification.status') }}">Get verified</a>
                    @endif
                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="mt-2">
                        @csrf
                        <button class="btn btn-outline w-full" type="submit">{{ $inWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}</button>
                    </form>
                    <form action="{{ route('messages.start', $product) }}" method="POST" class="mt-2">
                        @csrf
                        <button class="btn btn-ghost w-full" type="submit">Message seller</button>
                    </form>
                @else
                    <div class="alert alert-warning mt-3" style="margin-bottom:0">This item is {{ strtolower(product_status_label($product->status)) }} and not available.</div>
                @endif
            @else
                <a class="btn btn-primary w-full mt-3" href="{{ route('login') }}">Log in to buy</a>
                <a class="btn btn-ghost w-full mt-2" href="{{ route('login') }}">Message seller</a>
            @endauth
        </div>

        <div class="card card-pad mt-3">
            <h4>Seller</h4>
            <div class="seller-box mt-2">
                <span class="avatar">{{ $product->seller->initials() ?? '?' }}</span>
                <div class="flex-1">
                    <b>{{ $product->seller->name ?? 'Student' }}</b>
                    <div class="small muted">{{ $product->seller->campus->name ?? 'CampusBazar member' }}</div>
                </div>
                @if($product->seller && $product->seller->isVerified())
                    <span class="pill pill-green">Verified</span>
                @endif
            </div>
            @if($product->seller)
                <a class="btn btn-outline w-full mt-2" href="{{ route('sellers.show', $product->seller) }}">View profile</a>
            @endif
        </div>
    </aside>
</div>

@if($related->isNotEmpty())
<section class="mt-4">
    <div class="section-head"><h2>More in {{ $product->category->name ?? 'this category' }}</h2></div>
    <div class="grid grid-4">
        @foreach($related as $item)
            @include('partials.product-card', ['product' => $item])
        @endforeach
    </div>
</section>
@endif
@endsection
@push('scripts')
<script>
    document.querySelectorAll('.gallery .thumb').forEach(function (thumb) {
        thumb.addEventListener('click', function () {
            var main = document.getElementById('mainImage');
            if (main) main.src = thumb.getAttribute('data-src');
        });
    });
    document.querySelectorAll('form[data-confirm]').forEach(function (f) {
        f.addEventListener('submit', function (e) { if (!confirm(f.getAttribute('data-confirm'))) e.preventDefault(); });
    });
</script>
@endpush
