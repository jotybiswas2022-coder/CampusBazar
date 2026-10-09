<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(Request $request, Product $product): View
    {
        $this->authorize('view', $product);

        // Count a view once per session.
        $key = 'viewed_product_' . $product->id;
        if (! $request->session()->has($key)) {
            $product->increment('views_count');
            $request->session()->put($key, true);
        }

        $product->load(['images', 'category', 'seller.campus', 'campus', 'reviews.reviewer']);

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('images')
            ->take(4)
            ->get();

        $inWishlist = auth()->check()
            && auth()->user()->wishlist()->where('product_id', $product->id)->exists();

        return view('products.show', compact('product', 'related', 'inWishlist'));
    }
}
