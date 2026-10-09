<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Review::with(['product', 'reviewer', 'seller'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $reviews = $query->paginate(20)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggle(Review $review): RedirectResponse
    {
        $newStatus = $review->status === 'published' ? 'hidden' : 'published';
        $review->update(['status' => $newStatus]);

        AuditLog::record('review.moderated', "Review #{$review->id} set to {$newStatus}", [], $review);

        return back()->with('success', 'Review status updated.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}
