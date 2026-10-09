@extends('layouts.admin')
@section('title', 'Review Verification')
@section('heading', 'Review Verification')
@section('content')
<nav class="small muted mb-2"><a href="{{ route('admin.verifications.index') }}">Verifications</a> · #{{ $verification->id }}</nav>

<div class="grid grid-2" style="align-items:start">
    <div class="stack">
        <div class="card card-pad">
            <h3>Applicant details</h3>
            <dl class="spec" style="display:grid;grid-template-columns:auto 1fr;gap:.4rem 1rem;font-size:.86rem;margin-top:.8rem">
                <dt class="muted">Name</dt><dd style="font-weight:600">{{ $verification->full_name }}</dd>
                <dt class="muted">Account</dt><dd style="font-weight:600">{{ $verification->user->name ?? '—' }} ({{ $verification->user->email ?? '' }})</dd>
                <dt class="muted">Institution</dt><dd style="font-weight:600">{{ $verification->institution_name }}</dd>
                <dt class="muted">Department</dt><dd style="font-weight:600">{{ $verification->department ?? '—' }}</dd>
                <dt class="muted">Student ID</dt><dd style="font-weight:600">{{ $verification->student_id_number }}</dd>
                <dt class="muted">Academic year</dt><dd style="font-weight:600">{{ $verification->academic_year ?? '—' }}</dd>
                <dt class="muted">Campus</dt><dd style="font-weight:600">{{ $verification->user->campus->name ?? '—' }}</dd>
                <dt class="muted">Submitted</dt><dd style="font-weight:600">{{ $verification->created_at->format('d M Y, g:i A') }}</dd>
            </dl>
            <div class="badge-stack mt-3">
                <span class="pill {{ $verification->status === 'pending' ? 'pill-amber' : 'pill-gray' }}">{{ verification_status_label($verification->status) }}</span>
            </div>
            @if($verification->review_notes)<p class="small muted mt-2">Notes: {{ $verification->review_notes }}</p>@endif
        </div>

        <div class="card card-pad">
            <h3>ID documents</h3>
            <div class="grid grid-2 mt-2">
                <div>
                    <div class="small muted mb-1">Front side</div>
                    <img src="{{ route('verification.document', [$verification, 'front']) }}" alt="ID front" style="border-radius:10px;border:1px solid var(--border)">
                </div>
                @if($verification->back_image_path)
                    <div>
                        <div class="small muted mb-1">Back side</div>
                        <img src="{{ route('verification.document', [$verification, 'back']) }}" alt="ID back" style="border-radius:10px;border:1px solid var(--border)">
                    </div>
                @endif
            </div>
        </div>
    </div>

    <aside class="stack">
        <form class="card card-pad" method="POST" action="{{ route('admin.verifications.approve', $verification) }}">
            @csrf
            <h4>Approve</h4>
            <div class="field mt-2">
                <label for="review_notes">Review notes (optional)</label>
                <textarea class="input" id="review_notes" name="review_notes" style="min-height:70px"></textarea>
            </div>
            <button class="btn btn-primary w-full" type="submit">Approve & verify student</button>
        </form>

        <form class="card card-pad" method="POST" action="{{ route('admin.verifications.reject', $verification) }}">
            @csrf
            <h4>Reject</h4>
            <div class="field mt-2">
                <label for="rejection_reason">Rejection reason</label>
                <textarea class="input" id="rejection_reason" name="rejection_reason" required style="min-height:70px"></textarea>
            </div>
            <button class="btn btn-danger w-full" type="submit">Reject request</button>
        </form>

        <form class="card card-pad" method="POST" action="{{ route('admin.verifications.resubmission', $verification) }}">
            @csrf
            <h4>Request resubmission</h4>
            <div class="field mt-2">
                <label for="resubmission_reason">What should be corrected?</label>
                <textarea class="input" id="resubmission_reason" name="resubmission_reason" required style="min-height:70px"></textarea>
            </div>
            <button class="btn btn-outline w-full" type="submit">Request resubmission</button>
        </form>
    </aside>
</div>
@endsection
