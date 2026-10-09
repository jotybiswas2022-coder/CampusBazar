@extends('layouts.app')
@section('title', 'Chat with ' . ($other->name ?? 'Student') . ' · CampusBazar')
@push('styles')
<style>
.chat{display:flex;flex-direction:column;height:min(70vh,620px)}
.chat-body{flex:1;overflow-y:auto;padding:1.2rem;display:flex;flex-direction:column;gap:.7rem}
.bubble{max-width:75%;padding:.6rem .9rem;border-radius:14px;font-size:.9rem;line-height:1.45}
.bubble.mine{align-self:flex-end;background:linear-gradient(135deg,var(--primary),var(--emerald));color:#fff;border-bottom-right-radius:4px}
.bubble.theirs{align-self:flex-start;background:#f1f5f2;color:var(--text);border-bottom-left-radius:4px}
.bubble .meta{display:block;font-size:.68rem;opacity:.7;margin-top:.25rem}
.chat-form{display:flex;gap:.5rem;padding:1rem;border-top:1px solid var(--border)}
</style>
@endpush
@section('content')
<div class="section-head">
    <div class="row" style="gap:.8rem">
        <a class="btn btn-outline btn-sm" href="{{ route('messages.index') }}">←</a>
        <span class="avatar">{{ $other?->initials() ?? '?' }}</span>
        <div>
            <h2>{{ $other?->name ?? 'Student' }}</h2>
            @if($conversation->product)
                <a class="small" style="color:var(--primary)" href="{{ route('products.show', $conversation->product) }}">Re: {{ \Illuminate\Support\Str::limit($conversation->product->title, 45) }}</a>
            @endif
        </div>
    </div>
    <div class="menu">
        <button class="btn btn-outline btn-sm" data-menu-toggle>Options</button>
        <div class="menu-panel">
            <a href="#" onclick="event.preventDefault();document.getElementById('reportForm').classList.toggle('hidden')">Report conversation</a>
        </div>
    </div>
</div>

<div class="card chat">
    <div class="chat-body" id="chatBody">
        @forelse($conversation->messages as $message)
            <div class="bubble {{ $message->sender_id === auth()->id() ? 'mine' : 'theirs' }}">
                {{ $message->body }}
                <span class="meta">{{ $message->created_at->format('d M, g:i A') }}</span>
            </div>
        @empty
            <p class="empty small" style="padding:1rem">No messages yet. Say hello!</p>
        @endforelse
    </div>
    <form class="chat-form" method="POST" action="{{ route('messages.store', $conversation) }}">
        @csrf
        <input class="input flex-1" type="text" name="body" placeholder="Type a message…" autocomplete="off" required>
        <button class="btn btn-primary" type="submit">Send</button>
    </form>
</div>

<form id="reportForm" class="card card-pad mt-3 hidden" method="POST" action="{{ route('messages.report', $conversation) }}">
    @csrf
    <h4>Report this conversation</h4>
    <div class="field mt-2">
        <label for="reason">Reason</label>
        <input class="input" id="reason" name="reason" required placeholder="e.g. Fraud, abuse, spam">
    </div>
    <div class="field">
        <label for="details">Details (optional)</label>
        <textarea class="input" id="details" name="details" style="min-height:80px"></textarea>
    </div>
    <button class="btn btn-danger" type="submit">Submit report</button>
</form>
@endsection
@push('scripts')
<script>
    var body = document.getElementById('chatBody');
    if (body) body.scrollTop = body.scrollHeight;
</script>
@endpush
