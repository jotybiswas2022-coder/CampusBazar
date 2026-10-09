@extends('layouts.app')
@section('title', 'Contact · CampusBazar')
@section('content')
<div class="grid grid-2" style="align-items:start">
    <div class="card card-pad">
        <span class="eyebrow">Get in touch</span>
        <h2>Contact CampusBazar</h2>
        <p class="muted small mt-1">Questions, feedback or partnership ideas? Send us a message and our team will get back to you.</p>

        <form method="POST" action="{{ route('contact.submit') }}" class="mt-3">
            @csrf
            <div class="field">
                <label for="name">Full name</label>
                <input class="input @error('name') input-error @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required>
                @error('name')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input class="input @error('email') input-error @enderror" id="email" type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                @error('email')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="phone">Phone (optional)</label>
                <input class="input" id="phone" name="phone" value="{{ old('phone') }}" placeholder="01XXXXXXXXX">
            </div>
            <div class="field">
                <label for="message">Message</label>
                <textarea class="input @error('message') input-error @enderror" id="message" name="message" required>{{ old('message') }}</textarea>
                @error('message')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <button class="btn btn-primary w-full" type="submit">Send message</button>
        </form>
    </div>

    <div class="stack">
        <div class="card card-pad">
            <h4>Support hours</h4>
            <p class="small muted mt-1">Saturday – Thursday, 10:00 AM – 6:00 PM (BST)</p>
        </div>
        <div class="card card-pad">
            <h4>Email</h4>
            <p class="small muted mt-1">support@campusbazar.test</p>
        </div>
        <div class="card card-pad">
            <h4>Reminder</h4>
            <p class="small muted mt-1">CampusBazar is Cash on Delivery only. We will never ask for advance online payment.</p>
        </div>
    </div>
</div>
@endsection
