@extends('layouts.app')
@section('title', 'Order #' . $order->order_number . ' · CampusBazar')
@push('styles')
<style>.timeline{list-style:none;position:relative;padding-left:1.5rem}
.timeline:before{content:"";position:absolute;left:5px;top:6px;bottom:6px;width:2px;background:var(--border)}
.timeline li{position:relative;padding-bottom:1rem}
.timeline li:before{content:"";position:absolute;left:-1.5rem;top:5px;width:12px;height:12px;border-radius:50%;background:var(--emerald);border:2px solid #fff;box-shadow:0 0 0 2px var(--border)}</style>
@endpush
@section('content')
<nav class="small muted mb-2"><a href="{{ route('orders.index') }}">My orders</a> · #{{ $order->order_number }}</nav>
<div class="section-head">
    <div>
        <span class="eyebrow">Order</span>
        <h2>#{{ $order->order_number }}</h2>
        <div class="row mt-1" style="gap:.5rem">
            <span class="pill pill-blue">{{ order_status_label($order->status) }}</span>
            <span class="pill {{ $order->isPaid() ? 'pill-green' : 'pill-amber' }}">{{ $order->isPaid() ? 'Paid' : 'Cash on Delivery' }}</span>
        </div>
    </div>
    <span class="muted small">Placed {{ $order->created_at->format('d M Y, g:i A') }}</span>
</div>

<div class="grid grid-2" style="align-items:start;grid-template-columns:1.4fr 1fr">
    <div class="stack">
        <div class="card card-pad">
            <h3>Items</h3>
            <div class="table-wrap mt-2">
                <table>
                    <thead><tr><th>Product</th><th>Unit price</th><th>Qty</th><th>Total</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_title }}</td>
                                <td>{{ bdt($item->unit_price) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ bdt($item->line_total) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <dl class="spec mt-3">
                <dt>Subtotal</dt><dd>{{ bdt($order->subtotal) }}</dd>
                <dt>Delivery charge</dt><dd>{{ bdt($order->delivery_charge) }}</dd>
                <dt><b>Total</b></dt><dd><b>{{ bdt($order->total) }}</b></dd>
            </dl>
        </div>

        <div class="card card-pad">
            <h3>Delivery</h3>
            <dl class="spec mt-2">
                <dt>Recipient</dt><dd>{{ $order->recipient_name }}</dd>
                <dt>Phone</dt><dd>{{ $order->phone }}</dd>
                <dt>Address</dt><dd>{{ $order->address }}</dd>
                <dt>District</dt><dd>{{ $order->district }}</dd>
                @if($order->upazila)<dt>Upazila</dt><dd>{{ $order->upazila }}</dd>@endif
            </dl>
            @if($order->delivery_instructions)
                <p class="small muted mt-2">Note: {{ $order->delivery_instructions }}</p>
            @endif
        </div>
    </div>

    <aside class="stack">
        <div class="card card-pad">
            <h3>Order status</h3>
            <ul class="timeline mt-2">
                @forelse($order->statusHistories->sortBy('created_at') as $history)
                    <li>
                        <b>{{ order_status_label($history->to_status) }}</b>
                        <div class="small muted">{{ $history->created_at->format('d M Y, g:i A') }}@if($history->actor) · {{ $history->actor->name }}@endif</div>
                        @if($history->reason)<div class="small">{{ $history->reason }}</div>@endif
                    </li>
                @empty
                    <li><b>{{ order_status_label($order->status) }}</b></li>
                @endforelse
            </ul>
        </div>

        @if($order->seller)
        <div class="card card-pad">
            <h3>Seller</h3>
            <div class="seller-box mt-2">
                <span class="avatar">{{ $order->seller->initials() }}</span>
                <div class="flex-1"><b>{{ $order->seller->name }}</b></div>
            </div>
            <form action="{{ route('messages.start', $order->items->first()?->product ?? 0) }}" method="POST" class="mt-2">
                @csrf
                <button class="btn btn-outline w-full" type="submit">Message seller</button>
            </form>
        </div>
        @endif

        @if($order->canBeCancelled() && $order->buyer_id === auth()->id())
            <form class="card card-pad" method="POST" action="{{ route('orders.cancel', $order) }}">
                @csrf
                <h4>Cancel this order</h4>
                <div class="field mt-2">
                    <label for="reason">Reason (optional)</label>
                    <input class="input" id="reason" name="reason" placeholder="Why are you cancelling?">
                </div>
                <button class="btn btn-danger w-full" type="submit" onclick="return confirm('Cancel this order?')">Cancel order</button>
            </form>
        @endif

        @if($canReview)
            <form class="card card-pad" method="POST" action="{{ route('orders.review', $order) }}">
                @csrf
                <h4>Leave a review</h4>
                <div class="field mt-2">
                    <label for="rating">Rating</label>
                    <select class="input" id="rating" name="rating" required>
                        <option value="5">5 — Excellent</option>
                        <option value="4">4 — Good</option>
                        <option value="3">3 — Average</option>
                        <option value="2">2 — Poor</option>
                        <option value="1">1 — Bad</option>
                    </select>
                </div>
                <div class="field">
                    <label for="comment">Comment (optional)</label>
                    <textarea class="input" id="comment" name="comment" style="min-height:80px"></textarea>
                </div>
                <button class="btn btn-primary w-full" type="submit">Submit review</button>
            </form>
        @endif
    </aside>
</div>
@endsection
