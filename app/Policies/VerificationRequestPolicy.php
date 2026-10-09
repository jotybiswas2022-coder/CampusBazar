<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VerificationRequest;

class VerificationRequestPolicy
{
    public function view(User $user, VerificationRequest $request): bool
    {
        return $user->isAdmin() || $request->user_id === $user->id;
    }

    public function review(User $user): bool
    {
        return $user->isAdmin();
    }
}
