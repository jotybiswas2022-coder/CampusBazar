@extends('layouts.admin')
@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('content')
<div class="grid grid-4 mb-3">
    <div class="card stat"><span class="label">Students</span><span class="value">{{ number_format($stats['total_users']) }}</span></div>
    <div class="card stat"><span class="label">Verified students</span><span class="value">{{ number_format($stats['verified_students']) }}</span></div>
    <div class="card stat"><span class="label">Pending verifications</span><span class="value amber">{{ number_format($stats['pending_verifications']) }}</span></div>
    <div class="card stat"><span class="label">Unverified students</span><span class="value blue">{{ number_format($stats['unverified_students']) }}</span></div>
</div>
<div class="grid grid-4 mb-3">
    <div class="card stat"><span class="label">Active listings</span><span class="value">{{ number_format($stats['active_listings']) }}</span></div>
    <div class="card stat"><span class="label">Pending listings</span><span class="value amber">{{ number_format($stats['pending_products']) }}</span></div>
    <div class="card stat"><span class="label">Total orders</span><span class="value blue">{{ number_format($stats['total_orders']) }}</span></div>
    <div class="card stat"><span class="label">Open reports</span><span class="value red">{{ number_format($stats['open_reports']) }}</span></div>
</div>
<div class="grid grid-4 mb-3">
    <div class="card stat"><span class="label">Pending COD orders</span><span class="value amber">{{ number_format($stats['pending_cod_orders']) }}</span></div>
    <div class="card stat"><span class="label">Delivered · awaiting COD</span><span class="value blue">{{ number_format($stats['delivered_awaiting_cod']) }}</span></div>
    <div class="card stat"><span class="label">COD confirmed</span><span class="value">{{ number_format($stats['confirmed_cod']) }}</span></div>
    <div class="card stat"><span class="label">Cancelled / Returned</span><span class="value red">{{ number_format($stats['cancelled_orders'] + $stats['returned_orders']) }}</span></div>
</div>

<div class="grid grid-2" style="align-items:start">
    <div class="card card-pad">
        <div class="row-between mb-2"><h3>Recent verification requests</h3><a class="btn btn-outline btn-sm" href="{{ route('admin.verifications.index') }}">Review</a></div>
        @forelse($recentVerifications as $verification)
            <div class="row-between" style="padding:.55rem 0;border-bottom:1px solid var(--border)">
                <div>
                    <a href="{{ route('admin.verifications.show', $verification) }}" style="font-weight:600">{{ $verification->user->name ?? 'User' }}</a>
                    <div class="small muted">{{ $verification->institution_name }}</div>
                </div>
                <span class="pill {{ $verification->status === 'pending' ? 'pill-amber' : 'pill-green' }}">{{ verification_status_label($verification->status) }}</span>
            </div>
        @empty
            <p class="small muted">No verification requests.</p>
        @endforelse
    </div>

    <div class="card card-pad">
        <div class="row-between mb-2"><h3>Recent orders</h3><a class="btn btn-outline btn-sm" href="{{ route('admin.orders.index') }}">View all</a></div>
        @forelse($recentOrders as $order)
            <div class="row-between" style="padding:.55rem 0;border-bottom:1px solid var(--border)">
                <div>
                    <a href="{{ route('admin.orders.show', $order) }}" style="font-weight:600">#{{ $order->order_number }}</a>
                    <div class="small muted">{{ $order->buyer->name ?? '—' }} → {{ $order->seller->name ?? '—' }}</div>
                </div>
                <span class="pill pill-gray">{{ order_status_label($order->status) }}</span>
            </div>
        @empty
            <p class="small muted">No orders.</p>
        @endforelse
    </div>
</div>

<div class="card card-pad mt-3">
    <div class="row-between mb-2"><h3>Recent activity</h3><a class="btn btn-outline btn-sm" href="{{ route('admin.audit.index') }}">Audit log</a></div>
    @forelse($recentActivity as $log)
        <div class="row-between" style="padding:.5rem 0;border-bottom:1px solid var(--border)">
            <div class="small"><span class="pill pill-gray">{{ $log->action }}</span> {{ $log->description }}</div>
            <span class="small muted">{{ $log->created_at->diffForHumans() }}</span>
        </div>
    @empty
        <p class="small muted">No activity yet.</p>
    @endforelse
</div>
@endsection
