<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        $campuses = Campus::active()->orderBy('name')->get();

        return view('auth.register', compact('campuses'));
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]{6,20}$/'],
            'campus_id' => ['nullable', 'exists:campuses,id'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'terms' => ['accepted'],
        ]);

        $user = DB::transaction(function () use ($data) {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'campus_id' => $data['campus_id'] ?? null,
                'password' => $data['password'],
                'role' => UserRole::STUDENT,
            ]);
        });

        Auth::login($user);

        return redirect()->route('verification.status')
            ->with('success', 'Welcome to CampusBazar! Please verify your Student ID to start buying and selling.');
    }
}
