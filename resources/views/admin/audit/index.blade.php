@extends('layouts.admin')
@section('title', 'Audit Logs')
@section('heading', 'Audit Logs')
@section('content')
<form class="card card-pad mb-3" method="GET" action="{{ route('admin.audit.index') }}">
    <div class="grid grid-3" style="gap:1rem;align-items:end">
        <div class="field" style="margin-bottom:0">
            <label>Action</label>
            <input class="input" type="search" name="action" value="{{ request('action') }}" placeholder="e.g. product.approved">
        </div>
        <div class="field" style="margin-bottom:0">
            <label>Description contains</label>
            <input class="input" type="search" name="q" value="{{ request('q') }}">
        </div>
        <div class="row" style="gap:.5rem">
            <button class="btn btn-primary" type="submit">Filter</button>
            <a class="btn btn-outline" href="{{ route('admin.audit.index') }}">Reset</a>
        </div>
    </div>
</form>

@if($logs->isEmpty())
    <div class="card empty"><h3>No log entries</h3></div>
@else
    <div class="table-wrap">
        <table>
            <thead><tr><th>When</th><th>Action</th><th>Description</th><th>User</th><th>IP</th></tr></thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td class="small muted">{{ $log->created_at->format('d M Y, g:i A') }}</td>
                        <td><span class="pill pill-gray">{{ $log->action }}</span></td>
                        <td>{{ $log->description }}</td>
                        <td>{{ $log->user->name ?? 'System' }}</td>
                        <td class="small muted">{{ $log->ip_address ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $logs->links('pagination.campusbazar') }}
@endif
@endsection
