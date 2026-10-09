@php
    $image = $product->images->first();
    $sellerName = $product->seller->name ?? 'Student';
@endphp
<article class="card card-hover product-card">
    <a href="{{ route('products.show', $product) }}" class="product-thumb" aria-label="{{ $product->title }}">
        @if($image)
            <img src="{{ $image->url }}" alt="{{ $product->title }}" loading="lazy">
        @else
            <span class="ph">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9.5" r="1.5"/><path d="m21 16-5-5L5 21"/></svg>
            </span>
        @endif
        <div class="thumb-badges">
            @if($product->condition)
                <span class="pill pill-green">{{ condition_label($product->condition) }}</span>
            @endif
            @if($product->status !== \App\Enums\ProductStatus::ACTIVE)
                <span class="pill pill-gray">{{ product_status_label($product->status) }}</span>
            @endif
        </div>
    </a>
    <div class="product-body">
        <a href="{{ route('products.show', $product) }}" class="product-title">{{ $product->title }}</a>
        <div class="product-price">{{ bdt($product->price) }}</div>
        <div class="product-meta">
            <span>{{ $product->category->name ?? 'Uncategorised' }}</span>
            <span>{{ $product->campus->name ?? '' }}</span>
        </div>
        <div class="row-between" style="padding-top:.6rem">
            <span class="small muted">{{ $sellerName }}</span>
            @auth
                @if(auth()->id() !== $product->user_id)
                    <form action="{{ route('messages.start', $product) }}" method="POST">
                        @csrf
                        <button class="btn btn-ghost btn-sm" type="submit">Message</button>
                    </form>
                @endif
            @else
                <a class="btn btn-ghost btn-sm" href="{{ route('login') }}">Message</a>
            @endauth
        </div>
    </div>
</article>
