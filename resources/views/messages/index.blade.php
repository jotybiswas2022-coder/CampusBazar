@extends('layouts.app')
@section('title', 'Messages · CampusBazar')
@push('styles')
<style>.conv{display:flex;gap:.8rem;align-items:center;padding:1rem 1.2rem;border-bottom:1px solid var(--border)}
.conv:last-child{border-bottom:none}
.conv:hover{background:#f9fcfb}
.conv.unread{background:#f4fbf7}</style>
@endpush
@section('content')
<div class="section-head"><div><span class="eyebrow">Inbox</span><h2>Messages</h2></div></div>

@if($conversations->isEmpty())
    <div class="card empty">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        <h3>No conversations yet</h3>
        <p>Message a seller from any product page to start chatting.</p>
    </div>
@else
    <div class="card">
        @foreach($conversations as $conversation)
            <a class="conv {{ $conversation->unread ? 'unread' : '' }}" href="{{ route('messages.show', $conversation) }}">
                <span class="avatar">{{ $conversation->other?->initials() ?? '?' }}</span>
                <div class="flex-1">
                    <div class="row-between">
                        <b>{{ $conversation->other?->name ?? 'Student' }}</b>
                        <span class="small muted">{{ $conversation->last_message_at?->diffForHumans() }}</span>
                    </div>
                    <div class="small muted">
                        @if($conversation->product)Re: {{ \Illuminate\Support\Str::limit($conversation->product->title, 40) }}@else Conversation @endif
                    </div>
                </div>
                @if($conversation->unread)
                    <span class="pill pill-green">{{ $conversation->unread }}</span>
                @endif
            </a>
        @endforeach
    </div>
    {{ $conversations->links('pagination.campusbazar') }}
@endif
@endsection
