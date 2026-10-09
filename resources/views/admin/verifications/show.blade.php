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
            <p class="small muted">Click an image to view it in full size.</p>
            <div class="grid grid-2 mt-2">
                <div>
                    <div class="small muted mb-1">Front side</div>
                    <img class="doc-thumb" src="{{ route('verification.document', [$verification, 'front']) }}" alt="ID front" data-label="Front side" tabindex="0" role="button">
                </div>
                @if($verification->back_image_path)
                    <div>
                        <div class="small muted mb-1">Back side</div>
                        <img class="doc-thumb" src="{{ route('verification.document', [$verification, 'back']) }}" alt="ID back" data-label="Back side" tabindex="0" role="button">
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

<div class="doc-modal" id="docModal" aria-hidden="true">
    <button type="button" class="doc-modal-close" id="docModalClose" aria-label="Close">&times;</button>
    <figure class="doc-modal-body">
        <img id="docModalImg" src="" alt="">
        <figcaption id="docModalCaption"></figcaption>
    </figure>
</div>

<style>
    .doc-thumb {
        display: block;
        width: 100%;
        max-height: 240px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid var(--border);
        cursor: zoom-in;
        background: #fff;
        transition: opacity .15s ease;
    }
    .doc-thumb:hover { opacity: .9; }
    .doc-thumb:focus-visible { outline: 2px solid var(--primary); outline-offset: 2px; }

    .doc-modal {
        position: fixed;
        inset: 0;
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(6, 20, 14, .82);
        padding: 2rem;
    }
    .doc-modal.is-open { display: flex; }
    .doc-modal-body {
        margin: 0;
        max-width: min(90vw, 1000px);
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .75rem;
    }
    .doc-modal-body img {
        max-width: 100%;
        max-height: 82vh;
        border-radius: 12px;
        box-shadow: 0 24px 60px rgba(0, 0, 0, .5);
        background: #fff;
    }
    .doc-modal-body figcaption {
        color: #e6f2ec;
        font-size: .85rem;
        letter-spacing: .02em;
    }
    .doc-modal-close {
        position: absolute;
        top: 1rem;
        right: 1.25rem;
        width: 44px;
        height: 44px;
        border: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, .15);
        color: #fff;
        font-size: 1.6rem;
        line-height: 1;
        cursor: pointer;
    }
    .doc-modal-close:hover { background: rgba(255, 255, 255, .3); }
</style>

<script>
    (function () {
        var modal = document.getElementById('docModal');
        var modalImg = document.getElementById('docModalImg');
        var caption = document.getElementById('docModalCaption');
        var closeBtn = document.getElementById('docModalClose');

        function openModal(img) {
            modalImg.src = img.getAttribute('src');
            modalImg.alt = img.getAttribute('alt') || '';
            caption.textContent = img.getAttribute('data-label') || '';
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            closeBtn.focus();
        }

        function closeModal() {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            modalImg.src = '';
        }

        document.querySelectorAll('.doc-thumb').forEach(function (img) {
            img.addEventListener('click', function () { openModal(img); });
            img.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    openModal(img);
                }
            });
        });

        closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', function (e) {
            if (e.target === modal) { closeModal(); }
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('is-open')) { closeModal(); }
        });
    })();
</script>
@endsection
