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
            <form class="searchbar" method="get" action="{{ route('shop-product.search') }}">
                <div class="form-outline" data-mdb-input-init>
                    <input type="search" name="name" style="border-radius: 100px; height: 40px;" id="form1"
                        class="form-control" placeholder="search product"
                        value="{{ isset($data['searchparameter']) ? $data['searchparameter'] : '' }}" />
                </div>
                <button type="submit" id="searchbutton" class="btn"
                    style="color: white; height: 40px; width: 40px; border-radius: 100px; background-color: darkorange;"
                    data-mdb-ripple-init>
                    <i class="fas fa-search"></i>
                </button>
            </form>
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
                            @if (isset($data['idCategory']))
                                <li class="active" data-filter="*">All</li>
                                @foreach ($data['subcategories'] as $subcategory)
                                    <li data-filter=".{{ $subcategory['id'] }}">{{ $subcategory['name'] }}</li>
                                @endforeach
                            @endif
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
                @foreach ($data['products'] as $product)
                    <div class="col-lg-4 col-md-6 text-center {{ $product['subcategory_id'] }}">
                        <div class="single-product-item">
                            <a href="{{ route('shop-product.show', $product['slug']) }}">
                                <div class="product-image">
                                    {{-- @if ($product['stock'] == 0) --}}
                                    @if ($product['total_count'] == 0)
                                        <div
                                            style="position: absolute; background-color: rgba(0, 0, 0, 0.5); color: white; border-radius: 50%; width: 100px; height: 100px; padding: 20px; text-align: center; display: flex; justify-content: center; align-items: center;">
                                            Sold Out
                                        </div>
                                    @endif
                                    @if (isset($product['images'][0]['name']))
                                        <img src="{{ asset('storage/' . $product['images'][0]['name']) }}"
                                            class="product-img" alt="">
                                    @else
                                        <img src="{{ asset('guest/img/latest-news/none_image.png') }}" class="product-img"
                                            alt="">
                                    @endif
                                </div>
                                <h2 class="">{{ $product['name'] }}</h2>
                                <span style="color: black;">SKU : {{ $product['sku']->code }}</span>
                                {{-- <p class="p-4">{{ $product->description }}</p> --}}
                                <p class="p-4">{!! nl2br(e($product->description)) !!}</p>
                                <p class="product-price">{{ $product->fPrice() }}</p>
                            </a>
                            {{-- @if ($product['stock'] == 0) --}}
                            @if ($product['total_count'] == 0)
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
                @endforeach
            </div>

            <div class="row">
                <div class="col text-center">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            {{ $data['products']->links() }}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-outline {
            width: 50%;
        }

        @media only screen and (max-width: 600px) {
            .form-outline {
                width: 100%;
            }
        }

        .searchbar {
            width: 100%;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 2rem;
            flex-direction: row;
        }

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
