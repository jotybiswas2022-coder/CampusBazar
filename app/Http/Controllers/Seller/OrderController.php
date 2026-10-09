<?php

namespace App\Http\Controllers\Seller;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
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
        $query = $request->user()->sellerOrders()->with(['items', 'buyer'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $orders = $query->paginate(12)->withQueryString();

        return view('seller.orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order): View
    {
        $this->authorize('view', $order);

        abort_unless($order->seller_id === $request->user()->id || $request->user()->isAdmin(), 403);

        $order->load(['items', 'buyer', 'statusHistories.actor']);

        $allowed = OrderStatus::transitions($order->status);

        return view('seller.orders.show', compact('order', 'allowed'));
    }

    public function updateStatus(Request $request, Order $order, OrderWorkflow $workflow): RedirectResponse
    {
        $this->authorize('updateStatus', $order);

        $data = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', OrderStatus::all())],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $workflow->transition($order, $data['status'], $request->user(), $data['reason'] ?? null);

        $order->buyer?->notify(new MarketplaceNotification(
            title: 'Order status updated',
            message: "Order {$order->order_number} is now: " . OrderStatus::label($data['status']) . '.',
            url: route('orders.show', $order),
            level: 'info',
        ));

        return redirect()->route('seller.orders.show', $order)->with('success', 'Order status updated.');
    }
}
