<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Inquiry::with(['product', 'seller'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $inquiries = $query->paginate(20)->withQueryString();

        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function update(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,read,replied,closed'],
        ]);

        $inquiry->update(['status' => $data['status']]);

        return back()->with('success', 'Inquiry status updated.');
    }
}
