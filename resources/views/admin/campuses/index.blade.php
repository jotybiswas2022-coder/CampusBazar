@extends('layouts.admin')
@section('title', 'Campuses')
@section('heading', 'Campuses')
@section('content')
<div class="grid grid-2" style="align-items:start;grid-template-columns:1fr 1.6fr">
    <form class="card card-pad" method="POST" action="{{ route('admin.campuses.store') }}">
        @csrf
        <h3>Add campus</h3>
        <div class="field mt-2">
            <label for="name">Name</label>
            <input class="input" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="field">
            <label for="type">Type</label>
            <select class="input" id="type" name="type" required>
                <option value="college">College</option>
                <option value="university">University</option>
                <option value="institute">Institute</option>
            </select>
        </div>
        <div class="field">
            <label for="district">District</label>
            <input class="input" id="district" name="district" value="{{ old('district') }}" required>
        </div>
        <div class="field">
            <label for="location">Location (optional)</label>
            <input class="input" id="location" name="location" value="{{ old('location') }}">
        </div>
        <button class="btn btn-primary w-full" type="submit">Create campus</button>
    </form>

    <div>
        <form class="card card-pad mb-3" method="GET" action="{{ route('admin.campuses.index') }}">
            <div class="row" style="gap:.5rem">
                <input class="input flex-1" type="search" name="q" value="{{ request('q') }}" placeholder="Search campuses">
                <button class="btn btn-outline" type="submit">Search</button>
            </div>
        </form>

        @if($campuses->isEmpty())
            <div class="card empty"><h3>No campuses</h3></div>
        @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Campus</th><th>Type</th><th>District</th><th>Products</th><th>Active</th><th></th></tr></thead>
                    <tbody>
                        @foreach($campuses as $campus)
                            <tr>
                                <td>
                                    <form method="POST" action="{{ route('admin.campuses.update', $campus) }}" id="campus-{{ $campus->id }}">
                                        @csrf
                                        @method('PUT')
                                    </form>
                                    <input class="input" form="campus-{{ $campus->id }}" name="name" value="{{ $campus->name }}" style="min-width:180px">
                                    <input type="hidden" form="campus-{{ $campus->id }}" name="type" value="{{ $campus->type }}">
                                    <input type="hidden" form="campus-{{ $campus->id }}" name="district" value="{{ $campus->district }}">
                                </td>
                                <td>{{ ucfirst($campus->type) }}</td>
                                <td>{{ $campus->district }}</td>
                                <td>{{ $campus->products_count }}</td>
                                <td>
                                    <label class="checkbox" style="font-size:1rem">
                                        <input type="checkbox" form="campus-{{ $campus->id }}" name="is_active" value="1" @checked($campus->is_active)>
                                    </label>
                                </td>
                                <td>
                                    <div class="row" style="gap:.35rem">
                                        <button class="btn btn-outline btn-sm" form="campus-{{ $campus->id }}" type="submit">Save</button>
                                        <form method="POST" action="{{ route('admin.campuses.destroy', $campus) }}" data-confirm="Deactivate this campus?">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" type="submit">Deactivate</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $campuses->links('pagination.campusbazar') }}
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
