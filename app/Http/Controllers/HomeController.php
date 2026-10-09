<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featured = Product::active()
            ->with(['images', 'category', 'seller', 'campus'])
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::active()->withCount(['products' => function ($query) {
            $query->where('status', 'active');
        }])->get();

        $campuses = Campus::active()->withCount(['products' => function ($query) {
            $query->where('status', 'active');
        }])->orderByDesc('products_count')->take(6)->get();

        $stats = [
            'products' => Product::active()->count(),
            'students' => User::whereNotNull('verified_at')->count(),
            'campuses' => Campus::active()->count(),
        ];

        return view('home', compact('featured', 'categories', 'campuses', 'stats'));
    }
}
