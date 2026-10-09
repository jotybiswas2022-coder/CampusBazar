@extends('layouts.app')
@section('title', 'My Orders · CampusBazar')
@push('styles')
<style>.order-row{display:grid;grid-template-columns:1fr auto;gap:1rem;align-items:center;padding:1rem 1.2rem;border-bottom:1px solid var(--border)}
.order-row:last-child{border-bottom:none}
.order-thumbs{display:flex;gap:.4rem}
.order-thumb{width:48px;height:48px;border-radius:9px;object-fit:cover;background:#eef6f1;border:1px solid var(--border)}
@media (max-width:600px){.order-row{grid-template-columns:1fr}}</style>
@endpush
@section('content')
<div class="section-head"><div><span class="eyebrow">Purchases</span><h2>My orders</h2></div></div>

@if($orders->isEmpty())
    <div class="card empty">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        <h3>No orders yet</h3>
        <p>Your purchases will appear here.</p>
        <a class="btn btn-primary mt-2" href="{{ route('marketplace.index') }}">Start shopping</a>
    </div>
@else
    <div class="card">
        @foreach($orders as $order)
            <a class="order-row" href="{{ route('orders.show', $order) }}">
                <div>
                    <div class="row" style="gap:.6rem;flex-wrap:wrap">
                        <b>#{{ $order->order_number }}</b>
                        @php $pill = match($order->status) {
                            \App\Enums\OrderStatus::DELIVERED => 'pill-green',
                            \App\Enums\OrderStatus::CANCELLED, \App\Enums\OrderStatus::RETURNED => 'pill-red',
                            \App\Enums\OrderStatus::PENDING_CONFIRMATION => 'pill-amber',
                            default => 'pill-blue',
                        }; @endphp
                        <span class="pill {{ $pill }}">{{ order_status_label($order->status) }}</span>
                    </div>
                    <div class="small muted mt-1">
                        {{ $order->items->count() }} item(s) · Seller: {{ $order->seller->name ?? '—' }} · {{ $order->created_at->format('d M Y') }}
                    </div>
                </div>
                <div class="row" style="gap:1rem">
                    <b class="product-price" style="font-size:1.05rem">{{ bdt($order->total) }}</b>
                    <span class="btn btn-outline btn-sm">View</span>
                </div>
            </a>
        @endforeach
    </div>
    {{ $orders->links('pagination.campusbazar') }}
@endif
@endsection
