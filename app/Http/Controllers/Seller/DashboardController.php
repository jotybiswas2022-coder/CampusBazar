<?php

namespace App\Http\Controllers\Seller;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $stats = [
            'active_listings' => $user->products()->where('status', ProductStatus::ACTIVE)->count(),
            'pending_listings' => $user->products()->where('status', ProductStatus::PENDING_APPROVAL)->count(),
            'sold_listings' => $user->products()->where('status', ProductStatus::SOLD)->count(),
            'total_orders' => $user->sellerOrders()->count(),
            'orders_to_fulfil' => $user->sellerOrders()->whereIn('status', [
                OrderStatus::PENDING_CONFIRMATION, OrderStatus::CONFIRMED, OrderStatus::PROCESSING,
            ])->count(),
            'cod_collected' => $user->sellerOrders()->where('payment_status', PaymentStatus::PAID)->sum('total'),
            'total_reviews' => $user->reviewsReceived()->count(),
        ];

        $recentOrders = $user->sellerOrders()->with('buyer', 'items')->latest()->take(5)->get();
        $recentListings = $user->products()->with('images')->latest()->take(5)->get();

        return view('seller.dashboard', compact('user', 'stats', 'recentOrders', 'recentListings'));
    }
}
