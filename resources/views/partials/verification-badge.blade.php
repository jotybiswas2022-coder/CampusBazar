@php
    $status = $user->verificationStatus();
@endphp
@switch($status)
    @case(\App\Enums\VerificationStatus::APPROVED)
        <span class="pill pill-green">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>
            Verified Student
        </span>
        @break
    @case(\App\Enums\VerificationStatus::PENDING)
        <span class="pill pill-amber">Verification Pending</span>
        @break
    @case(\App\Enums\VerificationStatus::REJECTED)
        <span class="pill pill-red">Verification Rejected</span>
        @break
    @case(\App\Enums\VerificationStatus::RESUBMISSION_REQUIRED)
        <span class="pill pill-amber">Resubmission Required</span>
        @break
    @default
        <span class="pill pill-gray">Not Verified</span>
@endswitch
