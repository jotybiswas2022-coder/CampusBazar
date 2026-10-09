<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VerificationStatus;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\VerificationRequest;
use App\Notifications\MarketplaceNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString() ?: VerificationStatus::PENDING;

        $requests = VerificationRequest::with('user')
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'pending' => VerificationRequest::where('status', VerificationStatus::PENDING)->count(),
            'approved' => VerificationRequest::where('status', VerificationStatus::APPROVED)->count(),
            'rejected' => VerificationRequest::where('status', VerificationStatus::REJECTED)->count(),
            'resubmission_required' => VerificationRequest::where('status', VerificationStatus::RESUBMISSION_REQUIRED)->count(),
        ];

        return view('admin.verifications.index', compact('requests', 'status', 'counts'));
    }

    public function show(VerificationRequest $verification): View
    {
        $verification->load(['user.campus', 'reviewer']);

        return view('admin.verifications.show', compact('verification'));
    }

    public function approve(Request $request, VerificationRequest $verification): RedirectResponse
    {
        $data = $request->validate([
            'review_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($verification, $data, $request) {
            $verification->update([
                'status' => VerificationStatus::APPROVED,
                'reviewer_id' => $request->user()->id,
                'review_notes' => $data['review_notes'] ?? null,
                'rejection_reason' => null,
                'resubmission_reason' => null,
                'reviewed_at' => now(),
            ]);

            $verification->user->update(['verified_at' => now()]);
        });

        AuditLog::record('verification.approved', "Verification #{$verification->id} approved", [], $verification);

        $verification->user->notify(new MarketplaceNotification(
            title: 'Student ID verified',
            message: 'Congratulations! Your student account has been verified. You can now buy and sell on CampusBazar.',
            url: route('dashboard'),
            level: 'success',
        ));

        return redirect()->route('admin.verifications.show', $verification)->with('success', 'Verification approved.');
    }

    public function reject(Request $request, VerificationRequest $verification): RedirectResponse
    {
        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
            'review_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($verification, $data, $request) {
            $verification->update([
                'status' => VerificationStatus::REJECTED,
                'reviewer_id' => $request->user()->id,
                'rejection_reason' => $data['rejection_reason'],
                'review_notes' => $data['review_notes'] ?? null,
                'reviewed_at' => now(),
            ]);

            $verification->user->update(['verified_at' => null]);
        });

        AuditLog::record('verification.rejected', "Verification #{$verification->id} rejected", [], $verification);

        $verification->user->notify(new MarketplaceNotification(
            title: 'Student ID verification rejected',
            message: 'Your verification request was rejected. Reason: ' . $data['rejection_reason'] . ' You may resubmit after correction.',
            url: route('verification.status'),
            level: 'danger',
        ));

        return redirect()->route('admin.verifications.show', $verification)->with('success', 'Verification rejected.');
    }

    public function requestResubmission(Request $request, VerificationRequest $verification): RedirectResponse
    {
        $data = $request->validate([
            'resubmission_reason' => ['required', 'string', 'max:1000'],
        ]);

        $verification->update([
            'status' => VerificationStatus::RESUBMISSION_REQUIRED,
            'reviewer_id' => $request->user()->id,
            'resubmission_reason' => $data['resubmission_reason'],
            'reviewed_at' => now(),
        ]);

        AuditLog::record('verification.resubmission_requested', "Resubmission requested for verification #{$verification->id}", [], $verification);

        $verification->user->notify(new MarketplaceNotification(
            title: 'Resubmission required',
            message: 'Please correct and resubmit your Student ID. ' . $data['resubmission_reason'],
            url: route('verification.create'),
            level: 'warning',
        ));

        return redirect()->route('admin.verifications.show', $verification)->with('success', 'Resubmission requested.');
    }
}
