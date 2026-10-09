@extends('layouts.app')
@section('title', 'Log in · CampusBazar')
@push('styles')
<style>.auth-wrap{max-width:440px;margin:1rem auto}.auth-card{padding:1.8rem}</style>
@endpush
@section('content')
<div class="auth-wrap">
    <div class="card auth-card">
        <div class="center mb-3">
            <span class="eyebrow">Welcome back</span>
            <h2>Log in to CampusBazar</h2>
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="field">
                <label for="email">Email address</label>
                <input class="input @error('email') input-error @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input class="input @error('password') input-error @enderror" id="password" type="password" name="password" required autocomplete="current-password">
                @error('password')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="row-between mb-2">
                <label class="checkbox"><input type="checkbox" name="remember"> Remember me</label>
                <a class="small" href="{{ route('password.request') }}" style="color:var(--primary)">Forgot password?</a>
            </div>
            <button class="btn btn-primary w-full" type="submit">Log in</button>
        </form>
        <p class="center small muted mt-3">New to CampusBazar? <a href="{{ route('register') }}" style="color:var(--primary);font-weight:600">Create an account</a></p>
    </div>
</div>
@endsection
