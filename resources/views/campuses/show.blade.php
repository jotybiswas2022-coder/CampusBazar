@extends('layouts.app')
@section('title', $campus->name . ' · CampusBazar')
@section('content')
<div class="card card-pad mb-3">
    <span class="eyebrow">{{ ucfirst($campus->type) }}</span>
    <h2>{{ $campus->name }}</h2>
    <p class="muted small mt-1">{{ $campus->district }}@if($campus->location) · {{ $campus->location }}@endif</p>
</div>

<div class="section-head"><h2>Listings from this campus</h2><span class="muted small">{{ $products->total() }} items</span></div>

@if($products->isEmpty())
    <div class="card empty">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l1-5h16l1 5"/><path d="M4 9v11h16V9"/></svg>
        <h3>No listings yet</h3>
        <p>No active listings from {{ $campus->name }} right now.</p>
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
