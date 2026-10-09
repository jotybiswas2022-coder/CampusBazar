@extends('layouts.app')
@section('title', 'Manage Order #' . $order->order_number . ' · CampusBazar')
@section('content')
<nav class="small muted mb-2"><a href="{{ route('seller.orders.index') }}">Sales</a> · #{{ $order->order_number }}</nav>
<div class="section-head">
    <div>
        <span class="eyebrow">Order</span>
        <h2>#{{ $order->order_number }}</h2>
        <div class="row mt-1" style="gap:.5rem">
            <span class="pill pill-blue">{{ order_status_label($order->status) }}</span>
            <span class="pill {{ $order->isPaid() ? 'pill-green' : 'pill-amber' }}">{{ $order->isPaid() ? 'Paid' : 'COD Unpaid' }}</span>
        </div>
    </div>
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
                            <tr><td>{{ $item->product_title }}</td><td>{{ bdt($item->unit_price) }}</td><td>{{ $item->quantity }}</td><td>{{ bdt($item->line_total) }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <dl class="spec mt-3">
                <dt>Subtotal</dt><dd>{{ bdt($order->subtotal) }}</dd>
                <dt>Delivery charge</dt><dd>{{ bdt($order->delivery_charge) }}</dd>
                <dt><b>Total collectable (COD)</b></dt><dd><b>{{ bdt($order->total) }}</b></dd>
            </dl>
        </div>

        <div class="card card-pad">
            <h3>Buyer & delivery</h3>
            <dl class="spec mt-2">
                <dt>Buyer</dt><dd>{{ $order->buyer->name ?? '—' }}</dd>
                <dt>Recipient</dt><dd>{{ $order->recipient_name }}</dd>
                <dt>Phone</dt><dd>{{ $order->phone }}</dd>
                <dt>Address</dt><dd>{{ $order->address }}, {{ $order->district }}@if($order->upazila), {{ $order->upazila }}@endif</dd>
            </dl>
            @if($order->delivery_instructions)<p class="small muted mt-2">Note: {{ $order->delivery_instructions }}</p>@endif
        </div>
    </div>

    <aside class="stack">
        @if(!empty($allowed))
            <form class="card card-pad" method="POST" action="{{ route('seller.orders.status', $order) }}">
                @csrf
                @method('PATCH')
                <h4>Update status</h4>
                <div class="field mt-2">
                    <label for="status">New status</label>
                    <select class="input" id="status" name="status" required>
                        @foreach($allowed as $status)
                            <option value="{{ $status }}">{{ order_status_label($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="reason">Note (optional)</label>
                    <input class="input" id="reason" name="reason">
                </div>
                <button class="btn btn-primary w-full" type="submit">Update order</button>
            </form>
        @else
            <div class="card card-pad"><h4>Status</h4><p class="small muted mt-1">This order is in a final state and cannot be changed.</p></div>
        @endif

        <div class="card card-pad">
            <h3>History</h3>
            <ul class="timeline mt-2">
                @foreach($order->statusHistories->sortBy('created_at') as $history)
                    <li>
                        <b>{{ order_status_label($history->to_status) }}</b>
                        <div class="small muted">{{ $history->created_at->format('d M Y, g:i A') }}@if($history->actor) · {{ $history->actor->name }}@endif</div>
                        @if($history->reason)<div class="small">{{ $history->reason }}</div>@endif
                    </li>
                @endforeach
            </ul>
        </div>
    </aside>
</div>
@endsection
