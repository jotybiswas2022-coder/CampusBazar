<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Notifications\MarketplaceNotification;
use App\Services\OrderWorkflow;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = $request->user()->buyerOrders()
            ->with(['items', 'seller'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order): View
    {
        $this->authorize('view', $order);

        $order->load(['items', 'seller', 'buyer', 'statusHistories.actor', 'paymentConfirmer']);

        $canReview = $order->status === OrderStatus::DELIVERED
            && $order->buyer_id === $request->user()->id
            && ! $order->items()->whereHas('product.reviews', function ($q) use ($request) {
                $q->where('reviewer_id', $request->user()->id);
            })->exists();

        return view('orders.show', compact('order', 'canReview'));
    }

    public function confirmation(Request $request, Order $order): View
    {
        abort_unless($order->buyer_id === $request->user()->id, 403);

        $order->load('items', 'seller');

        return view('orders.confirmation', compact('order'));
    }

    public function cancel(Request $request, Order $order, OrderWorkflow $workflow): RedirectResponse
    {
        $this->authorize('cancel', $order);

        $data = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $workflow->transition($order, OrderStatus::CANCELLED, $request->user(), $data['reason'] ?? 'Cancelled by buyer.');

        $seller = $order->seller;
        if ($seller) {
            $seller->notify(new MarketplaceNotification(
                title: 'Order cancelled',
                message: "Order {$order->order_number} was cancelled by the buyer.",
                url: route('seller.orders.show', $order),
                level: 'warning',
            ));
        }

        return redirect()->route('orders.show', $order)->with('success', 'Order cancelled successfully.');
    }
}
