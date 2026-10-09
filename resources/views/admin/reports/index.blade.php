@extends('layouts.admin')
@section('title', 'Reports')
@section('heading', 'Reports & Moderation')
@section('content')
<div class="tabs">
    <a class="{{ $status === 'open' ? 'active' : '' }}" href="{{ route('admin.reports.index', ['status' => 'open']) }}">Open ({{ $counts['open'] }})</a>
    <a class="{{ $status === 'reviewed' ? 'active' : '' }}" href="{{ route('admin.reports.index', ['status' => 'reviewed']) }}">Reviewed ({{ $counts['reviewed'] }})</a>
    <a class="{{ $status === 'resolved' ? 'active' : '' }}" href="{{ route('admin.reports.index', ['status' => 'resolved']) }}">Resolved ({{ $counts['resolved'] }})</a>
    <a class="{{ $status === 'dismissed' ? 'active' : '' }}" href="{{ route('admin.reports.index', ['status' => 'dismissed']) }}">Dismissed ({{ $counts['dismissed'] }})</a>
    <a class="{{ $status === 'all' ? 'active' : '' }}" href="{{ route('admin.reports.index', ['status' => 'all']) }}">All</a>
</div>

@if($reports->isEmpty())
    <div class="card empty"><h3>No reports</h3><p>Nothing to moderate here.</p></div>
@else
    <div class="stack">
        @foreach($reports as $report)
            <div class="card card-pad">
                <div class="row-between">
                    <div>
                        <div class="row" style="gap:.5rem">
                            <b>{{ ucfirst($report->reason) }}</b>
                            <span class="pill {{ $report->status === 'open' ? 'pill-amber' : 'pill-gray' }}">{{ ucfirst($report->status) }}</span>
                        </div>
                        <div class="small muted mt-1">
                            Reported by {{ $report->reporter->name ?? '—' }} ·
                            Target: {{ class_basename($report->reportable_type) }} #{{ $report->reportable_id }} ·
                            {{ $report->created_at->format('d M Y, g:i A') }}
                        </div>
                    </div>
                </div>
                @if($report->details)<p class="small mt-2">{{ $report->details }}</p>@endif
                <form class="row mt-2" style="gap:.5rem;flex-wrap:wrap" method="POST" action="{{ route('admin.reports.update', $report) }}">
                    @csrf
                    @method('PATCH')
                    <select class="input" name="status" style="max-width:200px">
                        @foreach(\App\Enums\ReportStatus::all() as $s)
                            <option value="{{ $s }}" @selected($report->status === $s)>{{ \App\Enums\ReportStatus::label($s) }}</option>
                        @endforeach
                    </select>
                    <input class="input flex-1" name="review_notes" value="{{ $report->review_notes }}" placeholder="Review notes (optional)" style="min-width:200px">
                    <button class="btn btn-primary" type="submit">Save</button>
                </form>
            </div>
        @endforeach
    </div>
    {{ $reports->links('pagination.campusbazar') }}
@endif
@endsection
