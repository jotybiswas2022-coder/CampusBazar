<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with('campus')->latest();

        if ($request->filled('q')) {
            $term = $request->string('q');
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->string('status') === 'verified') {
                $query->whereNotNull('verified_at');
            } elseif ($request->string('status') === 'unverified') {
                $query->whereNull('verified_at')->where('role', 'student');
            }
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->load(['campus', 'verificationRequests.reviewer', 'products']);

        return view('admin.users.show', compact('user'));
    }

    public function updateStatus(Request $request, User $user): RedirectResponse
    {
        abort_if($user->isAdmin(), 403, 'Administrator accounts cannot be modified here.');

        $data = $request->validate([
            'status' => ['required', 'in:active,suspended'],
        ]);

        $user->update(['status' => $data['status']]);

        AuditLog::record('user.status_changed', "User {$user->email} set to {$data['status']}", ['user_id' => $user->id], $user);

        return back()->with('success', 'User status updated.');
    }
}
