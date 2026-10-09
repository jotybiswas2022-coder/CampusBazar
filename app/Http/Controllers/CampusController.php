<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampusController extends Controller
{
    public function index(Request $request): View
    {
        $query = Campus::active()->withCount('products')->orderBy('name');

        if ($request->filled('district')) {
            $query->where('district', $request->string('district'));
        }

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->string('q') . '%');
        }

        $campuses = $query->paginate(15)->withQueryString();

        $districts = Campus::active()
            ->select('district')
            ->distinct()
            ->orderBy('district')
            ->pluck('district');

        return view('campuses.index', compact('campuses', 'districts'));
    }

    public function show(Campus $campus): View
    {
        abort_unless($campus->is_active, 404);

        $products = $campus->products()
            ->active()
            ->with(['images', 'category', 'seller'])
            ->latest()
            ->paginate(12);

        return view('campuses.show', compact('campus', 'products'));
    }
}
