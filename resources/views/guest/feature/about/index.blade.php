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
    @php
        use Illuminate\Support\Str;
    @endphp
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
                <div style="display: flex; flex-wrap: wrap; justify-content: space-evenly; gap: 20px; margin-bottom: 5rem;">
                    @foreach ($data['services'] as $service)
                        <a href="{{ route('about.show', $service->slug) }}">
                            <div class="card card-style" style="min-height:30rem;">
                                <img class="card-img-top" src="{{ asset($service['image']) }}"
                                    alt="{{ $service['slug'] . ' image' }}"
                                    style="object-fit: cover; width: 100%; height: 200px; display: block;">
                                <div class="card-body">
                                    <h3 class="mt-2">{{ $service['name'] }}</h3>

                                    <p class="card-text">{!! nl2br(e(Str::limit($service['description'], 65, '...'))) !!}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            <style>
                .card-style {
                    width: 18rem;
                }

                .card-style:hover {
                    animation-duration: 2ms;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }
            </style>
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
