@extends('layouts.app')
@section('title', 'ID Verification · CampusBazar')
@section('content')
<div class="section-head">
    <div>
        <span class="eyebrow">Student verification</span>
        <h2>ID Verification</h2>
    </div>
    <a class="btn btn-outline btn-sm" href="{{ route('verification.history') }}">Submission history</a>
</div>

<div class="grid grid-2" style="align-items:start">
    <div class="card card-pad">
        <h3>Your status</h3>
        <div class="mt-2">@include('partials.verification-badge', ['user' => $user])</div>

        @php $status = $user->verificationStatus(); @endphp
        @if($status === \App\Enums\VerificationStatus::APPROVED)
            <p class="mt-2" style="color:var(--primary)">Your Student ID is verified. You can buy and sell with Cash on Delivery.</p>
        @elseif($status === \App\Enums\VerificationStatus::PENDING)
            <p class="muted mt-2">Your Student ID is under review. We will notify you once it is processed. This usually takes up to 48 hours.</p>
        @elseif($status === \App\Enums\VerificationStatus::REJECTED)
            <div class="alert alert-error mt-2" style="margin-bottom:0">
                <div>
                    <strong>Your verification was rejected.</strong>
                    @if($latest?->rejection_reason)<div class="mt-1">{{ $latest->rejection_reason }}</div>@endif
                </div>
            </div>
        @elseif($status === \App\Enums\VerificationStatus::RESUBMISSION_REQUIRED)
            <div class="alert alert-warning mt-2" style="margin-bottom:0">
                <div>
                    <strong>Resubmission requested.</strong>
                    @if($latest?->resubmission_reason)<div class="mt-1">{{ $latest->resubmission_reason }}</div>@endif
                </div>
            </div>
        @else
            <p class="muted mt-2">You have not submitted a Student ID yet. Verify to unlock buying and selling.</p>
        @endif

        @if($user->canResubmitVerification())
            <a class="btn btn-primary w-full mt-3" href="{{ route('verification.create') }}">
                {{ in_array($status, [\App\Enums\VerificationStatus::REJECTED, \App\Enums\VerificationStatus::RESUBMISSION_REQUIRED], true) ? 'Resubmit Student ID' : 'Verify your Student ID' }}
            </a>
        @endif
    </div>

    <div class="stack">
        @if($latest)
        <div class="card card-pad">
            <h4>Latest submission</h4>
            <dl class="spec mt-2">
                <dt>Name</dt><dd>{{ $latest->full_name }}</dd>
                <dt>Institution</dt><dd>{{ $latest->institution_name }}</dd>
                <dt>Student ID</dt><dd>{{ $latest->student_id_number }}</dd>
                <dt>Status</dt><dd>{{ verification_status_label($latest->status) }}</dd>
                <dt>Submitted</dt><dd>{{ $latest->created_at->format('d M Y') }}</dd>
            </dl>
            @if($latest->front_image_path)
                <h4 class="mt-3">Documents</h4>
                <div class="grid grid-2 mt-1">
                    <img src="{{ route('verification.document', [$latest, 'front']) }}" alt="Student ID front" style="border-radius:10px;border:1px solid var(--border)">
                    @if($latest->back_image_path)
                        <img src="{{ route('verification.document', [$latest, 'back']) }}" alt="Student ID back" style="border-radius:10px;border:1px solid var(--border)">
                    @endif
                </div>
            @endif
        </div>
        @endif

        <div class="card card-pad">
            <h4>Why verification?</h4>
            <p class="small muted mt-1">CampusBazar is a student-only community. Verifying your Student ID keeps buyers and sellers genuine, and helps us protect everyone from fraud. Your documents are stored privately and never shown to other users.</p>
        </div>
    </div>
</div>
@endsection
