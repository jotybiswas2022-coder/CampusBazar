@extends('layouts.app')
@section('title', 'Notifications · CampusBazar')
@section('content')
<div class="section-head">
    <div><span class="eyebrow">Activity</span><h2>Notifications</h2></div>
    <form action="{{ route('notifications.readAll') }}" method="POST">
        @csrf
        <button class="btn btn-outline btn-sm" type="submit">Mark all as read</button>
    </form>
</div>

@if($notifications->isEmpty())
    <div class="card empty">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg>
        <h3>No notifications</h3>
        <p>You are all caught up.</p>
    </div>
@else
    <div class="card">
        @foreach($notifications as $notification)
            <a href="{{ route('notifications.read', $notification->id) }}" class="row" style="gap:.8rem;padding:1rem 1.2rem;border-bottom:1px solid var(--border);{{ $notification->read_at ? '' : 'background:#f4fbf7' }}">
                <span class="pill {{ $notification->read_at ? 'pill-gray' : 'pill-green' }}" style="width:34px;height:34px;justify-content:center;border-radius:50%">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg>
                </span>
                <div class="flex-1">
                    <b>{{ $notification->data['title'] ?? 'Notification' }}</b>
                    <p class="small muted">{{ $notification->data['message'] ?? '' }}</p>
                </div>
                <span class="small muted">{{ $notification->created_at->diffForHumans() }}</span>
            </a>
        @endforeach
    </div>
    {{ $notifications->links('pagination.campusbazar') }}
@endif
@endsection
