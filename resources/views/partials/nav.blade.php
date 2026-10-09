@php
    $u = auth()->user();
    $unreadNotifs = $u ? $u->unreadNotifications()->count() : 0;
    $unreadMessages = 0;
    if ($u) {
        $unreadMessages = \App\Models\Message::whereNull('read_at')
            ->where('sender_id', '!=', $u->id)
            ->whereHas('conversation', function ($q) use ($u) {
                $q->where('user_one_id', $u->id)->orWhere('user_two_id', $u->id);
            })->count();
    }
    $wishCount = $u ? $u->wishlist()->count() : 0;
@endphp
<header class="site-header">
    <div class="container">
        <div class="nav-wrap">
            <a href="{{ route('home') }}" class="brand" aria-label="CampusBazar home">
                <span class="logo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9.5 12 4l9 5.5-9 5.5z"/><path d="M7 12v6c0 .6.16 1 .5 1.3L12 22l4.5-2.7c.34-.3.5-.7.5-1.3v-6"/></svg>
                </span>
                <span>Campus<em>Bazar</em></span>
            </a>

            <form class="nav-search" action="{{ route('marketplace.index') }}" method="GET" role="search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search books, calculators, cycles…" aria-label="Search products">
            </form>

            <nav class="nav-links" aria-label="Primary">
                <a class="navlink {{ request()->routeIs('marketplace.*') ? 'active' : '' }}" href="{{ route('marketplace.index') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l1-5h16l1 5"/><path d="M4 9v11h16V9"/><path d="M9 13h6"/></svg>
                    Marketplace
                </a>
                <a class="navlink {{ request()->routeIs('campuses.*') ? 'active' : '' }}" href="{{ route('campuses.index') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9.5 12 4l9 5.5-9 5.5z"/><path d="M7 12v6l5 3 5-3v-6"/></svg>
                    Campus
                </a>
            </nav>

            <div class="nav-right">
                @auth
                    <a class="icon-btn" href="{{ route('wishlist.index') }}" title="Wishlist">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 1 0-7.8 7.8l8.8 8.8 8.8-8.8a5.5 5.5 0 0 0 0-7.8z"/></svg>
                        @if($wishCount > 0)<span class="badge-dot">{{ $wishCount > 9 ? '9+' : $wishCount }}</span>@endif
                    </a>
                    <a class="icon-btn" href="{{ route('messages.index') }}" title="Messages">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        @if($unreadMessages > 0)<span class="badge-dot">{{ $unreadMessages > 9 ? '9+' : $unreadMessages }}</span>@endif
                    </a>
                    <a class="icon-btn" href="{{ route('orders.index') }}" title="Orders">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    </a>
                    <div class="menu">
                        <button class="icon-btn" data-menu-toggle title="Notifications" aria-haspopup="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg>
                            @if($unreadNotifs > 0)<span class="badge-dot">{{ $unreadNotifs > 9 ? '9+' : $unreadNotifs }}</span>@endif
                        </button>
                        <div class="menu-panel">
                            <div style="padding:.6rem .7rem;font-weight:700;font-size:.8rem;color:var(--muted)">Notifications</div>
                            @forelse($u->notifications()->latest()->take(3)->get() as $n)
                                <a href="{{ route('notifications.read', $n->id) }}">
                                    <span>{{ $n->data['title'] ?? 'Notification' }}</span>
                                </a>
                            @empty
                                <div style="padding:.6rem .7rem;font-size:.83rem;color:var(--muted)">No notifications yet.</div>
                            @endforelse
                            <div class="menu-sep"></div>
                            <a href="{{ route('notifications.index') }}">View all notifications</a>
                        </div>
                    </div>

                    <a class="btn btn-primary" href="{{ route('seller.products.create') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                        Sell Item
                    </a>

                    <div class="menu">
                        <button class="row" data-menu-toggle style="background:none;border:none;padding:0;gap:.5rem" aria-haspopup="true">
                            <span class="avatar">{{ $u->initials() }}</span>
                        </button>
                        <div class="menu-panel">
                            <div style="padding:.5rem .7rem">
                                <div style="font-weight:700;font-size:.88rem">{{ $u->name }}</div>
                                <div class="small muted">{{ $u->email }}</div>
                                @if($u->isVerified())
                                    <span class="pill pill-green mt-1" style="margin-top:.4rem">Verified Student</span>
                                @endif
                            </div>
                            <div class="menu-sep"></div>
                            @if($u->isAdmin())
                                <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                            @endif
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                            <a href="{{ route('seller.dashboard') }}">Seller Dashboard</a>
                            <a href="{{ route('verification.status') }}">ID Verification</a>
                            <a href="{{ route('profile.edit') }}">Account Settings</a>
                            <div class="menu-sep"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit">Log out</button>
                            </form>
                        </div>
                    </div>

                    <button class="icon-btn mobile-toggle" id="mobileToggle" aria-label="Menu">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                @else
                    <a class="btn btn-outline" href="{{ route('login') }}">Log in</a>
                    <a class="btn btn-primary" href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>

        <nav id="mobileNav" class="container" style="padding-bottom:1rem" aria-label="Mobile">
            <a class="navlink" href="{{ route('marketplace.index') }}">Marketplace</a>
            <a class="navlink" href="{{ route('campuses.index') }}">Campus Directory</a>
            @auth
                <a class="navlink" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="navlink" href="{{ route('seller.products.create') }}">Sell Item</a>
            @endauth
        </nav>
    </div>
</header>
