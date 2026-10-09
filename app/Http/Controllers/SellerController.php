<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class SellerController extends Controller
{
    public function show(User $user): View
    {
        $products = $user->products()
            ->active()
            ->with(['images', 'category'])
            ->latest()
            ->paginate(12);

        $rating = round((float) $user->reviewsReceived()->where('status', 'published')->avg('rating'), 1);
        $reviewCount = $user->reviewsReceived()->where('status', 'published')->count();

        return view('sellers.show', [
            'seller' => $user,
            'products' => $products,
            'rating' => $rating,
            'reviewCount' => $reviewCount,
        ]);
    }
}
