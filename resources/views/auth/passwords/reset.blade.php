@extends('layouts.app')
@section('title', 'Set New Password · CampusBazar')
@push('styles')
<style>.auth-wrap{max-width:440px;margin:1rem auto}.auth-card{padding:1.8rem}</style>
@endpush
@section('content')
<div class="auth-wrap">
    <div class="card auth-card">
        <div class="center mb-3">
            <span class="eyebrow">Account recovery</span>
            <h2>Set a new password</h2>
        </div>
        <form method="POST" action="{{ route('password.reset') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="field">
                <label for="email">Email address</label>
                <input class="input @error('email') input-error @enderror" id="email" type="email" name="email" value="{{ old('email', $email) }}" required autofocus>
                @error('email')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="password">New password</label>
                <input class="input @error('password') input-error @enderror" id="password" type="password" name="password" required autocomplete="new-password">
                @error('password')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="password_confirmation">Confirm new password</label>
                <input class="input" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>
            <button class="btn btn-primary w-full" type="submit">Reset password</button>
        </form>
    </div>
</div>
@endsection
