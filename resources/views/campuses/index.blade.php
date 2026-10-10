@extends('layouts.app')
@section('title', 'Campus Directory · CampusBazar')

@push('styles')
<style>
    /* ============================================================
       Filter row
       ============================================================ */
    .cd-filters{margin-top:1.2rem;padding:1.15rem 1.25rem}
    .cd-filters .filter-row{display:grid;grid-template-columns:2fr 1fr auto;gap:.9rem;align-items:end}
    .cd-filters .field{margin-bottom:0}
    .cd-actions{display:flex;gap:.55rem}

    /* ============================================================
       District quick filters
       ============================================================ */
    .cd-districts{display:flex;align-items:center;gap:.45rem;flex-wrap:wrap;margin-top:1.1rem}
    .cd-districts .lbl{font-size:.76rem;font-weight:600;color:var(--muted)}
    .dchip{
        display:inline-flex;align-items:center;gap:.35rem;padding:.4rem .85rem;border-radius:999px;
        border:1px solid var(--border);background:#fff;font-size:.8rem;font-weight:600;color:#334155;transition:.2s;
    }
    .dchip:hover{border-color:var(--emerald);color:var(--primary);transform:translateY(-1px)}
    .dchip.active{background:linear-gradient(135deg,var(--primary),var(--emerald));border-color:transparent;color:#fff;
        box-shadow:0 10px 22px -12px rgba(11,93,59,.8)}

    /* ============================================================
       Results
       ============================================================ */
    .cd-results{display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin:1.25rem 0 1rem}
    .cd-results .rc{font-size:.86rem;color:var(--muted)}
    .cd-results .rc b{color:var(--text);font-weight:700}

    /* ============================================================
       Campus tile
       ============================================================ */
    .campus-tile{
        position:relative;overflow:hidden;display:flex;flex-direction:column;gap:.75rem;padding:1.25rem;
        transition:transform .3s cubic-bezier(.22,1,.36,1),box-shadow .3s,border-color .3s;
    }
    .campus-tile .bar{position:absolute;top:0;bottom:0;left:0;width:4px;transform:scaleY(.35);transform-origin:top;
        background:linear-gradient(180deg,var(--emerald),var(--primary));transition:transform .45s cubic-bezier(.22,1,.36,1)}
    .campus-tile:hover{transform:translateY(-5px);border-color:#c7e6d6;box-shadow:0 24px 48px -26px rgba(11,93,59,.42)}
    .campus-tile:hover .bar{transform:scaleY(1)}
    .tile-top{display:flex;align-items:flex-start;gap:.75rem}
    .tile-mono{
        width:44px;height:44px;border-radius:13px;flex-shrink:0;display:grid;place-items:center;
        background:linear-gradient(135deg,var(--primary),var(--emerald));color:#fff;
        font-weight:800;font-size:.76rem;letter-spacing:.02em;transition:transform .35s;
    }
    .campus-tile:hover .tile-mono{transform:scale(1.06) rotate(-3deg)}
    .tile-id{min-width:0}
    .tile-id b{display:block;font-size:.96rem;line-height:1.3}
    .tile-meta{display:block;font-size:.75rem;color:var(--muted);margin-top:.2rem}
    .tile-loc{display:inline-flex;align-items:center;gap:.35rem;font-size:.78rem;color:#475569}
    .tile-loc svg{width:14px;height:14px;color:var(--emerald);flex-shrink:0}
    .tile-foot{display:flex;align-items:center;justify-content:space-between;gap:.6rem;margin-top:auto;
        padding-top:.75rem;border-top:1px dashed var(--border)}
    .tile-go{display:inline-flex;align-items:center;gap:.3rem;font-size:.78rem;font-weight:600;color:var(--emerald);
        opacity:0;transform:translateX(-5px);transition:.3s}
    .campus-tile:hover .tile-go{opacity:1;transform:none}
    .tile-go svg{width:14px;height:14px}

    @media (max-width:860px){
        .cd-filters .filter-row{grid-template-columns:1fr 1fr}
        .cd-actions{grid-column:1/-1}
    }

    /* ---------- Mobile: minimal ---------- */
    @media (max-width:760px){
        .cd-filters{margin-top:.9rem;padding:1rem}
        .cd-filters .filter-row{grid-template-columns:1fr;gap:.75rem}
        .cd-actions{grid-column:auto}
        .cd-actions .btn{flex:1 1 auto;justify-content:center}
        .cd-districts{margin-top:.9rem;gap:.4rem}
        .cd-districts .lbl{display:none}
        .dchip{padding:.34rem .7rem;font-size:.76rem}
        .dchip:hover{transform:none}
        .cd-results{margin:1rem 0 .8rem}
        .cd-results .rc{font-size:.8rem}
        .campus-tile{padding:1rem;gap:.6rem;border-radius:14px}
        .campus-tile:hover{transform:none;box-shadow:none;border-color:var(--border)}
        .campus-tile .bar{transform:scaleY(1)}
        .tile-mono{width:38px;height:38px;border-radius:11px;font-size:.68rem}
        .tile-id b{font-size:.9rem}
        .tile-go{display:none}
        .tile-foot{padding-top:.6rem}
    }
</style>
@endpush

@section('content')
@php
    /* "Bangladesh University of Engineering and Technology" -> BUET */
    $campusCode = function (string $name): string {
        $code = '';
        foreach (preg_split('/\s+/', trim($name)) as $word) {
            $word = preg_replace('/[^A-Za-z]/', '', $word);
            if ($word === '' || in_array(strtolower($word), ['of', 'and', 'the'], true)) {
                continue;
            }
            $code .= strtoupper($word[0]);
        }
        return substr($code, 0, 4) ?: strtoupper(substr($name, 0, 1));
    };

    /* Switching district keeps the current search term. */
    $districtUrl = function (?string $district) {
        $params = array_filter(['q' => request('q'), 'district' => $district]);
        return route('campuses.index') . ($params ? '?' . http_build_query($params) : '');
    };
@endphp

<section class="page-hero">
    <div class="page-hero-inner">
        <div>
            <nav class="page-crumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span class="sep">/</span>
                <span>Campuses</span>
            </nav>
            <h1>Campus directory</h1>
            <p class="page-hero-sub">Find your college or university and see what students around you are buying and selling.</p>
        </div>
        <div class="page-stats">
            <div class="s"><b>{{ $campuses->total() }}</b><span>campus{{ $campuses->total() === 1 ? '' : 'es' }}</span></div>
            <div class="s"><b>{{ $districts->count() }}</b><span>district{{ $districts->count() === 1 ? '' : 's' }}</span></div>
        </div>
    </div>
</section>

<form class="card cd-filters" method="GET" action="{{ route('campuses.index') }}" role="search">
    <div class="filter-row">
        <div class="field">
            <label for="cq">Search campus</label>
            <input class="input" id="cq" type="search" name="q" value="{{ request('q') }}" placeholder="Campus name…">
        </div>
        <div class="field">
            <label for="cd">District</label>
            <select class="input" id="cd" name="district">
                <option value="">All districts</option>
                @foreach($districts as $district)
                    <option value="{{ $district }}" @selected(request('district') === $district)>{{ $district }}</option>
                @endforeach
            </select>
        </div>
        <div class="cd-actions">
            <button class="btn btn-primary" type="submit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                Search
            </button>
            <a class="btn btn-outline" href="{{ route('campuses.index') }}">Reset</a>
        </div>
    </div>
</form>

@if($districts->isNotEmpty())
<div class="cd-districts">
    <span class="lbl">District:</span>
    <a class="dchip {{ request('district') ? '' : 'active' }}" href="{{ $districtUrl(null) }}">All</a>
    @foreach($districts as $district)
        <a class="dchip {{ request('district') === $district ? 'active' : '' }}" href="{{ $districtUrl($district) }}">{{ $district }}</a>
    @endforeach
</div>
@endif

@if($campuses->isEmpty())
    <div class="card empty reveal">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
        <h3>No campuses found</h3>
        <p>Try a different name, or pick another district.</p>
        <a class="btn btn-primary mt-2" href="{{ route('campuses.index') }}">Clear filters</a>
    </div>
@else
    <div class="cd-results">
        <p class="rc">Showing <b>{{ $campuses->firstItem() }}–{{ $campuses->lastItem() }}</b> of <b>{{ $campuses->total() }}</b> campus{{ $campuses->total() === 1 ? '' : 'es' }}</p>
    </div>

    <div class="grid grid-3">
        @foreach($campuses as $campus)
            <a class="card campus-tile reveal" style="--d:{{ min($loop->index, 8) * 45 }}ms" href="{{ route('campuses.show', $campus) }}">
                <span class="bar"></span>
                <div class="tile-top">
                    <span class="tile-mono">{{ $campusCode($campus->name) }}</span>
                    <div class="tile-id">
                        <b>{{ $campus->name }}</b>
                        <span class="tile-meta">{{ ucfirst($campus->type) }} · {{ $campus->district }}</span>
                    </div>
                </div>
                @if($campus->location)
                    <span class="tile-loc">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $campus->location }}
                    </span>
                @endif
                <div class="tile-foot">
                    @if($campus->products_count > 0)
                        <span class="pill pill-green">{{ $campus->products_count }} listing{{ $campus->products_count === 1 ? '' : 's' }}</span>
                    @else
                        <span class="pill pill-gray">No listings yet</span>
                    @endif
                    <span class="tile-go">
                        Browse
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </span>
                </div>
            </a>
        @endforeach
    </div>

    {{ $campuses->links('pagination.campusbazar') }}
@endif
@endsection
