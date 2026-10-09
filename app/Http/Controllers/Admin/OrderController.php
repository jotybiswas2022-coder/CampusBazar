<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
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
        $query = Order::with(['buyer', 'seller', 'items'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->string('payment_status'));
        }

        if ($request->filled('q')) {
            $query->where('order_number', 'like', '%' . $request->string('q') . '%');
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['items', 'buyer', 'seller', 'statusHistories.actor', 'paymentConfirmer']);

        $allowed = OrderStatus::transitions($order->status);

        return view('admin.orders.show', compact('order', 'allowed'));
    }

    public function updateStatus(Request $request, Order $order, OrderWorkflow $workflow): RedirectResponse
    {
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

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order status updated.');
    }
}
