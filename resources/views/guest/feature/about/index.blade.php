@extends('guest.layouts.app')

@push('header_script')
    <style>
        .feature-bg {
            position: relative;
            margin: 150px 0;
        }

        .feature-bg:after {
            background-image: url({{ asset('storage/' . $data['about']['image']) }});
            min-height: 250px;
            -webkit-box-shadow: 0 0 0px #cacaca;
            box-shadow: 0 0 0px #cacaca;
        }

        .sevice-img {
            height: 100%;
            max-height: 200px;
        }
    </style>
@endpush
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

    <!-- featured section -->
    <div class="feature-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 px-3">
                    <div class="featured-text">
                        <h2 class="pb-3">{{ $data['about']['title'] }}</h2>
                        <div class="row">
                            <div class="col">
                                <div class="list-box d-flex">
                                    <div class="content">
                                        <p>{{ $data['about']['description'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end featured section -->
    @if ($data['stotal'] > 0)
        <!-- our service -->
        <div class="mt-150">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 text-center">
                        <div class="section-title">
                            <h3>OUR <span class="orange-text">SERVICES</span></h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($data['services'] as $service)
                        <div class="col-lg-4 col-md-6 text-center mb-3">
                            <img class="sevice-img" src="{{ asset('storage/' . $service['image']) }}" alt="">
                            <h3 class="mt-2">{{ $service['name'] }}</h3>
                            <p class="text-justify px-3 text-break">{{ $service['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- end our service -->
    @endif

    @if ($data['etetotal'] > 1)
        <!-- testimonail-section -->
        <div class="testimonail-section mt-150 mb-5">
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

    @if ($data['ctotal'] > 0)
        <!-- logo carousel -->
        <div class="logo-carousel-section-cer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 text-center">
                        <div class="section-title">
                            <h3>CERTIFI<span class="orange-text">CATE</span></h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="logo-carousel-inner">
                            @foreach ($data['certificates'] as $certificate)
                                <div class="single-logo-item">
                                    <a href="{{ route('fo.certificate.show', $certificate['id']) }}"><img
                                            src="{{ asset('storage/' . $certificate['image']) }}" alt=""></a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end logo carousel -->
    @endif

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
@endsection

@push('footer_script')
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
