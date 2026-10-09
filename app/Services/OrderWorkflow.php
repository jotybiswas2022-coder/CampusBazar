<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Models\AuditLog;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Notifications\MarketplaceNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderWorkflow
{
    /**
     * Transition an order to a new status enforcing the allowed state machine.
     *
     * @throws ValidationException
     */
    public function transition(Order $order, string $to, ?User $actor, ?string $reason = null): Order
    {
        $from = $order->status;

        if ($from === $to) {
            return $order;
        }

        if (! OrderStatus::canTransition($from, $to)) {
            throw ValidationException::withMessages([
                'status' => 'Cannot change status from ' . OrderStatus::label($from) . ' to ' . OrderStatus::label($to) . '.',
            ]);
        }

        DB::transaction(function () use ($order, $actor, $to, $from, $reason) {
            $attributes = ['status' => $to];

            if ($to === OrderStatus::CANCELLED) {
                $attributes['cancellation_reason'] = $reason ?: 'Cancelled.';
            }

            $order->update($attributes);

            $order->statusHistories()->create([
                'from_status' => $from,
                'to_status' => $to,
                'actor_id' => $actor?->id,
                'reason' => $reason,
            ]);

            match ($to) {
                OrderStatus::DELIVERED => $this->markSold($order),
                OrderStatus::CANCELLED, OrderStatus::RETURNED => $this->restoreProducts($order),
                default => null,
            };
        });

        return $order->refresh();
    }

    /**
     * Mark COD cash as collected. Only allowed after delivery.
     *
     * @throws ValidationException
     */
    public function confirmCodPayment(Order $order, User $actor): Order
    {
        if ($order->status !== OrderStatus::DELIVERED) {
            throw ValidationException::withMessages([
                'payment' => 'COD payment can only be confirmed after the order is delivered.',
            ]);
        }

        if ($order->payment_status === PaymentStatus::PAID) {
            throw ValidationException::withMessages([
                'payment' => 'This order is already marked as paid.',
            ]);
        }

        $order->update([
            'payment_status' => PaymentStatus::PAID,
            'payment_collected_at' => now(),
            'payment_confirmed_by' => $actor->id,
        ]);

        AuditLog::record('order.cod_collected', "COD payment collected for order {$order->order_number}", [
            'amount' => (float) $order->total,
        ], $order);

        $order->buyer?->notify(new MarketplaceNotification(
            title: 'COD payment confirmed',
            message: "Payment of {$order->total} BDT for order {$order->order_number} has been confirmed.",
            url: route('orders.show', $order),
            level: 'success',
        ));

        return $order;
    }

    private function markSold(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->product_id) {
                Product::whereKey($item->product_id)->update(['status' => ProductStatus::SOLD]);
            }
        }
    }

    private function restoreProducts(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->product_id) {
                Product::whereKey($item->product_id)
                    ->whereIn('status', [ProductStatus::RESERVED, ProductStatus::SOLD])
                    ->update(['status' => ProductStatus::ACTIVE]);
            }
        }
    }
}
