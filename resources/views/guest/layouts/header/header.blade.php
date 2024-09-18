<div class="top-header-area" id="sticker">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-sm-12 text-center">
                <div class="main-menu-wrap">
                    <!-- logo -->
                    <div class="site-logo">
                        <a href="/">
                            <img src="{{ asset('admin/img/logo.webp') }}" alt="" width="40px">
                            <h5 class="text-white">Astraea Leather Craft</h5>
                        </a>
                    </div>
                    <!-- logo -->

                    <!-- menu start -->
                    <nav class="main-menu pt-3">
                        <ul>
                            <li class="{{ $ref['title'] == 'Home' ? 'current-list-item' : '' }}">
                                <a href={{ env('APP_URL') . '/' }}>Home</a>
                            </li>
                            <li
                                class="{{ $ref['title'] == 'Shop Product' || $ref['title'] == 'Shop Hampers' ? 'current-list-item' : '' }}">
                                <a href="#">Shop</a>
                                <ul class="sub-menu">
                                    <li class="{{ $ref['title'] == 'Shop Product' ? 'current-list-item' : '' }}">
                                        <a href="{{ route('shop-product.index') }}">Product</a>
                                    </li>
                                    <li class="{{ $ref['title'] == 'Shop Hampers' ? 'current-list-item' : '' }}">
                                        <a href="{{ route('shop-hampers.index') }}">Hampers</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="{{ $ref['title'] == 'Event' ? 'current-list-item' : '' }}">
                                <a href="{{ route('fo.event.index') }}">Event</a>
                            </li>
                            <li class="{{ $ref['title'] == 'About Us' ? 'current-list-item' : '' }}">
                                <a href="{{ route('fo.about.index') }}">About</a>
                            </li>
                            <li class="{{ $ref['title'] == 'Contact' ? 'current-list-item' : '' }}">
                                <a href="{{ route('fo.contact.index') }}">Contact</a>
                            </li>

                            <li>
                            </li>
                            <li
                                class="{{ $ref['title'] == 'Profile' || $ref['title'] == 'Cart Hampers' || $ref['title'] == 'Cart Product' ? 'current-list-item' : '' }}">
                                <a href="#"><i class="fas fa-user"></i></a>
                                <div class="header-icons">
                                    <ul class="sub-menu">
                                        <li>
                                            @auth('customer')
                                                <a class="user-icon" href="{{ route('dashboard.customer') }}">
                                                    Profile
                                                </a>
                                            @else
                                                <a class="user-icon" href="{{ route('loginf.customer') }}">
                                                    Login
                                                </a>
                                            @endauth
                                        </li>
                                        <li>
                                            <a href="{{ route('fo.cart-product.index') }}">
                                                Cart Product</a>
                                        </li>
                                        <li><a href="{{ route('fo.cart-hampers.index') }}">
                                                Cart Hampers</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </nav>
                    <div class="mobile-menu"></div>
                    <!-- menu end -->
                </div>
            </div>
        </div>
    </div>
</div>
