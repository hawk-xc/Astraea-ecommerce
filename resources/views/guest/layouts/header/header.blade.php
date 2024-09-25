<div class="top-header-area" id="sticker">
    <div class="container">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <!-- jQuery and Bootstrap JS -->
        <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

        <div class="row">
            <div class="col-lg-12 col-sm-12 text-center">
                <div class="navbar">
                    <a href="/">
                        <img src="{{ asset('admin/img/logo.webp') }}" alt="" width="40px">
                    </a>

                    <div class="dropdown show">
                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ri-menu-3-fill"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item {{ $ref['title'] == 'Home' ? 'current-route-link' : '' }}"
                                href={{ env('APP_URL') . '/' }}>Home</a>
                            <!-- Nested dropdown -->
                            <div class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#">Shop</a>
                                <div class="dropdown-menu dropdown-submenu-content">
                                    <a class="dropdown-item {{ $ref['title'] == 'Shop Product' ? 'current-route-link' : '' }}"
                                        href="{{ route('shop-product.index') }}">Product</a>
                                    <a class="dropdown-item {{ $ref['title'] == 'Shop Hampers' ? 'current-route-link' : '' }}"
                                        href="{{ route('shop-hampers.index') }}">Hampers</a>
                                </div>
                            </div>

                            <a class="dropdown-item {{ $ref['title'] == 'Event' ? 'current-route-link' : '' }}"
                                href="{{ route('fo.event.index') }}">Event</a>

                            <a class="dropdown-item {{ $ref['title'] == 'About Us' ? 'current-route-link' : '' }}"
                                href="{{ route('fo.about.index') }}">About</a>

                            <a class="dropdown-item {{ $ref['title'] == 'Contact' ? 'current-route-link' : '' }}"
                                href="{{ route('fo.contact.index') }}">Contact</a>

                            <div class="dropdown-divider"></div>

                            <div class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#">Profile</a>
                                <div class="dropdown-menu dropdown-submenu-content">
                                    @auth('customer')
                                        <a class="dropdown-item" href="{{ route('dashboard.customer') }}">Profile</a>
                                        <a class="dropdown-item" href="{{ route('fo.cart-product.index') }}">Cart
                                            Product</a>
                                        <a class="dropdown-item" href="{{ route('fo.cart-hampers.index') }}">Cart
                                            Hampers</a>
                                        <hr>
                                        <form id="logout-form" method="POST" action="{{ route('logout.customer') }}"
                                            style="display: none;"">
                                            @csrf
                                        </form>
                                        <a href="javascript:void(0);" class="dropdown-item"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <span>Log out</span>
                                        </a>
                                    @else
                                        <a class="dropdown-item" href="{{ route('loginf.customer') }}">Login</a>
                                        <a class="dropdown-item" href="{{ route('registerf.customer') }}">Register</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="main-menu-wrap desktop-nav">
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
                                            <li>
                                                <a class="user-icon" href="{{ route('dashboard.customer') }}">
                                                    Profile
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('fo.cart-product.index') }}">
                                                    Cart Product
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('fo.cart-hampers.index') }}">
                                                    Cart Hampers
                                                </a>
                                            </li>
                                            <hr>
                                            <li>
                                                <form id="logout-form" method="POST"
                                                    action="{{ route('logout.customer') }}" style="display: none;"">
                                                    @csrf
                                                </form>
                                                <a href="javascript:void(0);"
                                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    <span>Log out</span>
                                                </a>
                                            </li>
                                        @else
                                            <li>
                                                <a class="user-icon" href="{{ route('loginf.customer') }}">
                                                    Login
                                                </a>
                                            </li>
                                            <li>
                                                <a class="user-icon" href="{{ route('registerf.customer') }}">
                                                    Register
                                                </a>
                                            </li>
                                        @endauth
                            </li>
                        </ul>
                </div>
                </li>
                </ul>
                </nav>
                {{-- mengganti mobile nav menu dengan nav baru --}}
                {{-- <div class="mobile-menu"></div> --}}
                <!-- menu end -->
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .navbar {
        display: none;
    }

    a {
        text-decoration: none;
    }

    a:hover {
        text-decoration: none;
    }

    .current-route-link {
        background-color: orange;
    }

    .current-route-link:hover {
        background-color: orangered;
    }

    .dropdown {
        display: none;
    }

    .hamburger-menu {
        width: 100px;
        height: 100px;
        background-color: brown;
    }

    @media only screen and (max-width: 767px) {
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .desktop-nav {
            display: none;
        }

        .dropdown {
            display: inline;
        }

        .dropdown-submenu .dropdown-menu {
            display: none;
            transform:
                margin-top: 0;
            /* Set margin top to avoid overlap */
            position: relative;
            transition: all 0.3s ease;
            /* Smooth transition for grow effect */
        }

        .dropdown-submenu .dropdown-menu.show {
            display: block;
            transform: scaleY(1);
            /* Grow vertically */
            opacity: 1;
            visibility: visible;
        }

        .dropdown-submenu-content {
            transform-origin: top;
            /* Transform from the top down */
            transform: scaleY(0);
            /* Initially hidden */
            opacity: 0;
            visibility: hidden;
        }

        /* Custom padding untuk dropdown-item */
        .dropdown-item {
            padding: 1rem 1.5rem;
            /* Ubah nilai ini sesuai keinginan */
        }
    }
</style>

<script>
    $(document).ready(function() {
        $('.dropdown-submenu a.dropdown-toggle').on('click', function(e) {
            var $submenu = $(this).next('.dropdown-menu');

            // Toggle the visibility and grow effect
            $submenu.toggleClass('show');

            // Close any open submenus that are not this one
            $('.dropdown-submenu .dropdown-menu').not($submenu).removeClass('show');

            e.preventDefault();
            e.stopPropagation(); // Prevent closing the main dropdown
        });

        // Optional: Close dropdown if clicked outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('.dropdown-submenu .dropdown-menu').removeClass('show');
            }
        });
    });
</script>
</div>

