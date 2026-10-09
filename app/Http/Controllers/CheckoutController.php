<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Notifications\MarketplaceNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(Request $request, Product $product): View
    {
        $this->prepare($request, $product);

        $deliveryCharge = (float) config('campusbazar.delivery_charge');
        $total = (float) $product->price + $deliveryCharge;

        return view('checkout.create', compact('product', 'deliveryCharge', 'total'));
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $user = $this->prepare($request, $product);

        $data = $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]{6,20}$/'],
            'address' => ['required', 'string', 'max:500'],
            'district' => ['required', 'string', 'max:100'],
            'upazila' => ['nullable', 'string', 'max:100'],
            'delivery_instructions' => ['nullable', 'string', 'max:500'],
        ]);

        $deliveryCharge = (float) config('campusbazar.delivery_charge');

        $order = DB::transaction(function () use ($product, $user, $data, $deliveryCharge) {
            // Lock the product row to prevent overselling / duplicate sales.
            $locked = Product::whereKey($product->id)->lockForUpdate()->first();

            if (! $locked || $locked->status !== ProductStatus::ACTIVE) {
                throw ValidationException::withMessages([
                    'product' => 'Sorry, this item is no longer available.',
                ]);
            }

            if ($locked->user_id === $user->id) {
                throw ValidationException::withMessages([
                    'product' => 'You cannot purchase your own listing.',
                ]);
            }

            $unitPrice = (float) $locked->price;
            $subtotal = $unitPrice;
            $total = $subtotal + $deliveryCharge;

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'buyer_id' => $user->id,
                'seller_id' => $locked->user_id,
                'recipient_name' => $data['recipient_name'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'district' => $data['district'],
                'upazila' => $data['upazila'] ?? null,
                'delivery_instructions' => $data['delivery_instructions'] ?? null,
                'subtotal' => $subtotal,
                'delivery_charge' => $deliveryCharge,
                'total' => $total,
                'payment_method' => 'cod',
                'payment_status' => PaymentStatus::UNPAID,
                'status' => OrderStatus::PENDING_CONFIRMATION,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $locked->id,
                'seller_id' => $locked->user_id,
                'product_title' => $locked->title,
                'unit_price' => $unitPrice,
                'quantity' => 1,
                'line_total' => $subtotal,
            ]);

            $order->statusHistories()->create([
                'from_status' => null,
                'to_status' => OrderStatus::PENDING_CONFIRMATION,
                'actor_id' => $user->id,
                'reason' => 'Order placed with Cash on Delivery.',
            ]);

            $locked->update(['status' => ProductStatus::RESERVED]);

            return $order;
        });

        // Notify the seller.
        $seller = User::find($order->seller_id);
        if ($seller) {
            $seller->notify(new MarketplaceNotification(
                title: 'New order received',
                message: "You have a new COD order {$order->order_number} for \"{$product->title}\".",
                url: route('seller.orders.show', $order),
                level: 'success',
            ));
        }

        $user->notify(new MarketplaceNotification(
            title: 'Order placed successfully',
            message: "Your order {$order->order_number} has been placed. Pay {$order->total} BDT in cash on delivery.",
            url: route('orders.show', $order),
            level: 'success',
        ));

        return redirect()->route('orders.confirmation', $order)
            ->with('success', 'Your order has been placed. Payment method: Cash on Delivery.');
    }

    /**
     * Shared validation for checkout entry points.
     */
    private function prepare(Request $request, Product $product): User
    {
        $user = $request->user();

        if (! $product->isPurchasable()) {
            abort(404);
        }

        if ($product->user_id === $user->id) {
            abort(403, 'You cannot purchase your own listing.');
        }

        return $user;
    }
}
