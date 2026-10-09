<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        return $user->isAdmin()
            || $order->buyer_id === $user->id
            || $order->seller_id === $user->id;
    }

    public function updateStatus(User $user, Order $order): bool
    {
        return $user->isAdmin() || $order->seller_id === $user->id;
    }

    public function cancel(User $user, Order $order): bool
    {
        return ($order->buyer_id === $user->id || $order->seller_id === $user->id || $user->isAdmin())
            && $order->canBeCancelled();
    }

    public function confirmPayment(User $user, Order $order): bool
    {
        return $user->isAdmin();
    }
}
