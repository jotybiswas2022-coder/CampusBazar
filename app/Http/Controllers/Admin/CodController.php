<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderWorkflow;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CodController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->string('filter')->toString() ?: 'awaiting';

        $query = Order::with(['buyer', 'seller'])->latest();

        if ($filter === 'awaiting') {
            $query->where('status', OrderStatus::DELIVERED)
                ->where('payment_status', '!=', PaymentStatus::PAID);
        } elseif ($filter === 'paid') {
            $query->where('payment_status', PaymentStatus::PAID);
        } elseif ($filter === 'unpaid') {
            $query->where('payment_status', PaymentStatus::UNPAID);
        }

        $orders = $query->paginate(20)->withQueryString();

        $totals = [
            'awaiting' => Order::where('status', OrderStatus::DELIVERED)->where('payment_status', '!=', PaymentStatus::PAID)->count(),
            'collected' => Order::where('payment_status', PaymentStatus::PAID)->sum('total'),
            'collected_count' => Order::where('payment_status', PaymentStatus::PAID)->count(),
            'unpaid' => Order::where('payment_status', PaymentStatus::UNPAID)->sum('total'),
        ];

        return view('admin.cod.index', compact('orders', 'filter', 'totals'));
    }

    public function markPaid(Request $request, Order $order, OrderWorkflow $workflow): RedirectResponse
    {
        $workflow->confirmCodPayment($order, $request->user());

        return back()->with('success', 'COD payment marked as collected.');
    }
}
