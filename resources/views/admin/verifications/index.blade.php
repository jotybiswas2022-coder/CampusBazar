@extends('layouts.admin')
@section('title', 'ID Verifications')
@section('heading', 'Student ID Verifications')
@section('content')
<div class="tabs">
    <a class="{{ $status === 'pending' ? 'active' : '' }}" href="{{ route('admin.verifications.index', ['status' => 'pending']) }}">Pending ({{ $counts['pending'] }})</a>
    <a class="{{ $status === 'approved' ? 'active' : '' }}" href="{{ route('admin.verifications.index', ['status' => 'approved']) }}">Approved ({{ $counts['approved'] }})</a>
    <a class="{{ $status === 'rejected' ? 'active' : '' }}" href="{{ route('admin.verifications.index', ['status' => 'rejected']) }}">Rejected ({{ $counts['rejected'] }})</a>
    <a class="{{ $status === 'resubmission_required' ? 'active' : '' }}" href="{{ route('admin.verifications.index', ['status' => 'resubmission_required']) }}">Resubmission ({{ $counts['resubmission_required'] }})</a>
    <a class="{{ $status === 'all' ? 'active' : '' }}" href="{{ route('admin.verifications.index', ['status' => 'all']) }}">All</a>
</div>

@if($requests->isEmpty())
    <div class="card empty"><h3>No requests</h3><p>No verification requests in this category.</p></div>
@else
    <div class="table-wrap">
        <table>
            <thead><tr><th>Applicant</th><th>Institution</th><th>Student ID</th><th>Status</th><th>Submitted</th><th></th></tr></thead>
            <tbody>
                @foreach($requests as $request)
                    <tr>
                        <td><b>{{ $request->user->name ?? '—' }}</b><div class="small muted">{{ $request->user->email ?? '' }}</div></td>
                        <td>{{ $request->institution_name }}</td>
                        <td>{{ $request->student_id_number }}</td>
                        <td>
                            @php $pill = match($request->status) {
                                \App\Enums\VerificationStatus::APPROVED => 'pill-green',
                                \App\Enums\VerificationStatus::PENDING => 'pill-amber',
                                \App\Enums\VerificationStatus::REJECTED => 'pill-red',
                                default => 'pill-gray',
                            }; @endphp
                            <span class="pill {{ $pill }}">{{ verification_status_label($request->status) }}</span>
                        </td>
                        <td>{{ $request->created_at->format('d M Y') }}</td>
                        <td><a class="btn btn-primary btn-sm" href="{{ route('admin.verifications.show', $request) }}">Review</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $requests->links('pagination.campusbazar') }}
@endif
@endsection
