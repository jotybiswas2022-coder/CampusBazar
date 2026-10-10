@extends('layouts.app')
@section('title', 'New Listing · CampusBazar')
@section('content')
<div style="max-width:760px;margin:0 auto">
    <div class="section-head">
        <div><span class="eyebrow">Selling</span><h2>Post a new listing</h2></div>
        <a class="btn btn-outline btn-sm" href="{{ route('seller.products.index') }}">Back</a>
    </div>

    @if(! auth()->user()->isVerified())
        <div class="alert alert-warning mb-3">You can create a listing, but it will only go live after your Student ID is verified.</div>
    @endif

    <form class="card card-pad" method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="field">
            <label for="title">Listing title</label>
            <input class="input @error('title') input-error @enderror" id="title" name="title" value="{{ old('title') }}" required placeholder="e.g. Casio fx-991EX Scientific Calculator">
            @error('title')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="grid grid-2" style="gap:0 1rem">
            <div class="field">
                <label for="category_id">Category</label>
                <select class="input @error('category_id') input-error @enderror" id="category_id" name="category_id" required>
                    <option value="">Select category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="campus_id">Campus (optional)</label>
                <select class="input" id="campus_id" name="campus_id">
                    <option value="">Select campus</option>
                    @foreach($campuses as $campus)
                        <option value="{{ $campus->id }}" @selected(old('campus_id', auth()->user()->campus_id) == $campus->id)>{{ $campus->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-2" style="gap:0 1rem">
            <div class="field">
                <label for="price">Price (৳)</label>
                <input class="input @error('price') input-error @enderror" id="price" type="number" min="1" step="1" name="price" value="{{ old('price') }}" required>
                @error('price')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="condition">Condition</label>
                <select class="input @error('condition') input-error @enderror" id="condition" name="condition" required>
                    @foreach($conditions as $condition)
                        <option value="{{ $condition }}" @selected(old('condition') === $condition)>{{ condition_label($condition) }}</option>
                    @endforeach
                </select>
                @error('condition')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="field">
            <label for="description">Description</label>
            <textarea class="input @error('description') input-error @enderror" id="description" name="description" required placeholder="Describe the item, its condition, age and any defects.">{{ old('description') }}</textarea>
            @error('description')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field">
            <label for="images" class="uploader-label">Photos <span class="uploader-count" id="uploaderCount">0 / 6</span></label>
            <div class="uploader" id="imageUploader">
                <input id="images" class="uploader-input" type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple>
                <label class="uploader-drop @error('images') input-error @enderror" id="uploaderDrop" for="images">
                    <span class="uploader-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M17 8l-5-5-5 5"/><path d="M12 3v12"/></svg>
                    </span>
                    <span class="uploader-title">Click to upload <em>or drag &amp; drop</em></span>
                    <span class="uploader-sub">JPG, PNG or WEBP · up to 6 photos · max 5 MB each</span>
                </label>
                <div class="uploader-grid" id="uploaderGrid"></div>
            </div>
            <span class="uploader-note" id="uploaderNote" hidden></span>
            @error('images')<span class="field-error">{{ $message }}</span>@enderror
            @error('images.*')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <button class="btn btn-primary w-full" type="submit">Submit for approval</button>
    </form>
</div>
@endsection

@push('styles')
<style>
    .uploader-label{display:flex;align-items:center;justify-content:space-between;gap:.5rem}
    .uploader-count{font-size:.72rem;font-weight:600;color:var(--primary);background:var(--emerald-soft);border-radius:999px;padding:.12rem .55rem}
    .uploader-input{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap;border:0}
    .uploader-drop{display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;gap:.3rem;padding:1.7rem 1rem;border:1.5px dashed #c7d9cf;border-radius:14px;background:linear-gradient(180deg,#fbfefc,#f2f9f5);cursor:pointer;transition:.2s}
    .uploader-drop:hover{border-color:var(--emerald);background:linear-gradient(180deg,#f4fdf8,#e8f7f0)}
    .uploader-drop.dragover{border-color:var(--emerald);background:#e8f7f0;box-shadow:0 0 0 4px rgba(16,185,129,.14);transform:translateY(-1px)}
    .uploader-icon{width:46px;height:46px;border-radius:50%;display:grid;place-items:center;background:linear-gradient(135deg,var(--primary),var(--emerald));color:#fff;box-shadow:0 8px 18px rgba(11,93,59,.28);margin-bottom:.3rem}
    .uploader-icon svg{width:22px;height:22px}
    .uploader-title{font-weight:600;font-size:.92rem}
    .uploader-title em{font-style:normal;color:var(--primary)}
    .uploader-sub{font-size:.76rem;color:var(--muted)}
    .uploader-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(94px,1fr));gap:.6rem;margin-top:.75rem}
    .uploader-grid:empty{display:none}
    .uploader-item{position:relative;aspect-ratio:1;border-radius:11px;overflow:hidden;border:1px solid var(--border);background:#eef6f1;box-shadow:var(--shadow-sm);animation:uploader-pop .18s ease}
    .uploader-item img{width:100%;height:100%;object-fit:cover}
    .uploader-cover{position:absolute;left:.35rem;bottom:.35rem;background:rgba(11,93,59,.9);color:#fff;font-size:.62rem;font-weight:700;padding:.1rem .4rem;border-radius:999px;letter-spacing:.02em}
    .uploader-remove{position:absolute;top:.3rem;right:.3rem;width:24px;height:24px;border-radius:50%;border:none;background:rgba(15,23,42,.68);color:#fff;display:grid;place-items:center;cursor:pointer;transition:.15s;backdrop-filter:blur(4px)}
    .uploader-remove:hover{background:var(--danger);transform:scale(1.08)}
    .uploader-remove svg{width:13px;height:13px}
    .uploader-note{font-size:.78rem;color:var(--warning);margin-top:.45rem}
    @keyframes uploader-pop{from{opacity:0;transform:scale(.9)}to{opacity:1;transform:scale(1)}}
</style>
@endpush

@push('scripts')
<script>
(function () {
    var input = document.getElementById('images');
    if (!input) return;

    var drop = document.getElementById('uploaderDrop');
    var uploader = document.getElementById('imageUploader');
    var grid = document.getElementById('uploaderGrid');
    var count = document.getElementById('uploaderCount');
    var note = document.getElementById('uploaderNote');

    var MAX = 6;
    var MAX_SIZE = 5 * 1024 * 1024;
    var TYPES = ['image/jpeg', 'image/png', 'image/webp'];
    var dt = new DataTransfer();

    function key(f) { return f.name + ':' + f.size + ':' + f.lastModified; }
    function keys() { return Array.prototype.map.call(dt.files, key); }

    function sync() { try { input.files = dt.files; } catch (e) {} }

    function warn(msg) {
        if (!note) return;
        note.textContent = msg;
        note.hidden = false;
        clearTimeout(warn._t);
        warn._t = setTimeout(function () { note.hidden = true; }, 4500);
    }

    function addFiles(list) {
        var seen = keys();
        var rejected = 0;
        Array.prototype.forEach.call(list, function (f) {
            if (dt.files.length >= MAX) { rejected++; return; }
            if (TYPES.indexOf(f.type) === -1 || f.size > MAX_SIZE) { rejected++; return; }
            if (seen.indexOf(key(f)) !== -1) return;
            seen.push(key(f));
            dt.items.add(f);
        });
        sync();
        render();
        if (rejected > 0) {
            warn(rejected + ' file(s) skipped — only JPG/PNG/WEBP up to 5 MB, max ' + MAX + ' photos.');
        }
    }

    function removeAt(index) {
        var files = Array.prototype.slice.call(dt.files);
        files.splice(index, 1);
        dt = new DataTransfer();
        files.forEach(function (f) { dt.items.add(f); });
        sync();
        render();
    }

    function render() {
        grid.innerHTML = '';
        Array.prototype.forEach.call(dt.files, function (f, i) {
            var item = document.createElement('div');
            item.className = 'uploader-item';

            var img = document.createElement('img');
            img.src = URL.createObjectURL(f);
            img.alt = f.name;
            img.onload = function () { URL.revokeObjectURL(img.src); };
            item.appendChild(img);

            if (i === 0) {
                var cover = document.createElement('span');
                cover.className = 'uploader-cover';
                cover.textContent = 'Cover';
                item.appendChild(cover);
            }

            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'uploader-remove';
            btn.setAttribute('aria-label', 'Remove photo');
            btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>';
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                removeAt(i);
            });
            item.appendChild(btn);

            grid.appendChild(item);
        });
        count.textContent = dt.files.length + ' / ' + MAX;
    }

    input.addEventListener('change', function () { addFiles(input.files); });

    ['dragenter', 'dragover'].forEach(function (ev) {
        drop.addEventListener(ev, function (e) {
            e.preventDefault();
            e.stopPropagation();
            drop.classList.add('dragover');
        });
    });
    ['dragleave', 'dragend', 'drop'].forEach(function (ev) {
        drop.addEventListener(ev, function (e) {
            e.preventDefault();
            e.stopPropagation();
            drop.classList.remove('dragover');
        });
    });
    drop.addEventListener('drop', function (e) {
        if (e.dataTransfer && e.dataTransfer.files) addFiles(e.dataTransfer.files);
    });
    uploader.addEventListener('dragover', function (e) { e.preventDefault(); });
    uploader.addEventListener('drop', function (e) {
        e.preventDefault();
        if (e.dataTransfer && e.dataTransfer.files) addFiles(e.dataTransfer.files);
    });

    render();
})();
</script>
@endpush
