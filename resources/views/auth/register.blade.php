@extends('layouts.app')
@section('title', 'Register · CampusBazar')
@push('styles')
<style>.auth-wrap{max-width:520px;margin:1rem auto}.auth-card{padding:1.8rem}</style>
@endpush
@section('content')
<div class="auth-wrap">
    <div class="card auth-card">
        <div class="center mb-3">
            <span class="eyebrow">Join CampusBazar</span>
            <h2>Create your student account</h2>
            <p class="small muted mt-1">You will verify your Student ID after registering to start buying and selling.</p>
        </div>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="field">
                <label for="name">Full name</label>
                <input class="input @error('name') input-error @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                @error('name')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="email">Email address</label>
                <input class="input @error('email') input-error @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required>
                @error('email')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="phone">Phone number</label>
                <input class="input @error('phone') input-error @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="01XXXXXXXXX" required>
                @error('phone')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="campus_id">Campus (optional)</label>
                <select class="input" id="campus_id" name="campus_id">
                    <option value="">Select your campus</option>
                    @foreach($campuses as $campus)
                        <option value="{{ $campus->id }}" @selected(old('campus_id') == $campus->id)>{{ $campus->name }} — {{ $campus->district }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-2" style="gap:0 1rem">
                <div class="field">
                    <label for="password">Password</label>
                    <input class="input @error('password') input-error @enderror" id="password" type="password" name="password" required autocomplete="new-password">
                    @error('password')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="password_confirmation">Confirm password</label>
                    <input class="input" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                </div>
            </div>
            <label class="checkbox mb-2">
                <input type="checkbox" name="terms" @checked(old('terms'))>
                <span>I agree to the <a href="{{ route('terms') }}" style="color:var(--primary)">Terms</a> and <a href="{{ route('privacy') }}" style="color:var(--primary)">Privacy Policy</a>. I confirm I am a student.</span>
            </label>
            @error('terms')<span class="field-error">{{ $message }}</span>@enderror
            <button class="btn btn-primary w-full" type="submit">Create account</button>
        </form>
        <p class="center small muted mt-3">Already registered? <a href="{{ route('login') }}" style="color:var(--primary);font-weight:600">Log in</a></p>
    </div>
</div>
@endsection
