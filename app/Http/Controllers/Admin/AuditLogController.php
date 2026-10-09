<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AuditLog::with('user')->latest();

        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->string('action') . '%');
        }

        if ($request->filled('q')) {
            $query->where('description', 'like', '%' . $request->string('q') . '%');
        }

        $logs = $query->paginate(30)->withQueryString();

        return view('admin.audit.index', compact('logs'));
    }
}
