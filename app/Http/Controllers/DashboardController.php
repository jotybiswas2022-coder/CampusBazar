<?php

namespace App\Http\Controllers;

use App\Enums\VerificationStatus;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $data = [
            'user' => $user,
            'verification' => $user->latestVerificationRequest,
            'verificationStatus' => $user->verificationStatus(),
            'listingsCount' => $user->products()->count(),
            'activeListings' => $user->products()->where('status', 'active')->count(),
            'buyOrdersCount' => $user->buyerOrders()->count(),
            'sellOrdersCount' => $user->sellerOrders()->count(),
            'wishlistCount' => $user->wishlist()->count(),
            'unreadMessages' => 0,
        ];

        $data['recentOrders'] = $user->buyerOrders()->with('items')->latest()->take(5)->get();
        $data['recentListings'] = $user->products()->with('images')->latest()->take(5)->get();

        return view('dashboard.index', $data);
    }
}
