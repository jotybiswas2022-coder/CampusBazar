@extends('layouts.app')
@section('title', 'Dashboard · CampusBazar')
@section('content')
<div class="section-head">
    <div>
        <span class="eyebrow">My account</span>
        <h2>Welcome back, {{ explode(' ', $user->name)[0] }}</h2>
        <div class="mt-1">@include('partials.verification-badge', ['user' => $user])</div>
    </div>
    <a class="btn btn-primary" href="{{ route('seller.products.create') }}">Post a listing</a>
</div>

@if($verificationStatus !== \App\Enums\VerificationStatus::APPROVED)
    <div class="alert alert-warning mb-3">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/></svg>
        <div>Verify your Student ID to buy and sell. <a href="{{ route('verification.status') }}" style="color:#92400e;font-weight:700;text-decoration:underline">Verify now</a></div>
    </div>
@endif

<div class="grid grid-4 mb-3">
    <div class="stat"><span class="label">Active listings</span><span class="value">{{ $activeListings }}</span></div>
    <div class="stat"><span class="label">Total listings</span><span class="value blue">{{ $listingsCount }}</span></div>
    <div class="stat"><span class="label">Orders bought</span><span class="value">{{ $buyOrdersCount }}</span></div>
    <div class="stat"><span class="label">Sales</span><span class="value amber">{{ $sellOrdersCount }}</span></div>
</div>

<div class="grid grid-2" style="align-items:start">
    <div class="card card-pad">
        <div class="row-between mb-2"><h3>Recent orders</h3><a class="small" style="color:var(--primary)" href="{{ route('orders.index') }}">View all</a></div>
        @forelse($recentOrders as $order)
            <div class="row-between" style="padding:.6rem 0;border-bottom:1px solid var(--border)">
                <div>
                    <a href="{{ route('orders.show', $order) }}" style="font-weight:600">#{{ $order->order_number }}</a>
                    <div class="small muted">{{ $order->items->count() }} item(s) · {{ bdt($order->total) }}</div>
                </div>
                <span class="pill pill-gray">{{ order_status_label($order->status) }}</span>
            </div>
        @empty
            <p class="small muted">No orders yet.</p>
        @endforelse
    </div>

    <div class="card card-pad">
        <div class="row-between mb-2"><h3>My listings</h3><a class="small" style="color:var(--primary)" href="{{ route('seller.products.index') }}">Manage</a></div>
        @forelse($recentListings as $product)
            <div class="row-between" style="padding:.6rem 0;border-bottom:1px solid var(--border)">
                <div>
                    <a href="{{ route('products.show', $product) }}" style="font-weight:600">{{ \Illuminate\Support\Str::limit($product->title, 32) }}</a>
                    <div class="small muted">{{ bdt($product->price) }}</div>
                </div>
                <span class="pill pill-gray">{{ product_status_label($product->status) }}</span>
            </div>
        @empty
            <p class="small muted">You have no listings yet.</p>
        @endforelse
    </div>
</div>

<div class="grid grid-3 mt-3">
    <a class="card card-hover card-pad" href="{{ route('orders.index') }}"><b>My Orders</b><p class="small muted mt-1">Track purchases and deliveries.</p></a>
    <a class="card card-hover card-pad" href="{{ route('wishlist.index') }}"><b>Wishlist</b><p class="small muted mt-1">{{ $wishlistCount }} saved item(s).</p></a>
    <a class="card card-hover card-pad" href="{{ route('messages.index') }}"><b>Messages</b><p class="small muted mt-1">Chat with buyers and sellers.</p></a>
</div>
@endsection
