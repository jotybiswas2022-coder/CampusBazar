@extends('layouts.app')
@section('title', 'About · CampusBazar')
@push('styles')
<style>.prose{max-width:820px}.prose h2{margin:1.5rem 0 .5rem}.prose p{margin-bottom:1rem;color:#334155}.prose ul{margin:0 0 1rem 1.2rem;color:#334155}.prose li{margin-bottom:.35rem}</style>
@endpush
@section('content')
<div class="card card-pad prose">
    <span class="eyebrow">About us</span>
    <h1>CampusBazar</h1>
    <p>CampusBazar is a student-only marketplace built for colleges and universities in Bangladesh. We help students buy and sell used textbooks, calculators, electronics, hostel essentials and more — safely, within their own campus community.</p>

    <h2>Our mission</h2>
    <p>Students own value that too often goes unused. CampusBazar turns last semester's books and gadgets into cash for the seller and savings for the buyer, while keeping every transaction trusted through verified Student IDs.</p>

    <h2>Why students trust us</h2>
    <ul>
        <li><strong>Verified students only.</strong> Every buyer and seller submits a Student ID that our team reviews before they can trade.</li>
        <li><strong>Cash on Delivery.</strong> Pay only when your item arrives — no online payment, no upfront risk.</li>
        <li><strong>Local by design.</strong> Discover listings from your own campus and nearby campuses.</li>
        <li><strong>Fair, transparent pricing.</strong> All prices are shown in Bangladeshi Taka (৳).</li>
    </ul>

    <h2>How selling works</h2>
    <p>Verify your Student ID, post a listing with photos and a price, and coordinate delivery with your buyer. You confirm each order and get paid in cash on delivery.</p>

    <a class="btn btn-primary mt-2" href="{{ route('marketplace.index') }}">Explore the marketplace</a>
</div>
@endsection
