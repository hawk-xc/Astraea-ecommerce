@extends('guest.layouts.app')

@section('content')
    <!-- breadcrumb-section -->
    <div class="breadcrumb-section breadcrumb-bg">
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
                            @if (isset($data['idCategory']))
                                <li class="active" data-filter="*">All</li>
                                @foreach ($data['subcategories'] as $subcategory)
                                    <li data-filter=".{{ $subcategory['id'] }}">{{ $subcategory['name'] }}</li>
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
                                    @if (isset($product['images'][0]['name']))
                                        <img src="{{ asset('storage/' . $product['images'][0]['name']) }}"
                                            class="product-img" alt="">
                                    @else
                                        <img src="{{ asset('guest/img/latest-news/none_image.png') }}" class="product-img"
                                            alt="">
                                    @endif
                                </div>
                                <h2 class="">{{ $product['name'] }}</h2>
                                {{-- <p class="p-4">{{ $product->description }}</p> --}}
                                <p class="p-4">{!! nl2br(e($product->description)) !!}</p>
                                <p class="product-price">{{ $product->fPrice() }}</p>
                            </a>
                            <a class="boxed-btn" href="{{ route('shop-product.show', $product['slug']) }}">Beli
                                sekarang</a>
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
    <!-- end logo carousel -->

@endsection
