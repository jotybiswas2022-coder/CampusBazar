<?php

namespace App\Http\Controllers;

use App\Enums\ProductCondition;
use App\Models\Campus;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function index(Request $request): View
    {
        return $this->listing($request);
    }

    public function category(Request $request, Category $category): View
    {
        return $this->listing($request, $category);
    }

    public function search(Request $request): View
    {
        return $this->listing($request);
    }

    private function listing(Request $request, ?Category $category = null): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string'],
            'campus' => ['nullable', 'integer'],
            'condition' => ['nullable', 'string'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            'sort' => ['nullable', 'in:newest,oldest,price_asc,price_desc'],
        ]);

        $query = Product::active()->with(['images', 'category', 'seller', 'campus']);

        if ($category) {
            $query->where('category_id', $category->id);
        } elseif (! empty($filters['category'])) {
            $query->whereHas('category', fn (Builder $q) => $q->where('slug', $filters['category']));
        }

        $query->search($filters['q'] ?? null);

        if (! empty($filters['campus'])) {
            $query->where('campus_id', $filters['campus']);
        }

        if (! empty($filters['condition']) && in_array($filters['condition'], ProductCondition::all(), true)) {
            $query->where('condition', $filters['condition']);
        }

        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        $query = match ($filters['sort'] ?? 'newest') {
            'oldest' => $query->oldest(),
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::active()->get();
        $campuses = Campus::active()->orderBy('name')->get();

        return view('marketplace.index', compact('products', 'categories', 'campuses', 'category', 'filters'));
    }
}
