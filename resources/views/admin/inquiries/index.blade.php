@extends('layouts.admin')
@section('title', 'Inquiries')
@section('heading', 'Contact Inquiries')
@section('content')
<div class="tabs">
    <a class="{{ !request('status') ? 'active' : '' }}" href="{{ route('admin.inquiries.index') }}">All</a>
    @foreach(['new', 'read', 'replied', 'closed'] as $s)
        <a class="{{ request('status') === $s ? 'active' : '' }}" href="{{ route('admin.inquiries.index', ['status' => $s]) }}">{{ ucfirst($s) }}</a>
    @endforeach
</div>

@if($inquiries->isEmpty())
    <div class="card empty"><h3>No inquiries</h3><p>Contact form submissions will appear here.</p></div>
@else
    <div class="stack">
        @foreach($inquiries as $inquiry)
            <div class="card card-pad">
                <div class="row-between">
                    <div>
                        <b>{{ $inquiry->name }}</b>
                        <span class="pill pill-gray">{{ ucfirst($inquiry->status) }}</span>
                        <div class="small muted mt-1">{{ $inquiry->email }}@if($inquiry->phone) · {{ $inquiry->phone }}@endif · {{ $inquiry->created_at->format('d M Y, g:i A') }}</div>
                    </div>
                    <form class="row" method="POST" action="{{ route('admin.inquiries.update', $inquiry) }}" style="gap:.4rem">
                        @csrf
                        @method('PATCH')
                        <select class="input" name="status" style="width:130px">
                            @foreach(['new', 'read', 'replied', 'closed'] as $s)
                                <option value="{{ $s }}" @selected($inquiry->status === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary btn-sm" type="submit">Update</button>
                    </form>
                </div>
                <p class="small mt-2" style="white-space:pre-line">{{ $inquiry->message }}</p>
            </div>
        @endforeach
    </div>
    {{ $inquiries->links('pagination.campusbazar') }}
@endif
@endsection
