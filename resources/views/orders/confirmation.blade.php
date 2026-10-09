@extends('layouts.app')
@section('title', 'Order Confirmed · CampusBazar')
@section('content')
<div style="max-width:640px;margin:1rem auto">
    <div class="card card-pad center">
        <div class="avatar" style="width:64px;height:64px;margin:0 auto 1rem;background:linear-gradient(135deg,var(--primary),var(--emerald))">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>
        </div>
        <span class="eyebrow">Order confirmed</span>
        <h2>Thank you for your order!</h2>
        <p class="muted mt-1">Your order <b>#{{ $order->order_number }}</b> has been placed successfully. The seller will confirm and arrange delivery.</p>

        <div class="card card-pad mt-3" style="text-align:left;background:#f8fbf9">
            <dl class="spec">
                <dt>Item</dt><dd>{{ $order->items->first()->product_title ?? '—' }}</dd>
                <dt>Seller</dt><dd>{{ $order->seller->name ?? '—' }}</dd>
                <dt>Payment method</dt><dd>Cash on Delivery</dd>
                <dt>Amount to pay</dt><dd><b>{{ bdt($order->total) }}</b></dd>
            </dl>
        </div>

        <div class="alert alert-warning mt-3" style="text-align:left">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a4 4 0 0 0-8 0v2"/></svg>
            <div>Please keep <b>{{ bdt($order->total) }}</b> ready in cash. You will pay the rider on delivery.</div>
        </div>

        <div class="row mt-3" style="gap:.6rem;justify-content:center;flex-wrap:wrap">
            <a class="btn btn-primary" href="{{ route('orders.show', $order) }}">Track order</a>
            <a class="btn btn-outline" href="{{ route('marketplace.index') }}">Continue shopping</a>
        </div>
    </div>
</div>
@endsection
