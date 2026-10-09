@extends('layouts.admin')
@section('title', 'Reviews')
@section('heading', 'Reviews')
@section('content')
<div class="tabs">
    <a class="{{ !request('status') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">All</a>
    <a class="{{ request('status') === 'published' ? 'active' : '' }}" href="{{ route('admin.reviews.index', ['status' => 'published']) }}">Published</a>
    <a class="{{ request('status') === 'hidden' ? 'active' : '' }}" href="{{ route('admin.reviews.index', ['status' => 'hidden']) }}">Hidden</a>
</div>

@if($reviews->isEmpty())
    <div class="card empty"><h3>No reviews</h3><p>There are no reviews to moderate.</p></div>
@else
    <div class="table-wrap">
        <table>
            <thead><tr><th>Product</th><th>Reviewer</th><th>Seller</th><th>Rating</th><th>Comment</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @foreach($reviews as $review)
                    <tr>
                        <td>{{ \Illuminate\Support\Str::limit($review->product->title ?? '—', 30) }}</td>
                        <td>{{ $review->reviewer->name ?? '—' }}</td>
                        <td>{{ $review->seller->name ?? '—' }}</td>
                        <td><span style="color:#f59e0b">{{ str_repeat('★', $review->rating) }}</span></td>
                        <td class="small">{{ \Illuminate\Support\Str::limit($review->comment, 50) }}</td>
                        <td><span class="pill {{ $review->status === 'published' ? 'pill-green' : 'pill-gray' }}">{{ ucfirst($review->status) }}</span></td>
                        <td>
                            <div class="row" style="gap:.35rem">
                                <form method="POST" action="{{ route('admin.reviews.toggle', $review) }}">
                                    @csrf
                                    <button class="btn btn-outline btn-sm" type="submit">{{ $review->status === 'published' ? 'Hide' : 'Publish' }}</button>
                                </form>
                                <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" data-confirm="Delete this review?">
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
    {{ $reviews->links('pagination.campusbazar') }}
@endif
@endsection
@push('scripts')
<script>
    document.querySelectorAll('form[data-confirm]').forEach(function (f) {
        f.addEventListener('submit', function (e) { if (!confirm(f.getAttribute('data-confirm'))) e.preventDefault(); });
    });
</script>
@endpush
