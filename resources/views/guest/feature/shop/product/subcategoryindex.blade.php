@extends('guest.layouts.app')

@section('content')
    <!-- breadcrumb-section -->
    <div class="breadcrumb-section" style="background-image: url({{ asset($data['banner'][0]) }})">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="breadcrumb-text">
                        <h1>{{ $ref['title'] }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- products -->
    <div class="product-section mt-150 mb-150">
        <div class="container">

            <div class="row">
                <div class="col-md-12">
                    <div class="category-product-filters">
                        <ul>
                            <a href="{{ route('shop-product.index') }}">
                                <li class="{{ isset($data['idCategory']) ? '' : 'active' }}">All</li>
                            </a>
                            @foreach ($data['categories'] as $category)
                                <a href="{{ route('shop-product.category', $category['id']) }}">
                                    <li
                                        class="{{ isset($data['idCategory']) && $data['idCategory'] == $category['id'] ? 'active' : '' }}">
                                        {{ $category['name'] }}</li>
                                </a>
                            @endforeach
                        </ul>
                    </div>
                    <div class="product-filters">
                        <ul>
                            {{-- @if (isset($data['idCategory'])) --}}
                            <a href="{{ route('shop-product.index') }}">
                                <li>All</li>
                            </a>
                            @foreach ($data['subcategories'] as $subcategory)
                                <a href="{{ route('shop-product.subcategory', $subcategory['name']) }}">
                                    <li data-filter=".{{ $subcategory['id'] }}"
                                        class="{{ urldecode(request()->segment(2)) == $subcategory['name'] ? 'active' : '' }}">
                                        {{ $subcategory['name'] }}
                                    </li>
                                </a>
                            @endforeach
                            {{-- @endif --}}
                            @if (Route::is('shop-product.index'))
                                <li class="{{ Route::is('shop-product.index') ? 'active' : '' }}" data-filter="*">All</li>
                                @foreach ($data['subcategories'] as $subcategories)
                                    <a href="{{ route('shop-product.subcategory', $subcategories['name']) }}">
                                        <li data-filter=".{{ $subcategories['name'] }}">{{ $subcategories['name'] }}</li>
                                    </a>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row product-lists">
                {{-- {{ dd($data['products']) }} --}}
                @forelse ($data['products'] as $product)
                    @if ($product !== null)
                        <div class="col-lg-4 col-md-6 text-center">
                            <div class="single-product-item">
                                <a href="{{ route('shop-product.show', $product['slug']) }}">
                                    <div class="product-image">
                                        @if ($product['stock'] == 0)
                                            <div
                                                style="position: absolute; background-color: rgba(0, 0, 0, 0.5); color: white; border-radius: 50%; width: 100px; height: 100px; padding: 20px; text-align: center; display: flex; justify-content: center; align-items: center;">
                                                Sold Out
                                            </div>
                                        @endif
                                        @if (isset($product['images'][0]['name']))
                                            <img src="{{ asset('storage/' . $product['images'][0]['name']) }}"
                                                class="product-img" alt="">
                                        @else
                                            <img src="{{ asset('guest/img/latest-news/none_image.png') }}"
                                                class="product-img" alt="">
                                        @endif
                                    </div>
                                    <h2 class="">{{ $product['name'] }}</h2>
                                    <span style="color: black;">SKU : {{ $product['sku']->code }}</span>
                                    {{-- <p class="p-4">{{ $product->description }}</p> --}}
                                    <p class="p-4">{!! nl2br(e($product->description)) !!}</p>
                                    <p class="product-price">{{ $product->fPrice() }}</p>
                                </a>
                                @if ($product['stock'] == 0)
                                    <a class="boxed-btn"
                                        href="https://wa.me/+6285932966345?text=Hallo%20Astraea%20Leather%20Craft">Hubungi
                                        Penjual</a>
                                @else
                                    <a class="boxed-btn" href="{{ route('shop-product.show', $product['slug']) }}">Beli
                                        sekarang</a>
                                @endif
                                {{-- <form action="{{ route('fo.cart-product.update', $product['name']) }}" method="post">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="boxed-btn">
                                <i class="fas fa-shopping-cart">
                                </i> Add to Cart
                            </button>
                        </form> --}}
                            </div>
                        </div>
                    @endif
                @empty
                    {{-- <div class="col-12 text-center">
                        <p>Stok Kosong, silakan pilih subcategori lainnya</p>
                    </div> --}}
                    <div class="col-12 text-center">
                        <i class="ri-error-warning-line display-1 text-warning"></i>
                        <h2 class="text-warning">Hallo, untuk saat ini Stok Kosong</h2>
                        <p>Silakan pilih subcategori lainnya</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        .page-item.active .page-link {
            background-color: orange;
            border: 1px solid darkorange;
        }

        .page-link {
            color: darkorange;
        }

        .page-link:hover {
            color: darkorange;
        }
    </style>
    <!-- end products -->

    <!-- logo carousel -->
    <div class="logo-carousel-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="logo-carousel-inner">
                        @foreach ($data['partners'] as $partner)
                            <div class="single-logo-item">
                                <a href="{{ route('fo.partner.show', $partner['id']) }}">
                                    <img src="{{ asset('storage/' . $partner['image']) }}" alt="">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
