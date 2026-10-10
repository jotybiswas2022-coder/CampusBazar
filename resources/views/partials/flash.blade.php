@php
    $flashMeta = [
        'success' => [
            'title' => 'Success',
            'icon' => '<path d="M20 6 9 17l-5-5"/>',
        ],
        'error' => [
            'title' => 'Something went wrong',
            'icon' => '<circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/>',
        ],
        'warning' => [
            'title' => 'Heads up',
            'icon' => '<path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/>',
        ],
        'info' => [
            'title' => 'Notice',
            'icon' => '<circle cx="12" cy="12" r="9"/><path d="M12 16v-4M12 8h.01"/>',
        ],
    ];

    $flashMessages = [];
    foreach (['success', 'status'] as $key) {
        if (session($key)) {
            $flashMessages[] = ['type' => 'success', 'text' => session($key)];
        }
    }
    foreach (['error', 'warning', 'info'] as $key) {
        if (session($key)) {
            $flashMessages[] = ['type' => $key, 'text' => session($key)];
        }
    }
@endphp

@if(count($flashMessages) || $errors->any())
<style>
    .flash-stack{display:flex;flex-direction:column;gap:.6rem;margin-bottom:1.25rem}
    .flash{position:relative;display:flex;align-items:flex-start;gap:.75rem;padding:.85rem 2.5rem .9rem 1rem;border:1px solid transparent;border-radius:12px;font-size:.88rem;box-shadow:0 8px 22px rgba(15,23,42,.07);overflow:hidden;animation:flash-in .32s cubic-bezier(.2,.8,.3,1)}
    .flash-icon{flex-shrink:0;width:32px;height:32px;border-radius:9px;display:grid;place-items:center;color:#fff;box-shadow:0 5px 12px rgba(15,23,42,.18)}
    .flash-icon svg{width:18px;height:18px}
    .flash-body{display:flex;flex-direction:column;gap:.1rem;padding-top:.05rem;min-width:0}
    .flash-title{font-weight:700;font-size:.86rem;letter-spacing:-.01em}
    .flash-text{font-size:.86rem;line-height:1.45;opacity:.94}
    .flash-text ul{margin:.3rem 0 0 1.05rem}
    .flash-close{position:absolute;top:.55rem;right:.55rem;width:26px;height:26px;border-radius:8px;border:none;background:transparent;color:currentColor;opacity:.55;cursor:pointer;display:grid;place-items:center;transition:.16s}
    .flash-close:hover{opacity:1;background:rgba(15,23,42,.09)}
    .flash-close svg{width:14px;height:14px}
    .flash-progress{position:absolute;left:0;bottom:0;height:3px;width:100%;transform-origin:left;background:currentColor;opacity:.3;animation:flash-progress 5.5s linear forwards}
    .flash.hide{animation:flash-out .3s ease forwards}
    .flash-success{background:#ecfdf5;border-color:#a7f3d0;color:#065f46}
    .flash-success .flash-icon{background:linear-gradient(135deg,#34d399,#059669)}
    .flash-error{background:#fef2f2;border-color:#fecaca;color:#991b1b}
    .flash-error .flash-icon{background:linear-gradient(135deg,#f87171,#dc2626)}
    .flash-warning{background:#fffbeb;border-color:#fde68a;color:#92400e}
    .flash-warning .flash-icon{background:linear-gradient(135deg,#fbbf24,#d97706)}
    .flash-info{background:#eff6ff;border-color:#bfdbfe;color:#1e40af}
    .flash-info .flash-icon{background:linear-gradient(135deg,#60a5fa,#2563eb)}
    @keyframes flash-in{from{opacity:0;transform:translateY(-10px) scale(.99)}to{opacity:1;transform:translateY(0) scale(1)}}
    @keyframes flash-out{to{opacity:0;transform:translateX(16px);max-height:0;margin-top:-.6rem;padding-top:0;padding-bottom:0}}
    @keyframes flash-progress{from{transform:scaleX(1)}to{transform:scaleX(0)}}
    @media (prefers-reduced-motion:reduce){.flash,.flash.hide,.flash-progress{animation:none}}
</style>
<div class="flash-stack" id="flashStack">
    @foreach($flashMessages as $flash)
        <div class="flash flash-{{ $flash['type'] }}" role="alert" @if(in_array($flash['type'], ['success', 'info'])) data-autohide="1" @endif>
            <span class="flash-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">{!! $flashMeta[$flash['type']]['icon'] !!}</svg>
            </span>
            <div class="flash-body">
                <strong class="flash-title">{{ $flashMeta[$flash['type']]['title'] }}</strong>
                <span class="flash-text">{{ $flash['text'] }}</span>
            </div>
            <button type="button" class="flash-close" aria-label="Dismiss">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
            @if(in_array($flash['type'], ['success', 'info']))<span class="flash-progress" aria-hidden="true"></span>@endif
        </div>
    @endforeach

    @if($errors->any())
        <div class="flash flash-error" role="alert">
            <span class="flash-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/></svg>
            </span>
            <div class="flash-body">
                <strong class="flash-title">Please fix the following</strong>
                <span class="flash-text">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </span>
            </div>
            <button type="button" class="flash-close" aria-label="Dismiss">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
    @endif
</div>
<script>
    (function () {
        var stack = document.getElementById('flashStack');
        if (!stack) return;
        function dismiss(el) {
            if (!el || el.classList.contains('hide')) return;
            el.classList.add('hide');
            el.addEventListener('animationend', function () { el.remove(); }, { once: true });
        }
        stack.querySelectorAll('.flash').forEach(function (flash) {
            var btn = flash.querySelector('.flash-close');
            if (btn) btn.addEventListener('click', function () { dismiss(flash); });
            if (flash.dataset.autohide === '1') {
                var timer = setTimeout(function () { dismiss(flash); }, 5500);
                flash.addEventListener('mouseenter', function () { clearTimeout(timer); });
            }
        });
    })();
</script>
@endif
