<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        $items = $request->user()->wishlist()
            ->with(['product.images', 'product.category', 'product.seller'])
            ->latest()
            ->paginate(12);

        return view('wishlist.index', compact('items'));
    }

    public function toggle(Request $request, Product $product): RedirectResponse
    {
        $user = $request->user();

        $existing = $user->wishlist()->where('product_id', $product->id)->first();

        if ($existing) {
            $existing->delete();

            return back()->with('success', 'Removed from your wishlist.');
        }

        $user->wishlist()->create(['product_id' => $product->id]);

        return back()->with('success', 'Added to your wishlist.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $request->user()->wishlist()->where('product_id', $product->id)->delete();

        return back()->with('success', 'Removed from your wishlist.');
    }
}
