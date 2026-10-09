@extends('layouts.app')
@section('title', 'Reset Password · CampusBazar')
@push('styles')
<style>.auth-wrap{max-width:440px;margin:1rem auto}.auth-card{padding:1.8rem}</style>
@endpush
@section('content')
<div class="auth-wrap">
    <div class="card auth-card">
        <div class="center mb-3">
            <span class="eyebrow">Account recovery</span>
            <h2>Forgot your password?</h2>
            <p class="small muted mt-1">Enter your email and we will send you a reset link.</p>
        </div>
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="field">
                <label for="email">Email address</label>
                <input class="input @error('email') input-error @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <button class="btn btn-primary w-full" type="submit">Email reset link</button>
        </form>
        <p class="center small muted mt-3"><a href="{{ route('login') }}" style="color:var(--primary);font-weight:600">Back to login</a></p>
    </div>
</div>
@endsection
