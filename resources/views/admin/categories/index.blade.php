@extends('layouts.admin')
@section('title', 'Categories')
@section('heading', 'Categories')
@section('content')
<div class="grid grid-2" style="align-items:start;grid-template-columns:1fr 1.6fr">
    <form class="card card-pad" method="POST" action="{{ route('admin.categories.store') }}">
        @csrf
        <h3>Add category</h3>
        <div class="field mt-2">
            <label for="name">Name</label>
            <input class="input" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="field">
            <label for="icon">Icon key (optional)</label>
            <input class="input" id="icon" name="icon" value="{{ old('icon') }}" placeholder="e.g. book, device">
        </div>
        <div class="field">
            <label for="sort_order">Sort order</label>
            <input class="input" id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', 0) }}">
        </div>
        <div class="field">
            <label for="description">Description (optional)</label>
            <textarea class="input" id="description" name="description" style="min-height:70px">{{ old('description') }}</textarea>
        </div>
        <button class="btn btn-primary w-full" type="submit">Create category</button>
    </form>

    <div>
        @if($categories->isEmpty())
            <div class="card empty"><h3>No categories</h3></div>
        @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Name</th><th>Slug</th><th>Products</th><th>Order</th><th>Active</th><th></th></tr></thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>
                                    <form method="POST" action="{{ route('admin.categories.update', $category) }}" id="cat-{{ $category->id }}">
                                        @csrf
                                        @method('PUT')
                                    </form>
                                    <input class="input" form="cat-{{ $category->id }}" name="name" value="{{ $category->name }}" style="min-width:160px">
                                    <input type="hidden" form="cat-{{ $category->id }}" name="icon" value="{{ $category->icon }}">
                                    <input type="hidden" form="cat-{{ $category->id }}" name="description" value="{{ $category->description }}">
                                </td>
                                <td class="small muted">{{ $category->slug }}</td>
                                <td>{{ $category->products_count }}</td>
                                <td><input class="input" form="cat-{{ $category->id }}" name="sort_order" type="number" min="0" value="{{ $category->sort_order }}" style="width:70px"></td>
                                <td><label class="checkbox"><input type="checkbox" form="cat-{{ $category->id }}" name="is_active" value="1" @checked($category->is_active)></label></td>
                                <td>
                                    <div class="row" style="gap:.35rem">
                                        <button class="btn btn-outline btn-sm" form="cat-{{ $category->id }}" type="submit">Save</button>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" data-confirm="Delete this category?">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $categories->links('pagination.campusbazar') }}
        @endif
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.querySelectorAll('form[data-confirm]').forEach(function (f) {
        f.addEventListener('submit', function (e) { if (!confirm(f.getAttribute('data-confirm'))) e.preventDefault(); });
    });
</script>
@endpush
