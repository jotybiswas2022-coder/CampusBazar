<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Enums\VerificationStatus;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Order;
use App\Models\Product;
use App\Models\Report;
use App\Models\User;
use App\Models\VerificationRequest;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users' => User::where('role', 'student')->count(),
            'verified_students' => User::whereNotNull('verified_at')->count(),
            'unverified_students' => User::whereNull('verified_at')->where('role', 'student')->count(),
            'pending_verifications' => VerificationRequest::where('status', VerificationStatus::PENDING)->count(),
            'active_listings' => Product::where('status', ProductStatus::ACTIVE)->count(),
            'pending_products' => Product::where('status', ProductStatus::PENDING_APPROVAL)->count(),
            'total_orders' => Order::count(),
            'pending_cod_orders' => Order::where('payment_status', PaymentStatus::UNPAID)
                ->whereNotIn('status', [OrderStatus::CANCELLED, OrderStatus::RETURNED])->count(),
            'delivered_awaiting_cod' => Order::where('status', OrderStatus::DELIVERED)
                ->where('payment_status', PaymentStatus::UNPAID)->count(),
            'confirmed_cod' => Order::where('payment_status', PaymentStatus::PAID)->count(),
            'cancelled_orders' => Order::where('status', OrderStatus::CANCELLED)->count(),
            'returned_orders' => Order::where('status', OrderStatus::RETURNED)->count(),
            'open_reports' => Report::where('status', 'open')->count(),
        ];

        $recentVerifications = VerificationRequest::with('user')->latest()->take(5)->get();
        $recentOrders = Order::with(['buyer', 'seller'])->latest()->take(6)->get();
        $recentActivity = AuditLog::with('user')->latest()->take(8)->get();

        return view('admin.dashboard', compact('stats', 'recentVerifications', 'recentOrders', 'recentActivity'));
    }
}
