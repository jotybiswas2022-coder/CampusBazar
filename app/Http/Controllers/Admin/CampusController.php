<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Campus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampusController extends Controller
{
    public function index(Request $request): View
    {
        $query = Campus::withCount('products')->orderBy('name');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->string('q') . '%');
        }

        $campuses = $query->paginate(20)->withQueryString();

        return view('admin.campuses.index', compact('campuses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:college,university,institute'],
            'district' => ['required', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $campus = Campus::create($data + ['is_active' => true]);

        AuditLog::record('campus.created', "Campus {$campus->name} created", [], $campus);

        return back()->with('success', 'Campus created.');
    }

    public function update(Request $request, Campus $campus): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:college,university,institute'],
            'district' => ['required', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $campus->update([
            'name' => $data['name'],
            'type' => $data['type'],
            'district' => $data['district'],
            'location' => $data['location'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Campus updated.');
    }

    public function destroy(Campus $campus): RedirectResponse
    {
        $campus->update(['is_active' => false]);
        $campus->delete();

        return back()->with('success', 'Campus deactivated.');
    }
}
