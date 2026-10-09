@extends('layouts.admin')
@section('title', 'Order #' . $order->order_number)
@section('heading', 'Order #' . $order->order_number)
@section('content')
<div class="badge-stack mb-3">
    <span class="pill pill-blue">{{ order_status_label($order->status) }}</span>
    <span class="pill {{ $order->isPaid() ? 'pill-green' : 'pill-amber' }}">{{ $order->isPaid() ? 'Paid' : 'Unpaid' }}</span>
    <span class="pill pill-gray">COD</span>
</div>

<div class="grid grid-2" style="align-items:start">
    <div class="stack">
        <div class="card card-pad">
            <h3>Items</h3>
            <div class="table-wrap mt-2">
                <table>
                    <thead><tr><th>Product</th><th>Unit</th><th>Qty</th><th>Total</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr><td>{{ $item->product_title }}</td><td>{{ bdt($item->unit_price) }}</td><td>{{ $item->quantity }}</td><td>{{ bdt($item->line_total) }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <dl class="spec mt-3" style="display:grid;grid-template-columns:auto 1fr;gap:.4rem 1rem;font-size:.86rem">
                <dt class="muted">Subtotal</dt><dd style="text-align:right">{{ bdt($order->subtotal) }}</dd>
                <dt class="muted">Delivery charge</dt><dd style="text-align:right">{{ bdt($order->delivery_charge) }}</dd>
                <dt class="muted"><b>Total</b></dt><dd style="text-align:right;font-weight:700">{{ bdt($order->total) }}</dd>
            </dl>
        </div>

        <div class="card card-pad">
            <h3>Parties & delivery</h3>
            <dl class="spec mt-2" style="display:grid;grid-template-columns:auto 1fr;gap:.4rem 1rem;font-size:.86rem">
                <dt class="muted">Buyer</dt><dd style="text-align:right">{{ $order->buyer->name ?? '—' }}</dd>
                <dt class="muted">Seller</dt><dd style="text-align:right">{{ $order->seller->name ?? '—' }}</dd>
                <dt class="muted">Recipient</dt><dd style="text-align:right">{{ $order->recipient_name }}</dd>
                <dt class="muted">Phone</dt><dd style="text-align:right">{{ $order->phone }}</dd>
                <dt class="muted">Address</dt><dd style="text-align:right">{{ $order->address }}, {{ $order->district }}@if($order->upazila), {{ $order->upazila }}@endif</dd>
            </dl>
            @if($order->payment_confirmer)
                <p class="small muted mt-2">COD confirmed by {{ $order->payment_confirmer->name }} @ {{ $order->payment_collected_at?->format('d M Y') }}</p>
            @endif
            @if($order->cancellation_reason)
                <p class="small muted mt-2">Cancellation reason: {{ $order->cancellation_reason }}</p>
            @endif
        </div>

        <div class="card card-pad">
            <h3>Status history</h3>
            <div class="table-wrap mt-2">
                <table>
                    <thead><tr><th>From</th><th>To</th><th>By</th><th>When</th><th>Reason</th></tr></thead>
                    <tbody>
                        @foreach($order->statusHistories->sortBy('created_at') as $history)
                            <tr>
                                <td>{{ $history->from_status ? order_status_label($history->from_status) : '—' }}</td>
                                <td>{{ order_status_label($history->to_status) }}</td>
                                <td>{{ $history->actor->name ?? 'System' }}</td>
                                <td>{{ $history->created_at->format('d M Y, g:i A') }}</td>
                                <td class="small muted">{{ $history->reason ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <aside class="stack">
        @if(!empty($allowed))
            <form class="card card-pad" method="POST" action="{{ route('admin.orders.status', $order) }}">
                @csrf
                @method('PATCH')
                <h4>Change status</h4>
                <div class="field mt-2">
                    <label for="status">New status</label>
                    <select class="input" id="status" name="status" required>
                        @foreach($allowed as $status)
                            <option value="{{ $status }}">{{ order_status_label($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="reason">Reason (optional)</label>
                    <input class="input" id="reason" name="reason">
                </div>
                <button class="btn btn-primary w-full" type="submit">Update status</button>
            </form>
        @else
            <div class="card card-pad"><h4>Status</h4><p class="small muted mt-1">This order is in a final state.</p></div>
        @endif
    </aside>
</div>
@endsection
