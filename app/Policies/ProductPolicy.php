<?php

namespace App\Policies;

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Product $product): bool
    {
        if ($product->status === ProductStatus::ACTIVE) {
            return true;
        }

        if (! $user) {
            return false;
        }

        return $user->isAdmin() || $product->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isStudent() && $user->isVerified();
    }

    public function update(User $user, Product $product): bool
    {
        return $user->isAdmin() || $product->user_id === $user->id;
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->isAdmin() || $product->user_id === $user->id;
    }

    public function moderate(User $user): bool
    {
        return $user->isAdmin();
    }
}
