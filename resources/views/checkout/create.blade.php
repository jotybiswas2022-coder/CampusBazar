@extends('layouts.app')
@section('title', 'Checkout · CampusBazar')
@section('content')
<div class="section-head"><div><span class="eyebrow">Checkout</span><h2>Confirm your order</h2></div></div>

<form method="POST" action="{{ route('checkout.store', $product) }}">
    @csrf
    <div class="grid grid-2" style="align-items:start;grid-template-columns:1.4fr 1fr">
        <div class="card card-pad">
            <h3>Delivery details</h3>
            <div class="mt-2">
                <div class="grid grid-2" style="gap:0 1rem">
                    <div class="field">
                        <label for="recipient_name">Recipient name</label>
                        <input class="input @error('recipient_name') input-error @enderror" id="recipient_name" name="recipient_name" value="{{ old('recipient_name', auth()->user()->name) }}" required>
                        @error('recipient_name')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="phone">Phone</label>
                        <input class="input @error('phone') input-error @enderror" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required>
                        @error('phone')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="field">
                    <label for="address">Full address</label>
                    <textarea class="input @error('address') input-error @enderror" id="address" name="address" required placeholder="House / road / area">{{ old('address') }}</textarea>
                    @error('address')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="grid grid-2" style="gap:0 1rem">
                    <div class="field">
                        <label for="district">District</label>
                        <input class="input @error('district') input-error @enderror" id="district" name="district" value="{{ old('district') }}" required>
                        @error('district')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="upazila">Upazila / Thana (optional)</label>
                        <input class="input" id="upazila" name="upazila" value="{{ old('upazila') }}">
                    </div>
                </div>
                <div class="field">
                    <label for="delivery_instructions">Delivery instructions (optional)</label>
                    <textarea class="input" id="delivery_instructions" name="delivery_instructions" style="min-height:70px">{{ old('delivery_instructions') }}</textarea>
                </div>
            </div>

            <div class="alert alert-warning mt-2">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a4 4 0 0 0-8 0v2"/></svg>
                <div><strong>Cash on Delivery only.</strong> Pay the rider in cash when your item arrives. No online payment is taken.</div>
            </div>
        </div>

        <aside class="card card-pad">
            <h3>Order summary</h3>
            <div class="seller-box mt-2">
                @if($product->images->first())
                    <img src="{{ $product->images->first()->url }}" alt="{{ $product->title }}" class="thumb-sm" style="width:52px;height:52px">
                @endif
                <div class="flex-1">
                    <b>{{ \Illuminate\Support\Str::limit($product->title, 40) }}</b>
                    <div class="small muted">{{ $product->category->name ?? '' }}</div>
                </div>
            </div>
            <dl class="spec mt-3">
                <dt>Item price</dt><dd>{{ bdt($product->price) }}</dd>
                <dt>Delivery charge</dt><dd>{{ bdt($deliveryCharge) }}</dd>
                <dt>Payment method</dt><dd>Cash on Delivery</dd>
            </dl>
            <div class="row-between mt-3" style="border-top:1px solid var(--border);padding-top:.8rem">
                <b>Total</b>
                <span class="product-price" style="font-size:1.4rem">{{ bdt($total) }}</span>
            </div>
            <button class="btn btn-primary w-full mt-3" type="submit">Place order (COD)</button>
            <a class="btn btn-outline w-full mt-2" href="{{ route('products.show', $product) }}">Back to item</a>
        </aside>
    </div>
</form>
@endsection
