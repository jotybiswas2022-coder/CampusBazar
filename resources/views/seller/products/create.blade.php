@extends('layouts.app')
@section('title', 'New Listing · CampusBazar')
@section('content')
<div style="max-width:760px;margin:0 auto">
    <div class="section-head">
        <div><span class="eyebrow">Selling</span><h2>Post a new listing</h2></div>
        <a class="btn btn-outline btn-sm" href="{{ route('seller.products.index') }}">Back</a>
    </div>

    @if(! auth()->user()->isVerified())
        <div class="alert alert-warning mb-3">You can create a listing, but it will only go live after your Student ID is verified.</div>
    @endif

    <form class="card card-pad" method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="field">
            <label for="title">Listing title</label>
            <input class="input @error('title') input-error @enderror" id="title" name="title" value="{{ old('title') }}" required placeholder="e.g. Casio fx-991EX Scientific Calculator">
            @error('title')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="grid grid-2" style="gap:0 1rem">
            <div class="field">
                <label for="category_id">Category</label>
                <select class="input @error('category_id') input-error @enderror" id="category_id" name="category_id" required>
                    <option value="">Select category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="campus_id">Campus (optional)</label>
                <select class="input" id="campus_id" name="campus_id">
                    <option value="">Select campus</option>
                    @foreach($campuses as $campus)
                        <option value="{{ $campus->id }}" @selected(old('campus_id', auth()->user()->campus_id) == $campus->id)>{{ $campus->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-2" style="gap:0 1rem">
            <div class="field">
                <label for="price">Price (৳)</label>
                <input class="input @error('price') input-error @enderror" id="price" type="number" min="1" step="1" name="price" value="{{ old('price') }}" required>
                @error('price')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="condition">Condition</label>
                <select class="input @error('condition') input-error @enderror" id="condition" name="condition" required>
                    @foreach($conditions as $condition)
                        <option value="{{ $condition }}" @selected(old('condition') === $condition)>{{ condition_label($condition) }}</option>
                    @endforeach
                </select>
                @error('condition')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="field">
            <label for="description">Description</label>
            <textarea class="input @error('description') input-error @enderror" id="description" name="description" required placeholder="Describe the item, its condition, age and any defects.">{{ old('description') }}</textarea>
            @error('description')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field">
            <label for="images">Photos (up to 6)</label>
            <input class="input @error('images') input-error @enderror" id="images" type="file" name="images[]" accept="image/*" multiple>
            <span class="hint">JPG, PNG or WEBP. Max 5 MB each. Clear photos sell faster.</span>
            @error('images')<span class="field-error">{{ $message }}</span>@enderror
            @error('images.*')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <button class="btn btn-primary w-full" type="submit">Submit for approval</button>
    </form>
</div>
@endsection
