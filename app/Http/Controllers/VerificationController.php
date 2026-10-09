<?php

namespace App\Http\Controllers;

use App\Enums\VerificationStatus;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\VerificationRequest;
use App\Notifications\MarketplaceNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VerificationController extends Controller
{
    public function status(): View
    {
        $user = auth()->user();
        $latest = $user->latestVerificationRequest;

        return view('verification.status', compact('user', 'latest'));
    }

    public function create(): View|RedirectResponse
    {
        $user = auth()->user();

        if (! $user->canResubmitVerification()) {
            return redirect()->route('verification.status')
                ->with('info', 'You already have an active verification request under review.');
        }

        return view('verification.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->canResubmitVerification()) {
            return redirect()->route('verification.status')
                ->with('warning', 'You already have an active verification request under review.');
        }

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'institution_name' => ['required', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'student_id_number' => ['required', 'string', 'max:255'],
            'academic_year' => ['nullable', 'string', 'max:100'],
            'front_image' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'back_image' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'confirm' => ['accepted'],
        ], [
            'confirm.accepted' => 'You must confirm that the submitted information is accurate.',
        ]);

        $request->validate([
            'front_image' => ['dimensions:min_width=200,min_height=200'],
        ]);

        $verification = DB::transaction(function () use ($request, $data, $user) {
            $front = $request->file('front_image')->store("verification/{$user->id}", 'local');
            $back = $request->hasFile('back_image')
                ? $request->file('back_image')->store("verification/{$user->id}", 'local')
                : null;

            return VerificationRequest::create([
                'user_id' => $user->id,
                'full_name' => $data['full_name'],
                'institution_name' => $data['institution_name'],
                'department' => $data['department'] ?? null,
                'student_id_number' => $data['student_id_number'],
                'academic_year' => $data['academic_year'] ?? null,
                'front_image_path' => $front,
                'back_image_path' => $back,
                'status' => VerificationStatus::PENDING,
            ]);
        });

        AuditLog::record('verification.submitted', "Verification request #{$verification->id} submitted", [], $verification);

        // Notify all administrators (in-app only).
        User::where('role', 'admin')->each(function (User $admin) use ($verification, $user) {
            $admin->notify(new MarketplaceNotification(
                title: 'New Student ID verification request',
                message: "{$user->name} submitted a Student ID verification request.",
                url: route('admin.verifications.show', $verification),
                level: 'info',
            ));
        });

        return redirect()->route('verification.status')
            ->with('success', 'Your Student ID has been submitted and is pending review. We will notify you once it is processed.');
    }

    public function history(): View
    {
        $requests = auth()->user()->verificationRequests()->latest()->paginate(10);

        return view('verification.history', compact('requests'));
    }

    /**
     * Securely stream a private verification document.
     */
    public function document(VerificationRequest $verificationRequest, string $side): StreamedResponse
    {
        $user = auth()->user();
        abort_unless($user->isAdmin() || $verificationRequest->user_id === $user->id, 403);

        abort_unless(in_array($side, ['front', 'back'], true), 404);

        $path = $side === 'front' ? $verificationRequest->front_image_path : $verificationRequest->back_image_path;

        abort_if(! $path || ! Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->response($path, null, [
            'Content-Type' => Storage::disk('local')->mimeType($path) ?: 'image/jpeg',
            'Cache-Control' => 'private, no-store',
        ]);
    }
}
