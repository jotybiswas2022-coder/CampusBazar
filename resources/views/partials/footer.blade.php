<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="brand" style="margin-bottom:.7rem">
                    <span class="logo" style="width:34px;height:34px">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9.5 12 4l9 5.5-9 5.5z"/><path d="M7 12v6c0 .6.16 1 .5 1.3L12 22l4.5-2.7c.34-.3.5-.7.5-1.3v-6"/></svg>
                    </span>
                    <span>Campus<em>Bazar</em></span>
                </div>
                <p class="footer-about">
                    A student-only marketplace for colleges and universities in Bangladesh. Buy smart, sell easy, stay connected.
                </p>
                <span class="cod-note footer-cod">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a4 4 0 0 0-8 0v2"/></svg>
                    Cash on Delivery only
                </span>
            </div>
            <div>
                <h4>Marketplace</h4>
                <a href="{{ route('marketplace.index') }}">All Products</a>
                <a href="{{ route('marketplace.index', ['sort' => 'latest']) }}">New Arrivals</a>
                <a href="{{ route('marketplace.index', ['sort' => 'price_asc']) }}">Best Prices</a>
                <a href="{{ route('campuses.index') }}">Browse by Campus</a>
            </div>
            <div>
                <h4>Account</h4>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('seller.products.create') }}">Sell an Item</a>
                <a href="{{ route('orders.index') }}">My Orders</a>
                <a href="{{ route('verification.status') }}">ID Verification</a>
            </div>
            <div>
                <h4>Company</h4>
                <a href="{{ route('about') }}">About</a>
                <a href="{{ route('contact.page') }}">Contact</a>
                <a href="{{ route('terms') }}">Terms</a>
                <a href="{{ route('privacy') }}">Privacy</a>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} CampusBazar. Made for students in Bangladesh.</span>
            <span class="footer-legal">
                <span>Prices in BDT (৳) · COD only</span>
                <a class="to-top" href="#top">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                    Back to top
                </a>
            </span>
        </div>
    </div>
</footer>
