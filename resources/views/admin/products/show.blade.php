@extends('layouts.admin')
@section('title', 'Review Product')
@section('heading', 'Review Product')
@section('content')
<nav class="small muted mb-2"><a href="{{ route('admin.products.index') }}">Products</a> · #{{ $product->id }}</nav>

<div class="grid grid-2" style="align-items:start">
    <div class="card card-pad">
        <h3>{{ $product->title }}</h3>
        <div class="badge-stack mt-2">
            <span class="pill pill-gray">{{ product_status_label($product->status) }}</span>
            <span class="pill pill-green">{{ condition_label($product->condition) }}</span>
        </div>
        <div class="gallery mt-3" style="grid-template-columns:repeat(3,1fr)">
            @forelse($product->images as $image)
                <div class="thumb"><img src="{{ $image->url }}" alt=""></div>
            @empty
                <p class="small muted">No photos uploaded.</p>
            @endforelse
        </div>
        <p class="mt-3" style="white-space:pre-line">{{ $product->description }}</p>
        <dl class="spec mt-3" style="display:grid;grid-template-columns:auto 1fr;gap:.4rem 1rem;font-size:.86rem">
            <dt class="muted">Price</dt><dd style="font-weight:600">{{ bdt($product->price) }}</dd>
            <dt class="muted">Category</dt><dd style="font-weight:600">{{ $product->category->name ?? '—' }}</dd>
            <dt class="muted">Campus</dt><dd style="font-weight:600">{{ $product->campus->name ?? '—' }}</dd>
            <dt class="muted">Seller</dt><dd style="font-weight:600">{{ $product->seller->name ?? '—' }}</dd>
        </dl>
        @if($product->rejection_reason)
            <div class="alert alert-error mt-3" style="margin-bottom:0">Previously rejected: {{ $product->rejection_reason }}</div>
        @endif
    </div>

    <aside class="stack">
        <form class="card card-pad" method="POST" action="{{ route('admin.products.approve', $product) }}">
            @csrf
            <h4>Approve listing</h4>
            <p class="small muted mt-1">Publish this listing to the marketplace.</p>
            <button class="btn btn-primary w-full mt-2" type="submit">Approve & publish</button>
        </form>

        <form class="card card-pad" method="POST" action="{{ route('admin.products.reject', $product) }}">
            @csrf
            <h4>Reject listing</h4>
            <div class="field mt-2">
                <label for="rejection_reason">Reason</label>
                <textarea class="input" id="rejection_reason" name="rejection_reason" required style="min-height:70px"></textarea>
            </div>
            <button class="btn btn-danger w-full" type="submit">Reject</button>
        </form>

        <form class="card card-pad" method="POST" action="{{ route('admin.products.remove', $product) }}">
            @csrf
            <h4>Remove listing</h4>
            <p class="small muted mt-1">Take this listing down from the marketplace.</p>
            <button class="btn btn-outline w-full mt-2" type="submit">Remove from marketplace</button>
        </form>

        <a class="btn btn-ghost" href="{{ route('products.show', $product) }}" target="_blank">View public page ↗</a>
    </aside>
</div>
@endsection
