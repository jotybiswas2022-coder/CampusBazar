@extends('layouts.admin')
@section('title', 'Orders')
@section('heading', 'Orders')
@section('content')
<form class="card card-pad mb-3" method="GET" action="{{ route('admin.orders.index') }}">
    <div class="grid grid-3" style="gap:1rem;align-items:end">
        <div class="field" style="margin-bottom:0">
            <label>Order number</label>
            <input class="input" type="search" name="q" value="{{ request('q') }}" placeholder="Search order #">
        </div>
        <div class="field" style="margin-bottom:0">
            <label>Status</label>
            <select class="input" name="status">
                <option value="">Any status</option>
                @foreach(\App\Enums\OrderStatus::all() as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ order_status_label($status) }}</option>
                @endforeach
            </select>
        </div>
        <div class="field" style="margin-bottom:0">
            <label>Payment</label>
            <select class="input" name="payment_status">
                <option value="">Any payment</option>
                @foreach(\App\Enums\PaymentStatus::all() as $status)
                    <option value="{{ $status }}" @selected(request('payment_status') === $status)>{{ payment_status_label($status) }}</option>
                @endforeach
            </select>
        </div>
        <div class="row" style="gap:.5rem">
            <button class="btn btn-primary" type="submit">Filter</button>
            <a class="btn btn-outline" href="{{ route('admin.orders.index') }}">Reset</a>
        </div>
    </div>
</form>

@if($orders->isEmpty())
    <div class="card empty"><h3>No orders</h3><p>No orders match your filters.</p></div>
@else
    <div class="table-wrap">
        <table>
            <thead><tr><th>Order</th><th>Buyer</th><th>Seller</th><th>Total</th><th>Payment</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td><b>#{{ $order->order_number }}</b><div class="small muted">{{ $order->created_at->format('d M Y') }}</div></td>
                        <td>{{ $order->buyer->name ?? '—' }}</td>
                        <td>{{ $order->seller->name ?? '—' }}</td>
                        <td>{{ bdt($order->total) }}</td>
                        <td><span class="pill {{ $order->isPaid() ? 'pill-green' : 'pill-amber' }}">{{ $order->isPaid() ? 'Paid' : 'Unpaid' }}</span></td>
                        <td><span class="pill pill-gray">{{ order_status_label($order->status) }}</span></td>
                        <td><a class="btn btn-outline btn-sm" href="{{ route('admin.orders.show', $order) }}">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $orders->links('pagination.campusbazar') }}
@endif
@endsection
