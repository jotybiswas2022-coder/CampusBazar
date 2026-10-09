<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString() ?: 'open';

        $reports = Report::with(['reporter', 'reportable'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'open' => Report::where('status', 'open')->count(),
            'reviewed' => Report::where('status', 'reviewed')->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
            'dismissed' => Report::where('status', 'dismissed')->count(),
        ];

        return view('admin.reports.index', compact('reports', 'status', 'counts'));
    }

    public function update(Request $request, Report $report): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:open,reviewed,resolved,dismissed'],
            'review_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $report->update([
            'status' => $data['status'],
            'review_notes' => $data['review_notes'] ?? null,
            'reviewed_by' => $request->user()->id,
        ]);

        AuditLog::record('report.reviewed', "Report #{$report->id} set to {$data['status']}", [], $report);

        return back()->with('success', 'Report updated.');
    }
}
