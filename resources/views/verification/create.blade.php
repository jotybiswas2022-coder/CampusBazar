@extends('layouts.app')
@section('title', 'Submit Student ID · CampusBazar')
@section('content')
<div style="max-width:680px;margin:0 auto">
    <div class="section-head">
        <div><span class="eyebrow">Student verification</span><h2>Submit your Student ID</h2></div>
        <a class="btn btn-outline btn-sm" href="{{ route('verification.status') }}">Back</a>
    </div>

    <form class="card card-pad" method="POST" action="{{ route('verification.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="alert alert-info mb-3">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 16v-4M12 8h.01"/></svg>
            <div>Upload a clear photo of your college or university ID. Documents are stored securely and only visible to admins.</div>
        </div>

        <div class="grid grid-2" style="gap:0 1rem">
            <div class="field">
                <label for="full_name">Full name (as on ID)</label>
                <input class="input @error('full_name') input-error @enderror" id="full_name" name="full_name" value="{{ old('full_name', auth()->user()->name) }}" required>
                @error('full_name')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="student_id_number">Student ID number</label>
                <input class="input @error('student_id_number') input-error @enderror" id="student_id_number" name="student_id_number" value="{{ old('student_id_number') }}" required>
                @error('student_id_number')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="field">
            <label for="institution_name">Institution name</label>
            <input class="input @error('institution_name') input-error @enderror" id="institution_name" name="institution_name" value="{{ old('institution_name', auth()->user()->campus->name ?? '') }}" required>
            @error('institution_name')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="grid grid-2" style="gap:0 1rem">
            <div class="field">
                <label for="department">Department (optional)</label>
                <input class="input" id="department" name="department" value="{{ old('department') }}">
            </div>
            <div class="field">
                <label for="academic_year">Academic year (optional)</label>
                <input class="input" id="academic_year" name="academic_year" value="{{ old('academic_year') }}" placeholder="e.g. 2024-2025">
            </div>
        </div>
        <div class="grid grid-2" style="gap:0 1rem">
            <div class="field">
                <label for="front_image">Student ID — front side</label>
                <input class="input @error('front_image') input-error @enderror" id="front_image" type="file" name="front_image" accept="image/*" required>
                <span class="hint">JPG, PNG or WEBP. Max 5 MB, at least 200×200 px.</span>
                @error('front_image')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="back_image">Student ID — back side (optional)</label>
                <input class="input" id="back_image" type="file" name="back_image" accept="image/*">
                <span class="hint">If your ID has information on the back.</span>
                @error('back_image')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <label class="checkbox mb-2">
            <input type="checkbox" name="confirm" @checked(old('confirm'))>
            <span>I confirm that the information and documents I am submitting are accurate and belong to me.</span>
        </label>
        @error('confirm')<span class="field-error">{{ $message }}</span>@enderror

        <button class="btn btn-primary w-full" type="submit">Submit for review</button>
    </form>
</div>
@endsection
