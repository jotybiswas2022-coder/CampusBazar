@extends('layouts.app')
@section('title', 'Seller Dashboard · CampusBazar')
@section('content')
<div class="section-head">
    <div>
        <span class="eyebrow">Selling</span>
        <h2>Seller dashboard</h2>
        @if(! $user->isVerified())<div class="mt-1">@include('partials.verification-badge', ['user' => $user])</div>@endif
    </div>
    <a class="btn btn-primary" href="{{ route('seller.products.create') }}">+ New listing</a>
</div>

@if(! $user->isVerified())
    <div class="alert alert-warning mb-3">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/></svg>
        <div>You must verify your Student ID before your listings go live. <a href="{{ route('verification.status') }}" style="color:#92400e;font-weight:700;text-decoration:underline">Verify now</a></div>
    </div>
@endif

<div class="grid grid-4 mb-3">
    <div class="stat"><span class="label">Active listings</span><span class="value">{{ $stats['active_listings'] }}</span></div>
    <div class="stat"><span class="label">Pending approval</span><span class="value amber">{{ $stats['pending_listings'] }}</span></div>
    <div class="stat"><span class="label">Orders to fulfil</span><span class="value blue">{{ $stats['orders_to_fulfil'] }}</span></div>
    <div class="stat"><span class="label">COD collected</span><span class="value">{{ bdt($stats['cod_collected']) }}</span></div>
</div>

<div class="grid grid-2" style="align-items:start">
    <div class="card card-pad">
        <div class="row-between mb-2"><h3>Recent orders</h3><a class="small" style="color:var(--primary)" href="{{ route('seller.orders.index') }}">All orders</a></div>
        @forelse($recentOrders as $order)
            <div class="row-between" style="padding:.6rem 0;border-bottom:1px solid var(--border)">
                <div>
                    <a href="{{ route('seller.orders.show', $order) }}" style="font-weight:600">#{{ $order->order_number }}</a>
                    <div class="small muted">Buyer: {{ $order->buyer->name ?? '—' }} · {{ bdt($order->total) }}</div>
                </div>
                <span class="pill pill-gray">{{ order_status_label($order->status) }}</span>
            </div>
        @empty
            <p class="small muted">No orders yet.</p>
        @endforelse
    </div>

    <div class="card card-pad">
        <div class="row-between mb-2"><h3>Recent listings</h3><a class="small" style="color:var(--primary)" href="{{ route('seller.products.index') }}">Manage</a></div>
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
@endsection
