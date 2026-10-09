@extends('layouts.admin')
@section('title', $user->name)
@section('heading', 'User: ' . $user->name)
@section('content')
<nav class="small muted mb-2"><a href="{{ route('admin.users.index') }}">Users</a> · {{ $user->email }}</nav>

<div class="grid grid-2" style="align-items:start">
    <div class="stack">
        <div class="card card-pad">
            <h3>Profile</h3>
            <dl class="spec mt-2" style="display:grid;grid-template-columns:auto 1fr;gap:.4rem 1rem;font-size:.86rem">
                <dt class="muted">Name</dt><dd style="text-align:right">{{ $user->name }}</dd>
                <dt class="muted">Email</dt><dd style="text-align:right">{{ $user->email }}</dd>
                <dt class="muted">Phone</dt><dd style="text-align:right">{{ $user->phone ?? '—' }}</dd>
                <dt class="muted">Campus</dt><dd style="text-align:right">{{ $user->campus->name ?? '—' }}</dd>
                <dt class="muted">Role</dt><dd style="text-align:right">{{ ucfirst($user->role) }}</dd>
                <dt class="muted">Verified</dt><dd style="text-align:right">{{ $user->verified_at?->format('d M Y') ?? 'No' }}</dd>
                <dt class="muted">Registered</dt><dd style="text-align:right">{{ $user->created_at->format('d M Y') }}</dd>
            </dl>
            @if($user->bio)<p class="small mt-2">{{ $user->bio }}</p>@endif
        </div>

        <div class="card card-pad">
            <h3>Verification requests</h3>
            @forelse($user->verificationRequests->sortByDesc('created_at') as $vr)
                <div class="row-between" style="padding:.55rem 0;border-bottom:1px solid var(--border)">
                    <div>
                        <b>{{ $vr->institution_name }}</b>
                        <div class="small muted">{{ $vr->student_id_number }} · {{ $vr->created_at->format('d M Y') }}</div>
                    </div>
                    <a href="{{ route('admin.verifications.show', $vr) }}" class="pill {{ $vr->status === 'approved' ? 'pill-green' : ($vr->status === 'pending' ? 'pill-amber' : 'pill-red') }}">{{ verification_status_label($vr->status) }}</a>
                </div>
            @empty
                <p class="small muted">No verification submissions.</p>
            @endforelse
        </div>

        <div class="card card-pad">
            <h3>Listings ({{ $user->products->count() }})</h3>
            @forelse($user->products->take(10) as $product)
                <div class="row-between" style="padding:.5rem 0;border-bottom:1px solid var(--border)">
                    <a href="{{ route('admin.products.show', $product) }}" class="small">{{ \Illuminate\Support\Str::limit($product->title, 40) }}</a>
                    <span class="pill pill-gray">{{ product_status_label($product->status) }}</span>
                </div>
            @empty
                <p class="small muted">No listings.</p>
            @endforelse
        </div>
    </div>

    <aside class="stack">
        @unless($user->isAdmin())
            <form class="card card-pad" method="POST" action="{{ route('admin.users.status', $user) }}">
                @csrf
                @method('PATCH')
                <h4>Account status</h4>
                <div class="field mt-2">
                    <label for="status">Status</label>
                    <select class="input" id="status" name="status">
                        <option value="active" @selected($user->status === 'active')>Active</option>
                        <option value="suspended" @selected($user->status === 'suspended')>Suspended</option>
                    </select>
                </div>
                <button class="btn btn-primary w-full" type="submit">Update status</button>
            </form>
        @else
            <div class="card card-pad"><p class="small muted">Administrator accounts cannot be modified here.</p></div>
        @endunless

        <a class="btn btn-outline" href="{{ route('sellers.show', $user) }}">View public profile ↗</a>
    </aside>
</div>
@endsection
