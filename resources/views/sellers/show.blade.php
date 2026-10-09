@extends('layouts.app')
@section('title', $seller->name . ' · CampusBazar')
@section('content')
<div class="card card-pad mb-3">
    <div class="row" style="gap:1rem">
        <span class="avatar" style="width:64px;height:64px;font-size:1.3rem">{{ $seller->initials() }}</span>
        <div class="flex-1">
            <div class="row" style="gap:.6rem;flex-wrap:wrap">
                <h2>{{ $seller->name }}</h2>
                @include('partials.verification-badge', ['user' => $seller])
            </div>
            <p class="small muted mt-1">
                {{ $seller->campus->name ?? 'CampusBazar member' }}
                · <span class="stars" style="color:#f59e0b">★ {{ $rating ?: '0.0' }}</span> ({{ $reviewCount }} review{{ $reviewCount === 1 ? '' : 's' }})
                · Member since {{ $seller->created_at->format('M Y') }}
            </p>
            @if($seller->bio)<p class="mt-1">{{ $seller->bio }}</p>@endif
        </div>
    </div>
</div>

<div class="section-head"><h2>Active listings</h2><span class="muted small">{{ $products->total() }} items</span></div>
@if($products->isEmpty())
    <div class="card empty"><h3>No active listings</h3><p>{{ $seller->name }} has no items for sale right now.</p></div>
@else
    <div class="grid grid-4">
        @foreach($products as $product)
            @include('partials.product-card', ['product' => $product])
        @endforeach
    </div>
    {{ $products->links('pagination.campusbazar') }}
@endif
@endsection
