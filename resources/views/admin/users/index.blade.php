@extends('layouts.admin')
@section('title', 'Users')
@section('heading', 'Users')
@section('content')
<form class="card card-pad mb-3" method="GET" action="{{ route('admin.users.index') }}">
    <div class="grid grid-3" style="gap:1rem;align-items:end">
        <div class="field" style="margin-bottom:0">
            <label>Search</label>
            <input class="input" type="search" name="q" value="{{ request('q') }}" placeholder="Name, email or phone">
        </div>
        <div class="field" style="margin-bottom:0">
            <label>Verification</label>
            <select class="input" name="status">
                <option value="">All users</option>
                <option value="verified" @selected(request('status') === 'verified')>Verified</option>
                <option value="unverified" @selected(request('status') === 'unverified')>Unverified students</option>
            </select>
        </div>
        <div class="row" style="gap:.5rem">
            <button class="btn btn-primary" type="submit">Filter</button>
            <a class="btn btn-outline" href="{{ route('admin.users.index') }}">Reset</a>
        </div>
    </div>
</form>

@if($users->isEmpty())
    <div class="card empty"><h3>No users</h3><p>No users match your filters.</p></div>
@else
    <div class="table-wrap">
        <table>
            <thead><tr><th>User</th><th>Phone</th><th>Campus</th><th>Role</th><th>Verified</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td><b>{{ $user->name }}</b><div class="small muted">{{ $user->email }}</div></td>
                        <td>{{ $user->phone ?? '—' }}</td>
                        <td>{{ $user->campus->name ?? '—' }}</td>
                        <td><span class="pill pill-gray">{{ ucfirst($user->role) }}</span></td>
                        <td><span class="pill {{ $user->isVerified() ? 'pill-green' : 'pill-amber' }}">{{ $user->isVerified() ? 'Verified' : 'No' }}</span></td>
                        <td><span class="pill {{ $user->status === 'active' ? 'pill-green' : 'pill-red' }}">{{ ucfirst($user->status) }}</span></td>
                        <td><a class="btn btn-outline btn-sm" href="{{ route('admin.users.show', $user) }}">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $users->links('pagination.campusbazar') }}
@endif
@endsection
