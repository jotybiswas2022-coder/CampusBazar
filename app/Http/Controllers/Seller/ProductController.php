<?php

namespace App\Http\Controllers\Seller;

use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Notifications\MarketplaceNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->user()->products()->with(['images', 'category'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $products = $query->paginate(12)->withQueryString();

        return view('seller.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::active()->get();
        $campuses = Campus::active()->orderBy('name')->get();
        $conditions = ProductCondition::all();

        return view('seller.products.create', compact('categories', 'campuses', 'conditions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $product = DB::transaction(function () use ($request, $data) {
            $product = $request->user()->products()->create([
                'category_id' => $data['category_id'],
                'campus_id' => $data['campus_id'] ?? null,
                'title' => $data['title'],
                'slug' => Product::generateSlug($data['title']),
                'description' => $data['description'],
                'price' => $data['price'],
                'condition' => $data['condition'],
                'status' => ProductStatus::PENDING_APPROVAL,
            ]);

            $this->storeImages($request, $product);

            return $product;
        });

        // Notify admins of a new listing awaiting moderation.
        User::where('role', 'admin')->each(function (User $admin) use ($product) {
            $admin->notify(new MarketplaceNotification(
                title: 'New listing pending approval',
                message: "\"{$product->title}\" was submitted and is awaiting moderation.",
                url: route('admin.products.show', $product),
                level: 'info',
            ));
        });

        return redirect()->route('seller.products.index')
            ->with('success', 'Your listing has been submitted and is pending admin approval.');
    }

    public function edit(Request $request, Product $product): View
    {
        $this->authorize('update', $product);

        $categories = Category::active()->get();
        $campuses = Campus::active()->orderBy('name')->get();
        $conditions = ProductCondition::all();

        return view('seller.products.edit', compact('product', 'categories', 'campuses', 'conditions'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $data = $this->validated($request);

        DB::transaction(function () use ($request, $product, $data) {
            $status = $product->status;
            if (in_array($status, [ProductStatus::REJECTED, ProductStatus::REMOVED], true)) {
                $status = ProductStatus::PENDING_APPROVAL;
            }

            $product->update([
                'category_id' => $data['category_id'],
                'campus_id' => $data['campus_id'] ?? null,
                'title' => $data['title'],
                'description' => $data['description'],
                'price' => $data['price'],
                'condition' => $data['condition'],
                'status' => $status,
                'rejection_reason' => null,
            ]);

            if ($request->hasFile('images')) {
                $this->storeImages($request, $product);
            }
        });

        return redirect()->route('seller.products.index')->with('success', 'Listing updated successfully.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);

        $product->update(['status' => ProductStatus::REMOVED]);
        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Listing removed.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'campus_id' => ['nullable', 'exists:campuses,id'],
            'description' => ['required', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:1', 'max:1000000'],
            'condition' => ['required', 'in:' . implode(',', ProductCondition::all())],
            'images' => ['nullable', 'array', 'max:6'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
    }

    private function storeImages(Request $request, Product $product): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $order = (int) $product->images()->max('sort_order');

        foreach ($request->file('images') as $image) {
            $path = $image->store('products/' . $product->id, 'public');
            $product->images()->create([
                'path' => $path,
                'sort_order' => ++$order,
            ]);
        }
    }
}
