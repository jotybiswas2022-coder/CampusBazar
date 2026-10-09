@extends('layouts.app')
@section('title', 'Account Settings · CampusBazar')
@section('content')
<div class="section-head"><div><span class="eyebrow">My account</span><h2>Account settings</h2></div></div>

<div class="grid grid-2" style="align-items:start">
    <form class="card card-pad" method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')
        <h3>Profile details</h3>
        <div class="mt-2">
            <div class="field">
                <label for="name">Full name</label>
                <input class="input @error('name') input-error @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label>Email</label>
                <input class="input" value="{{ $user->email }}" disabled>
                <span class="hint">Email cannot be changed.</span>
            </div>
            <div class="field">
                <label for="phone">Phone</label>
                <input class="input @error('phone') input-error @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                @error('phone')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="campus_id">Campus</label>
                <select class="input" id="campus_id" name="campus_id">
                    <option value="">Select your campus</option>
                    @foreach($campuses as $campus)
                        <option value="{{ $campus->id }}" @selected(old('campus_id', $user->campus_id) == $campus->id)>{{ $campus->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="bio">About you</label>
                <textarea class="input" id="bio" name="bio" maxlength="1000">{{ old('bio', $user->bio) }}</textarea>
            </div>
        </div>
        <button class="btn btn-primary" type="submit">Save changes</button>
    </form>

    <form class="card card-pad" method="POST" action="{{ route('profile.password') }}">
        @csrf
        @method('PUT')
        <h3>Change password</h3>
        <div class="mt-2">
            <div class="field">
                <label for="current_password">Current password</label>
                <input class="input @error('current_password') input-error @enderror" id="current_password" type="password" name="current_password" required>
                @error('current_password')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="password">New password</label>
                <input class="input @error('password') input-error @enderror" id="password" type="password" name="password" required>
                @error('password')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="password_confirmation">Confirm new password</label>
                <input class="input" id="password_confirmation" type="password" name="password_confirmation" required>
            </div>
        </div>
        <button class="btn btn-primary" type="submit">Update password</button>
    </form>
</div>
@endsection
