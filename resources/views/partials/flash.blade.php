@if(session('status'))
    <div class="alert alert-success mb-3">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
        <div>{{ session('status') }}</div>
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success mb-3">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
        <div>{{ session('success') }}</div>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-error mb-3">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/></svg>
        <div>{{ session('error') }}</div>
    </div>
@endif
@if(session('warning'))
    <div class="alert alert-warning mb-3">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/></svg>
        <div>{{ session('warning') }}</div>
    </div>
@endif
@if(session('info'))
    <div class="alert alert-info mb-3">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 16v-4M12 8h.01"/></svg>
        <div>{{ session('info') }}</div>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-error mb-3">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/></svg>
        <div>
            <strong>Please fix the following:</strong>
            <ul style="margin:.35rem 0 0 1.1rem">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
