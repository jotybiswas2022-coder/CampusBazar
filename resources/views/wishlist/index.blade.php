@extends('layouts.app')
@section('title', 'Wishlist · CampusBazar')
@section('content')
<div class="section-head"><div><span class="eyebrow">Saved items</span><h2>My wishlist</h2></div></div>

@if($items->isEmpty())
    <div class="card empty">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 1 0-7.8 7.8l8.8 8.8 8.8-8.8a5.5 5.5 0 0 0 0-7.8z"/></svg>
        <h3>Your wishlist is empty</h3>
        <p>Save items you like and find them here later.</p>
        <a class="btn btn-primary mt-2" href="{{ route('marketplace.index') }}">Browse marketplace</a>
    </div>
@else
    <div class="grid grid-4">
        @foreach($items as $item)
            @if($item->product)
                <div>
                    @include('partials.product-card', ['product' => $item->product])
                    <form action="{{ route('wishlist.destroy', $item->product) }}" method="POST" class="mt-1">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm w-full" type="submit">Remove</button>
                    </form>
                </div>
            @endif
        @endforeach
    </div>
    {{ $items->links('pagination.campusbazar') }}
@endif
@endsection
