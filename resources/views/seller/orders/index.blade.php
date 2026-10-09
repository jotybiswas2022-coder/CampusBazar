@extends('layouts.app')
@section('title', 'Sales · CampusBazar')
@section('content')
<div class="section-head"><div><span class="eyebrow">Selling</span><h2>Sales & orders</h2></div></div>

<div class="tabs">
    <a class="{{ !request('status') ? 'active' : '' }}" href="{{ route('seller.orders.index') }}">All</a>
    @foreach(\App\Enums\OrderStatus::all() as $status)
        <a class="{{ request('status') === $status ? 'active' : '' }}" href="{{ route('seller.orders.index', ['status' => $status]) }}">{{ order_status_label($status) }}</a>
    @endforeach
</div>

@if($orders->isEmpty())
    <div class="card empty"><h3>No sales yet</h3><p>Orders from buyers will appear here.</p></div>
@else
    <div class="table-wrap">
        <table>
            <thead><tr><th>Order</th><th>Buyer</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td><b>#{{ $order->order_number }}</b><div class="small muted">{{ $order->created_at->format('d M Y') }}</div></td>
                        <td>{{ $order->buyer->name ?? '—' }}</td>
                        <td>{{ $order->items->count() }}</td>
                        <td>{{ bdt($order->total) }}</td>
                        <td><span class="pill {{ $order->isPaid() ? 'pill-green' : 'pill-amber' }}">{{ $order->isPaid() ? 'Paid' : 'Unpaid' }}</span></td>
                        <td><span class="pill pill-gray">{{ order_status_label($order->status) }}</span></td>
                        <td><a class="btn btn-outline btn-sm" href="{{ route('seller.orders.show', $order) }}">Manage</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $orders->links('pagination.campusbazar') }}
@endif
@endsection
