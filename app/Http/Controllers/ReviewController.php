<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Order $order): RedirectResponse
    {
        $this->authorize('view', $order);

        abort_unless($order->buyer_id === $request->user()->id, 403);
        abort_unless($order->status === OrderStatus::DELIVERED, 403);

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $item = $order->items()->first();
        abort_unless($item && $item->product_id, 422);

        $exists = Review::where('order_id', $order->id)
            ->where('reviewer_id', $request->user()->id)
            ->where('product_id', $item->product_id)
            ->exists();

        if ($exists) {
            return back()->with('warning', 'You have already reviewed this order.');
        }

        Review::create([
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'reviewer_id' => $request->user()->id,
            'seller_id' => $order->seller_id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'status' => 'published',
        ]);

        return back()->with('success', 'Thank you for your review.');
    }
}
