@extends('guest.layouts.app')

{{-- @push('header_script')

    <style>
        .abt-bg {
            background-image: url("{{ asset('storage/' . $data['about']['image']) }}");
        }
    </style>
@endpush --}}

@section('content')
    <!-- home page slider -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <div class="homepage-slider">
        <!-- single home slider -->
        {{-- <div class="single-homepage-slider homepage-bg-1"> --}}
        {{-- slider automation menggunakan blade laravel, plan saya ingin menggunakan looping --}}
        @foreach ($data['sliders'] as $slider)
            {{-- <div class="single-homepage-slider" style="background-image: url('{{ asset('guest/img/slide-home/1.png') }}')"> --}}
            <div class="single-homepage-slider" style="background-image: url('{{ asset($slider->image) }}')">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 col-lg-7 offset-lg-1 offset-xl-0">
                            <div class="hero-text">
                            </div>
                        </div>
                        {{-- button bisa disesuaikan, apabila terdapat kondisi true, maka tampilkan button dibawah --}}
                        @if ($slider->button_title)
                            <button class="btn {{ $slider->button_background == '#fff' ? 'btn-light' : '' }} btn-lg"
                                style="
                        position: absolute;
                        {{ $slider->button_background !== '$fff' ? 'background_color: ' . $slider->button_background . ';' : '' }}
                        {{ $slider->button_text_color !== '$000' ? 'color: ' . $slider->button_text_color . ';' : '' }}
                        bottom: 5rem;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        ">read
                                more <i class="ri-links-fill"></i>
                        @endif
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- end home page slider -->

    <!-- product section -->
    <div class="product-section mt-150 mb-150">
        <div class="container">
            @if ($data['ptotal'] > 0)
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 text-center">
                        <div class="section-title">
                            <h3><span class="orange-text">Our</span> Products</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($data['products'] as $product)
                        <div class="col-lg-4 col-md-6 text-center {{ $product['subcategory_id'] }}">
                            <div class="single-product-item">
                                <a href="{{ route('shop-product.show', $product['slug']) }}">
                                    <div class="product-image">
                                        @if (isset($product['images'][0]['name']))
                                            <img src="{{ asset('storage/' . $product['images'][0]['name']) }}"
                                                class="product-img" alt="">
                                        @else
                                            <img src="{{ asset('guest/img/latest-news/none_image.png') }}"
                                                class="product-img" alt="">
                                        @endif
                                    </div>
                                    <h3>{{ $product['name'] }}</h3>
                                    {{-- <p class="p-4">{{ $product->description }}</p> --}}
                                    <p class="p-4">{!! nl2br(e($product->description)) !!}</p>
                                    <p class="product-price">{{ $product->fPrice() }}</p>
                                </a>
                                <a class="boxed-btn" href="{{ route('shop-product.show', $product['slug']) }}">Beli
                                    sekarang</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            @if ($data['ptotal'] > 3)
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <a href="{{ route('shop-product.index') }}" class="boxed-btn">More Product</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- end product section -->

    <!-- advertisement section -->
    <div class="abt-section mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="abt-bg" style="background-image: url('{{ asset('storage/' . $data['about']['image']) }}')">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="abt-text">
                        <h2>{{ $data['about']['title'] }}</h2>
                        <p>{{ $data['about']['description'] }}</p>
                        <a href="{{ route('fo.about.index') }}" class="boxed-btn mt-4">know more</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end advertisement section -->

    @if ($data['etetotal'] > 1)
        <!-- testimonail-section -->
        <div class="testimonail-section mt-150">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 offset-lg-1 text-center">
                        <div class="testimonial-sliders">

                            @foreach ($data['testimonis'] as $testimoni)
                                <div class="single-testimonial-slider">
                                    <div class="client-meta">
                                        <h3>{{ $testimoni['customerData']['name'] }} <span>@
                                                {{ $testimoni['customerData']['username'] }}</span></h3>
                                        <div class="row d-flex justify-content-center">
                                            <div class="col-3 text-center">
                                                {!! displayStars($testimoni['rating']) !!}
                                            </div>
                                        </div>
                                        <p class="testimonial-body">
                                            " {{ $testimoni['testimonial'] }} "
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end testimonail-section -->
    @endif

    <!-- latest news -->
    <div class="latest-news pt-150 pb-150">
        <div class="container">
            @if ($data['etotal'] > 0)
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 text-center">
                        <div class="section-title">
                            <h3><span class="orange-text">Our</span> Event</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($data['events'] as $event)
                        <div class="col-lg-4 col-md-6">
                            <div class="single-latest-news">
                                <a href="{{ route('fo.event.show', $event->slug) }}">
                                    <div class="latest-news-bg news-bg-none"
                                        style="background-image: url('{{ asset('storage/' . $event->cover_image) }}')">
                                    </div>
                                </a>
                                <div class="news-text-box">
                                    <h3><a href="{{ route('fo.event.show', $event->slug) }}">{{ $event->title }}</a></h3>
                                    <p class="blog-meta">
                                        <span class="author"><i class="fas fa-user"></i>{{ $event->name }}</span>
                                        <span class="date"><i class="fas fa-calendar"></i>{{ $event->updated_at }}</span>
                                    </p>
                                    <p class="excerpt">{!! $event->description !!}</p>
                                    <a href="{{ route('fo.event.show', $event->slug) }}" class="read-more-btn">read more <i
                                            class="fas fa-angle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            @if ($data['etotal'] > 3)
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <a href="{{ route('fo.event.index') }}" class="boxed-btn">More News</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- end latest news -->

    @if ($data['ptotal'] > 0)
        <!-- logo carousel -->
        <div class="logo-carousel-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 text-center">
                        <div class="section-title">
                            <h3>PART<span class="orange-text">NER</span></h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="logo-carousel-inner">
                            @foreach ($data['partners'] as $partner)
                                <div class="single-logo-item">
                                    <a href="{{ route('fo.partner.show', $partner['id']) }}"><img
                                            src="{{ asset('storage/' . $partner['image']) }}" alt=""></a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end logo carousel -->
    @endif

    <div class="modal fade" id="iklan_new">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal body -->
                <div class="modal-body">
                    <button type="button" id="closeModalButton" class="close mb-3" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <img src="{{ asset('storage/' . $data['discount_new']) }}" alt="Segera Daftar dan Dapatkan Discount">
                </div>
            </div>
        </div>
    </div>
@endsection
@push('footer_script')
    <script>
        $(document).ready(function() {
            // Show the modal when the page loads
            if (!sessionStorage.getItem('modalShown')) {
                // Set timeout to show modal after 2 seconds
                setTimeout(function() {
                    $('#iklan_new').modal('show');
                    // Set sessionStorage to indicate modal has been shown
                    sessionStorage.setItem('modalShown', 'true');
                }, 2000);
            }

            $('#closeModalButton').on('click', function() {
                $('#iklan_new').modal('hide');
            });
        });
    </script>

    <?php
    function displayStars($rating)
    {
        $rating = max(0, min($rating, 5));
    
        $fullStars = floor($rating);
        $emptyStars = 5 - $fullStars;
    
        $starHTML = '<div class="star-ratingt text-center row d-flex justify-content-center">';
    
        for ($i = 0; $i < $fullStars; $i++) {
            $starHTML .= '<span class="star full col-2">&#9733;</span>';
        }
    
        for ($i = 0; $i < $emptyStars; $i++) {
            $starHTML .= '<span class="star col-2">&#9733;</span>';
        }
        $starHTML .= '</div>';
    
        return $starHTML;
    }
    ?>
@endpush
