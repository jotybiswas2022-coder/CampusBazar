@extends('layouts.admin')
@section('title', 'COD Payments')
@section('heading', 'Cash on Delivery')
@section('content')
<div class="grid grid-3 mb-3">
    <div class="card stat"><span class="label">Awaiting COD confirmation</span><span class="value amber">{{ number_format($totals['awaiting']) }}</span></div>
    <div class="card stat"><span class="label">COD collected</span><span class="value">{{ bdt($totals['collected']) }}</span></div>
    <div class="card stat"><span class="label">Outstanding (unpaid)</span><span class="value red">{{ bdt($totals['unpaid']) }}</span></div>
</div>

<div class="tabs">
    <a class="{{ $filter === 'awaiting' ? 'active' : '' }}" href="{{ route('admin.cod.index', ['filter' => 'awaiting']) }}">Delivered · awaiting payment</a>
    <a class="{{ $filter === 'paid' ? 'active' : '' }}" href="{{ route('admin.cod.index', ['filter' => 'paid']) }}">Collected</a>
    <a class="{{ $filter === 'unpaid' ? 'active' : '' }}" href="{{ route('admin.cod.index', ['filter' => 'unpaid']) }}">All unpaid</a>
</div>

@if($orders->isEmpty())
    <div class="card empty"><h3>Nothing here</h3><p>No orders match this filter.</p></div>
@else
    <div class="table-wrap">
        <table>
            <thead><tr><th>Order</th><th>Buyer</th><th>Seller</th><th>Amount</th><th>Status</th><th>Payment</th><th></th></tr></thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td><b>#{{ $order->order_number }}</b><div class="small muted">{{ $order->created_at->format('d M Y') }}</div></td>
                        <td>{{ $order->buyer->name ?? '—' }}</td>
                        <td>{{ $order->seller->name ?? '—' }}</td>
                        <td><b>{{ bdt($order->total) }}</b></td>
                        <td><span class="pill pill-gray">{{ order_status_label($order->status) }}</span></td>
                        <td><span class="pill {{ $order->isPaid() ? 'pill-green' : 'pill-amber' }}">{{ $order->isPaid() ? 'Paid' : 'Unpaid' }}</span></td>
                        <td>
                            @if(! $order->isPaid())
                                <form method="POST" action="{{ route('admin.cod.markPaid', $order) }}" data-confirm="Mark COD as collected for #{{ $order->order_number }}?">
                                    @csrf
                                    <button class="btn btn-primary btn-sm" type="submit">Mark collected</button>
                                </form>
                            @else
                                <span class="small muted">{{ $order->payment_collected_at?->format('d M Y') }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $orders->links('pagination.campusbazar') }}
@endif
@endsection
@push('scripts')
<script>
    document.querySelectorAll('form[data-confirm]').forEach(function (f) {
        f.addEventListener('submit', function (e) { if (!confirm(f.getAttribute('data-confirm'))) e.preventDefault(); });
    });
</script>
@endpush
