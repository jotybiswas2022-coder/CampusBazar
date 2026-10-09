@extends('layouts.app')
@section('title', 'My Listings · CampusBazar')
@push('styles')
<style>.list-thumb{width:54px;height:54px;border-radius:10px;object-fit:cover;background:#eef6f1;border:1px solid var(--border)}</style>
@endpush
@section('content')
<div class="section-head">
    <div><span class="eyebrow">Selling</span><h2>My listings</h2></div>
    <a class="btn btn-primary" href="{{ route('seller.products.create') }}">+ New listing</a>
</div>

<div class="tabs">
    <a class="{{ !request('status') ? 'active' : '' }}" href="{{ route('seller.products.index') }}">All</a>
    @foreach(\App\Enums\ProductStatus::all() as $status)
        <a class="{{ request('status') === $status ? 'active' : '' }}" href="{{ route('seller.products.index', ['status' => $status]) }}">{{ product_status_label($status) }}</a>
    @endforeach
</div>

@if($products->isEmpty())
    <div class="card empty">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="M3 8l9 5 9-5"/></svg>
        <h3>No listings found</h3>
        <p>Post your first item and start selling on campus.</p>
        <a class="btn btn-primary mt-2" href="{{ route('seller.products.create') }}">Create a listing</a>
    </div>
@else
    <div class="table-wrap">
        <table>
            <thead><tr><th>Item</th><th>Price</th><th>Category</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>
                            <div class="row" style="gap:.7rem">
                                @if($product->images->first())
                                    <img class="list-thumb" src="{{ $product->images->first()->url }}" alt="">
                                @endif
                                <a href="{{ route('products.show', $product) }}" style="font-weight:600">{{ \Illuminate\Support\Str::limit($product->title, 42) }}</a>
                            </div>
                        </td>
                        <td>{{ bdt($product->price) }}</td>
                        <td>{{ $product->category->name ?? '—' }}</td>
                        <td>
                            @php $pill = match($product->status) {
                                \App\Enums\ProductStatus::ACTIVE => 'pill-green',
                                \App\Enums\ProductStatus::SOLD => 'pill-blue',
                                \App\Enums\ProductStatus::PENDING_APPROVAL => 'pill-amber',
                                \App\Enums\ProductStatus::REJECTED, \App\Enums\ProductStatus::REMOVED => 'pill-red',
                                default => 'pill-gray',
                            }; @endphp
                            <span class="pill {{ $pill }}">{{ product_status_label($product->status) }}</span>
                        </td>
                        <td>
                            <div class="row" style="gap:.35rem">
                                <a class="btn btn-outline btn-sm" href="{{ route('seller.products.edit', $product) }}">Edit</a>
                                <form action="{{ route('seller.products.destroy', $product) }}" method="POST" data-confirm="Remove this listing?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $products->links('pagination.campusbazar') }}
@endif
@endsection
@push('scripts')
<script>
    document.querySelectorAll('form[data-confirm]').forEach(function (f) {
        f.addEventListener('submit', function (e) { if (!confirm(f.getAttribute('data-confirm'))) e.preventDefault(); });
    });
</script>
@endpush
