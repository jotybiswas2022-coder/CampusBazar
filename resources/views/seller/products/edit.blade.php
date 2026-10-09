@extends('layouts.app')
@section('title', 'Edit Listing · CampusBazar')
@section('content')
<div style="max-width:760px;margin:0 auto">
    <div class="section-head">
        <div><span class="eyebrow">Selling</span><h2>Edit listing</h2></div>
        <a class="btn btn-outline btn-sm" href="{{ route('seller.products.index') }}">Back</a>
    </div>

    @if($product->status === \App\Enums\ProductStatus::REJECTED && $product->rejection_reason)
        <div class="alert alert-error mb-3">
            <div><strong>Listing rejected:</strong> {{ $product->rejection_reason }} Edit and resubmit for approval.</div>
        </div>
    @endif

    @if($product->images->isNotEmpty())
        <div class="card card-pad mb-3">
            <h4>Current photos</h4>
            <div class="gallery mt-2" style="grid-template-columns:repeat(6,1fr)">
                @foreach($product->images as $image)
                    <div class="thumb"><img src="{{ $image->url }}" alt=""></div>
                @endforeach
            </div>
        </div>
    @endif

    <form class="card card-pad" method="POST" action="{{ route('seller.products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="field">
            <label for="title">Listing title</label>
            <input class="input @error('title') input-error @enderror" id="title" name="title" value="{{ old('title', $product->title) }}" required>
            @error('title')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="grid grid-2" style="gap:0 1rem">
            <div class="field">
                <label for="category_id">Category</label>
                <select class="input" id="category_id" name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="campus_id">Campus (optional)</label>
                <select class="input" id="campus_id" name="campus_id">
                    <option value="">Select campus</option>
                    @foreach($campuses as $campus)
                        <option value="{{ $campus->id }}" @selected(old('campus_id', $product->campus_id) == $campus->id)>{{ $campus->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-2" style="gap:0 1rem">
            <div class="field">
                <label for="price">Price (৳)</label>
                <input class="input @error('price') input-error @enderror" id="price" type="number" min="1" name="price" value="{{ old('price', (int) $product->price) }}" required>
                @error('price')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="condition">Condition</label>
                <select class="input" id="condition" name="condition" required>
                    @foreach($conditions as $condition)
                        <option value="{{ $condition }}" @selected(old('condition', $product->condition) === $condition)>{{ condition_label($condition) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="field">
            <label for="description">Description</label>
            <textarea class="input @error('description') input-error @enderror" id="description" name="description" required>{{ old('description', $product->description) }}</textarea>
            @error('description')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field">
            <label for="images">Add more photos <span class="muted small">(optional)</span></label>
            <input class="input" id="images" type="file" name="images[]" accept="image/*" multiple>
            <span class="hint">New photos are added to your existing ones.</span>
            @error('images.*')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <button class="btn btn-primary w-full" type="submit">Save changes</button>
    </form>
</div>
@endsection
