<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header text-center">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('home') }}">
            <img src="{{ asset('admin/img/logo.webp') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">Astraea-BO</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}"
                    href="{{ route('home') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Pages</h6>
            </li>
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#category"
                    class="nav-link {{ request()->is('admin/category*') ? 'active' : '' }}" aria-controls="masterData"
                    role="button" aria-expanded="{{ request()->is('admin/category*') ? 'true' : 'false' }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-app text-info text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Categories</span>
                </a>
                <div class="collapse {{ request()->is('admin/category*') ? 'show' : '' }}" id="category">
                    <ul class="nav ms-4">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/category/category*') ? 'active' : '' }}"
                                href="{{ route('category.index') }}">
                                <span class="sidenav-normal">Categories</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/category/subcategory*') ? 'active' : '' }}"
                                href="{{ route('subcategory.index') }}">
                                <span class="sidenav-normal">Sub Categories</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/category/color*') ? 'active' : '' }}"
                                href="{{ route('color.index') }}">
                                <span class="sidenav-normal">Color Categories</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/category/sku*') ? 'active' : '' }}"
                                href="{{ route('sku.index') }}">
                                <span class="sidenav-normal">Seri</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#productss"
                    class="nav-link {{ request()->is('admin/product*') ? 'active' : '' }}" aria-controls="masterData"
                    role="button" aria-expanded="{{ request()->is('admin/product*') ? 'true' : 'false' }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Products</span>
                </a>
                <div class="collapse {{ request()->is('admin/product*') ? 'show' : '' }}" id="productss">
                    <ul class="nav ms-4">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/product/product*') ? 'active' : '' }}"
                                href="{{ route('product.index') }}">
                                <span class="sidenav-normal">Products</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/product/hampers*') ? 'active' : '' }}"
                                href="{{ route('hampers.index') }}">
                                <span class="sidenav-normal">Hampers</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/product/slider*') ? 'active' : '' }}"
                                href="{{ route('slider.index') }}">
                                <span class="sidenav-normal">Slider <span
                                        class="badge badge-secondary">New</span></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#discount"
                    class="nav-link {{ request()->is('admin/discount*') ? 'active' : '' }}" aria-controls="masterData"
                    role="button" aria-expanded="{{ request()->is('admin/discount*') ? 'true' : 'false' }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-credit-card text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manajement Discount</span>
                </a>
                <div class="collapse {{ request()->is('admin/discount*') ? 'show' : '' }}" id="discount">
                    <ul class="nav ms-4">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/discount/disc_new_customer*') ? 'active' : '' }}"
                                href="{{ route('disc_new_customer.index') }}">
                                <span class="sidenav-normal">Discount New Customer</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/discount/discount*') ? 'active' : '' }}"
                                href="{{ route('discount.index') }}">
                                <span class="sidenav-normal">Discount Event</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/appfee*') ? 'active' : '' }}"
                    href="{{ route('appfee.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-credit-card text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">App Fee</span>
                </a>
            </li>
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#sites"
                    class="nav-link {{ request()->is('admin/comprof*') ? 'active' : '' }}" aria-controls="masterData"
                    role="button" aria-expanded="{{ request()->is('admin/comprof*') ? 'true' : 'false' }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="bi bi-globe mb-04 text-danger text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Company Profile</span>
                </a>
                <div class="collapse {{ request()->is('admin/comprof*') ? 'show' : '' }}" id="sites">
                    <ul class="nav ms-4">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/comprof/com_profile*') ? 'active' : '' }}"
                                href="{{ route('com_profile.index') }}">
                                <span class="sidenav-normal">Profile</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/comprof/event*') ? 'active' : '' }}"
                                href="{{ route('event.index') }}">
                                <span class="sidenav-normal">Event</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/comprof/service*') ? 'active' : '' }}"
                                href="{{ route('service.index') }}">
                                <span class="sidenav-normal">Services</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/comprof/partner*') ? 'active' : '' }}"
                                href="{{ route('partner.index') }}">
                                <span class="sidenav-normal">Partner</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/comprof/certificate*') ? 'active' : '' }}"
                                href="{{ route('certificate.index') }}">
                                <span class="sidenav-normal">Certificate</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/comprof/contact*') ? 'active' : '' }}"
                                href="{{ route('contact.index') }}">
                                <span class="sidenav-normal">Contact</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/comprof/banner*') ? 'active' : '' }}"
                                href="{{ route('banner.index') }}">
                                <span class="sidenav-normal">Banner</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Customers Pages</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/management_customer*') ? 'active' : '' }}"
                    href="{{ route('management_customer.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-copy-04 text-warning text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Management Customer</span>
                </a>
            </li>
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#orderss"
                    class="nav-link  {{ request()->is('admin/order*') ? 'active' : '' }}" aria-controls="masterData"
                    role="button" aria-expanded=" {{ request()->is('admin/order*') ? 'true' : 'false' }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-copy-04 text-warning text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Orders</span>
                </a>
                <div class="collapse {{ request()->is('admin/order*') ? 'show' : '' }}" id="orderss">
                    <ul class="nav ms-4">
                        <li class="nav-item">
                            <a class="nav-link  {{ request()->is('admin/order_product*') ? 'active' : '' }}"
                                href="{{ route('order_product.index') }}">
                                <span class="sidenav-normal">Products</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  {{ request()->is('admin/order_hampers*') ? 'active' : '' }}"
                                href="{{ route('order_hampers.index') }}">
                                <span class="sidenav-normal">Hampers</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/testimoni*') ? 'active' : '' }}"
                    href="{{ route('testimoni.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-collection text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Testimoni</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/mail_visitor*') ? 'active' : '' }}"
                    href="{{ route('mail_visitor.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-collection text-info text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Mail</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/profile*') ? 'active' : '' }}"
                    href="{{ route('profile.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/user*') ? 'active' : '' }}"
                    href="{{ route('user.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">User Management</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
