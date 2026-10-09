@extends('layouts.app')
@section('title', 'Privacy Policy · CampusBazar')
@push('styles')
<style>.prose{max-width:820px}.prose h2{margin:1.5rem 0 .5rem}.prose p{margin-bottom:1rem;color:#334155}.prose li{margin-bottom:.35rem}</style>
@endpush
@section('content')
<div class="card card-pad prose">
    <span class="eyebrow">Legal</span>
    <h1>Privacy Policy</h1>
    <p>Your privacy matters. This policy explains what we collect and how we use it.</p>

    <h2>Information we collect</h2>
    <ul>
        <li>Account details: name, email, phone number and campus.</li>
        <li>Student ID documents submitted for verification.</li>
        <li>Marketplace activity: listings, orders, messages and reviews.</li>
    </ul>

    <h2>Student ID documents</h2>
    <p>Verification documents are stored privately and are only accessible to authorised administrators for the purpose of verifying your student status. They are never shown publicly and never shared with other users.</p>

    <h2>How we use your information</h2>
    <ul>
        <li>To verify eligibility and keep the marketplace trustworthy.</li>
        <li>To operate listings, orders, messaging and notifications.</li>
        <li>To respond to support requests and improve the service.</li>
    </ul>

    <h2>Sharing</h2>
    <p>We do not sell your personal data. Limited information (such as your display name, campus and public listings) is visible to other users. Delivery details are shared between the buyer and seller to fulfil orders.</p>

    <h2>Retention</h2>
    <p>Verification documents are retained for a limited period as required for record-keeping, then deleted or anonymised.</p>

    <h2>Your choices</h2>
    <p>You can update your account details at any time from your settings. To request deletion of your account data, contact our support team.</p>
</div>
@endsection
