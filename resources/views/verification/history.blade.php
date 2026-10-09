@extends('layouts.app')
@section('title', 'Verification History · CampusBazar')
@section('content')
<div class="section-head">
    <div><span class="eyebrow">Student verification</span><h2>Submission history</h2></div>
    <a class="btn btn-outline btn-sm" href="{{ route('verification.status') }}">Back to status</a>
</div>

@if($requests->isEmpty())
    <div class="card empty"><h3>No submissions yet</h3><p>Your verification submissions will appear here.</p></div>
@else
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>#</th><th>Institution</th><th>Student ID</th><th>Status</th><th>Submitted</th><th>Reviewed</th></tr>
            </thead>
            <tbody>
                @foreach($requests as $request)
                    <tr>
                        <td>{{ $request->id }}</td>
                        <td>{{ $request->institution_name }}</td>
                        <td>{{ $request->student_id_number }}</td>
                        <td>
                            @php
                                $pill = match($request->status) {
                                    \App\Enums\VerificationStatus::APPROVED => 'pill-green',
                                    \App\Enums\VerificationStatus::PENDING => 'pill-amber',
                                    \App\Enums\VerificationStatus::REJECTED => 'pill-red',
                                    default => 'pill-gray',
                                };
                            @endphp
                            <span class="pill {{ $pill }}">{{ verification_status_label($request->status) }}</span>
                        </td>
                        <td>{{ $request->created_at->format('d M Y') }}</td>
                        <td>{{ $request->reviewed_at?->format('d M Y') ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $requests->links('pagination.campusbazar') }}
@endif
@endsection
