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

        <div class="field">
            <label for="front_image" class="uploader-label">
                Student ID — front side
                <span class="uploader-req">Required</span>
            </label>
            <div class="uploader" data-uploader data-max="1" data-multiple="0">
                <input id="front_image" class="uploader-input" type="file" name="front_image" accept="image/jpeg,image/png,image/webp" required>
                <label class="uploader-drop @error('front_image') input-error @enderror" for="front_image">
                    <span class="uploader-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M17 8l-5-5-5 5"/><path d="M12 3v12"/></svg>
                    </span>
                    <span class="uploader-title">Click to upload <em>or drag &amp; drop</em></span>
                    <span class="uploader-sub">JPG, PNG or WEBP · max 5 MB · at least 200×200 px</span>
                </label>
                <div class="uploader-grid"></div>
            </div>
            <span class="uploader-note" hidden></span>
            @error('front_image')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div class="field">
            <label for="back_image" class="uploader-label">
                Student ID — back side
                <span class="uploader-opt">Optional</span>
            </label>
            <div class="uploader" data-uploader data-max="1" data-multiple="0">
                <input id="back_image" class="uploader-input" type="file" name="back_image" accept="image/jpeg,image/png,image/webp">
                <label class="uploader-drop @error('back_image') input-error @enderror" for="back_image">
                    <span class="uploader-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M17 8l-5-5-5 5"/><path d="M12 3v12"/></svg>
                    </span>
                    <span class="uploader-title">Click to upload <em>or drag &amp; drop</em></span>
                    <span class="uploader-sub">If your ID has information on the back.</span>
                </label>
                <div class="uploader-grid"></div>
            </div>
            <span class="uploader-note" hidden></span>
            @error('back_image')<span class="field-error">{{ $message }}</span>@enderror
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

@push('styles')
<style>
    .uploader-label{display:flex;align-items:center;justify-content:space-between;gap:.5rem}
    .uploader-req,.uploader-opt{font-size:.68rem;font-weight:700;border-radius:999px;padding:.1rem .55rem;letter-spacing:.02em}
    .uploader-req{background:#fee2e2;color:var(--danger)}
    .uploader-opt{background:#eef2f0;color:var(--muted)}
    .uploader-input{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap;border:0}
    .uploader-drop{display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;gap:.3rem;padding:1.7rem 1rem;border:1.5px dashed #c7d9cf;border-radius:14px;background:linear-gradient(180deg,#fbfefc,#f2f9f5);cursor:pointer;transition:.2s}
    .uploader-drop:hover{border-color:var(--emerald);background:linear-gradient(180deg,#f4fdf8,#e8f7f0)}
    .uploader-drop.dragover{border-color:var(--emerald);background:#e8f7f0;box-shadow:0 0 0 4px rgba(16,185,129,.14);transform:translateY(-1px)}
    .uploader-icon{width:46px;height:46px;border-radius:50%;display:grid;place-items:center;background:linear-gradient(135deg,var(--primary),var(--emerald));color:#fff;box-shadow:0 8px 18px rgba(11,93,59,.28);margin-bottom:.3rem}
    .uploader-icon svg{width:22px;height:22px}
    .uploader-title{font-weight:600;font-size:.92rem}
    .uploader-title em{font-style:normal;color:var(--primary)}
    .uploader-sub{font-size:.76rem;color:var(--muted)}
    .uploader-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:.6rem;margin-top:.75rem}
    .uploader-grid:empty{display:none}
    .uploader-item{position:relative;aspect-ratio:16/10;border-radius:11px;overflow:hidden;border:1px solid var(--border);background:#eef6f1;box-shadow:var(--shadow-sm);animation:uploader-pop .18s ease}
    .uploader-item img{width:100%;height:100%;object-fit:cover}
    .uploader-remove{position:absolute;top:.35rem;right:.35rem;width:26px;height:26px;border-radius:50%;border:none;background:rgba(15,23,42,.68);color:#fff;display:grid;place-items:center;cursor:pointer;transition:.15s;backdrop-filter:blur(4px)}
    .uploader-remove:hover{background:var(--danger);transform:scale(1.08)}
    .uploader-remove svg{width:13px;height:13px}
    .uploader-note{font-size:.78rem;color:var(--warning);margin-top:.45rem;display:block}
    @keyframes uploader-pop{from{opacity:0;transform:scale(.92)}to{opacity:1;transform:scale(1)}}
</style>
@endpush

@push('scripts')
<script>
    (function () {
        var TYPES = ['image/jpeg', 'image/png', 'image/webp'];
        var MAX_SIZE = 5 * 1024 * 1024;

        function key(f) { return f.name + ':' + f.size + ':' + f.lastModified; }
        function isValid(f) { return TYPES.indexOf(f.type) !== -1 && f.size <= MAX_SIZE; }

        document.querySelectorAll('[data-uploader]').forEach(function (box) {
            var input = box.querySelector('input[type="file"]');
            if (!input) return;

            var drop = box.querySelector('.uploader-drop');
            var grid = box.querySelector('.uploader-grid');
            var note = box.parentElement.querySelector('.uploader-note');
            var max = parseInt(box.getAttribute('data-max') || '1', 10);
            var multiple = box.getAttribute('data-multiple') === '1';
            var dt = new DataTransfer();

            function files() { return Array.prototype.slice.call(dt.files); }
            function sync() { try { input.files = dt.files; } catch (e) {} }

            function warn(msg) {
                if (!note) return;
                note.textContent = msg;
                note.hidden = false;
                clearTimeout(warn._t);
                warn._t = setTimeout(function () { note.hidden = true; }, 4500);
            }

            function addFiles(list) {
                if (!list || !list.length) { sync(); render(); return; }

                if (!multiple) {
                    var f = list[0];
                    if (!isValid(f)) { warn('Please choose a JPG, PNG or WEBP image up to 5 MB.'); sync(); render(); return; }
                    dt = new DataTransfer();
                    dt.items.add(f);
                    sync();
                    render();
                    return;
                }

                var seen = files().map(key);
                var rejected = 0;
                Array.prototype.forEach.call(list, function (f) {
                    if (dt.files.length >= max) { rejected++; return; }
                    if (!isValid(f)) { rejected++; return; }
                    if (seen.indexOf(key(f)) !== -1) return;
                    seen.push(key(f));
                    dt.items.add(f);
                });
                sync();
                render();
                if (rejected > 0) warn(rejected + ' file(s) skipped — only JPG/PNG/WEBP up to 5 MB, max ' + max + '.');
            }

            function removeAt(index) {
                var list = files();
                list.splice(index, 1);
                dt = new DataTransfer();
                list.forEach(function (f) { dt.items.add(f); });
                sync();
                render();
            }

            function render() {
                grid.innerHTML = '';
                files().forEach(function (f, i) {
                    var item = document.createElement('div');
                    item.className = 'uploader-item';

                    var img = document.createElement('img');
                    img.src = URL.createObjectURL(f);
                    img.alt = f.name;
                    img.onload = function () { URL.revokeObjectURL(img.src); };
                    item.appendChild(img);

                    var btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'uploader-remove';
                    btn.setAttribute('aria-label', 'Remove image');
                    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>';
                    btn.addEventListener('click', function (e) { e.preventDefault(); e.stopPropagation(); removeAt(i); });
                    item.appendChild(btn);

                    grid.appendChild(item);
                });
            }

            input.addEventListener('change', function () { addFiles(Array.prototype.slice.call(input.files)); });

            ['dragenter', 'dragover'].forEach(function (ev) {
                box.addEventListener(ev, function (e) { e.preventDefault(); e.stopPropagation(); drop.classList.add('dragover'); });
            });
            ['dragleave', 'dragend', 'drop'].forEach(function (ev) {
                box.addEventListener(ev, function (e) { e.preventDefault(); e.stopPropagation(); drop.classList.remove('dragover'); });
            });
            box.addEventListener('drop', function (e) {
                if (e.dataTransfer && e.dataTransfer.files) addFiles(Array.prototype.slice.call(e.dataTransfer.files));
            });

            render();
        });
    })();
</script>
@endpush
