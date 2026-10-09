@extends('layouts.admin')
@section('title', 'Products')
@section('heading', 'Product Moderation')
@section('content')
<div class="tabs">
    <a class="{{ $status === 'pending_approval' ? 'active' : '' }}" href="{{ route('admin.products.index', ['status' => 'pending_approval']) }}">Pending ({{ $counts['pending_approval'] }})</a>
    <a class="{{ $status === 'active' ? 'active' : '' }}" href="{{ route('admin.products.index', ['status' => 'active']) }}">Active ({{ $counts['active'] }})</a>
    <a class="{{ $status === 'reserved' ? 'active' : '' }}" href="{{ route('admin.products.index', ['status' => 'reserved']) }}">Reserved ({{ $counts['reserved'] }})</a>
    <a class="{{ $status === 'sold' ? 'active' : '' }}" href="{{ route('admin.products.index', ['status' => 'sold']) }}">Sold ({{ $counts['sold'] }})</a>
    <a class="{{ $status === 'rejected' ? 'active' : '' }}" href="{{ route('admin.products.index', ['status' => 'rejected']) }}">Rejected ({{ $counts['rejected'] }})</a>
    <a class="{{ $status === 'removed' ? 'active' : '' }}" href="{{ route('admin.products.index', ['status' => 'removed']) }}">Removed ({{ $counts['removed'] }})</a>
    <a class="{{ $status === 'all' ? 'active' : '' }}" href="{{ route('admin.products.index', ['status' => 'all']) }}">All</a>
</div>

@if($products->isEmpty())
    <div class="card empty"><h3>No products</h3><p>Nothing to moderate in this category.</p></div>
@else
    <div class="table-wrap">
        <table>
            <thead><tr><th>Product</th><th>Seller</th><th>Price</th><th>Category</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>
                            <div class="row" style="gap:.7rem">
                                @if($product->images->first())<img class="thumb-sm" src="{{ $product->images->first()->url }}" alt="">@endif
                                <span style="font-weight:600">{{ \Illuminate\Support\Str::limit($product->title, 38) }}</span>
                            </div>
                        </td>
                        <td>{{ $product->seller->name ?? '—' }}</td>
                        <td>{{ bdt($product->price) }}</td>
                        <td>{{ $product->category->name ?? '—' }}</td>
                        <td>
                            @php $pill = match($product->status) {
                                \App\Enums\ProductStatus::ACTIVE => 'pill-green',
                                \App\Enums\ProductStatus::PENDING_APPROVAL => 'pill-amber',
                                \App\Enums\ProductStatus::SOLD => 'pill-blue',
                                \App\Enums\ProductStatus::REJECTED, \App\Enums\ProductStatus::REMOVED => 'pill-red',
                                default => 'pill-gray',
                            }; @endphp
                            <span class="pill {{ $pill }}">{{ product_status_label($product->status) }}</span>
                        </td>
                        <td><a class="btn btn-primary btn-sm" href="{{ route('admin.products.show', $product) }}">Review</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $products->links('pagination.campusbazar') }}
@endif
@endsection
