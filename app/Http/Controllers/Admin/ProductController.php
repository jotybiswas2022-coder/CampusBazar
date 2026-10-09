<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Product;
use App\Notifications\MarketplaceNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString() ?: ProductStatus::PENDING_APPROVAL;

        $products = Product::with(['seller', 'category', 'images'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'pending_approval' => Product::where('status', ProductStatus::PENDING_APPROVAL)->count(),
            'active' => Product::where('status', ProductStatus::ACTIVE)->count(),
            'reserved' => Product::where('status', ProductStatus::RESERVED)->count(),
            'sold' => Product::where('status', ProductStatus::SOLD)->count(),
            'rejected' => Product::where('status', ProductStatus::REJECTED)->count(),
            'removed' => Product::where('status', ProductStatus::REMOVED)->count(),
        ];

        return view('admin.products.index', compact('products', 'status', 'counts'));
    }

    public function show(Product $product): View
    {
        $product->load(['seller', 'category', 'campus', 'images']);

        return view('admin.products.show', compact('product'));
    }

    public function approve(Request $request, Product $product): RedirectResponse
    {
        $product->update([
            'status' => ProductStatus::ACTIVE,
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        AuditLog::record('product.approved', "Product {$product->title} approved", [], $product);

        $product->seller->notify(new MarketplaceNotification(
            title: 'Listing approved',
            message: "Your listing \"{$product->title}\" is now live.",
            url: route('products.show', $product),
            level: 'success',
        ));

        return back()->with('success', 'Product approved and published.');
    }

    public function reject(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $product->update([
            'status' => ProductStatus::REJECTED,
            'rejection_reason' => $data['rejection_reason'],
        ]);

        AuditLog::record('product.rejected', "Product {$product->title} rejected", [], $product);

        $product->seller->notify(new MarketplaceNotification(
            title: 'Listing rejected',
            message: "Your listing \"{$product->title}\" was rejected. Reason: {$data['rejection_reason']}",
            url: route('seller.products.index'),
            level: 'danger',
        ));

        return back()->with('success', 'Product rejected.');
    }

    public function remove(Product $product): RedirectResponse
    {
        $product->update(['status' => ProductStatus::REMOVED]);

        AuditLog::record('product.removed', "Product {$product->title} removed", [], $product);

        return back()->with('success', 'Product removed from the marketplace.');
    }
}
