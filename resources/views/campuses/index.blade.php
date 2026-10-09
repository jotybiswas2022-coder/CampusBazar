@extends('layouts.app')
@section('title', 'Campus Directory · CampusBazar')
@section('content')
<div class="section-head">
    <div>
        <span class="eyebrow">Directory</span>
        <h2>Browse by campus</h2>
    </div>
</div>

<form class="card card-pad mb-3" method="GET" action="{{ route('campuses.index') }}">
    <div class="filters" style="grid-template-columns:2fr 1fr auto">
        <div class="field" style="margin-bottom:0">
            <label for="cq">Search campus</label>
            <input class="input" id="cq" type="search" name="q" value="{{ request('q') }}" placeholder="Campus name…">
        </div>
        <div class="field" style="margin-bottom:0">
            <label for="cd">District</label>
            <select class="input" id="cd" name="district">
                <option value="">All districts</option>
                @foreach($districts as $district)
                    <option value="{{ $district }}" @selected(request('district') === $district)>{{ $district }}</option>
                @endforeach
            </select>
        </div>
        <div class="row" style="gap:.5rem">
            <button class="btn btn-primary" type="submit">Filter</button>
            <a class="btn btn-outline" href="{{ route('campuses.index') }}">Reset</a>
        </div>
    </div>
</form>

@if($campuses->isEmpty())
    <div class="card empty"><h3>No campuses found</h3><p>Try a different search.</p></div>
@else
    <div class="grid grid-3">
        @foreach($campuses as $campus)
            <a class="card card-hover card-pad" href="{{ route('campuses.show', $campus) }}">
                <div class="row-between">
                    <b>{{ $campus->name }}</b>
                    <span class="pill pill-green">{{ $campus->products_count }}</span>
                </div>
                <div class="small muted mt-1">{{ ucfirst($campus->type) }} · {{ $campus->district }}</div>
                @if($campus->location)<div class="small muted">{{ $campus->location }}</div>@endif
            </a>
        @endforeach
    </div>
    {{ $campuses->links('pagination.campusbazar') }}
@endif
@endsection
