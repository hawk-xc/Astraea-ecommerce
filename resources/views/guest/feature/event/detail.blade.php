@extends('guest.layouts.app')

{{-- @push('header_script')
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
@endpush --}}
@section('content')
    @php
        use Illuminate\Support\Str;
    @endphp
    <!-- breadcrumb-section -->
    <div class="breadcrumb-section" style="background-image: url({{ asset($data['banner'][0]) }})">
        <div class="container-nd">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="breadcrumb-text">
                        <h1>{{ $data['event']->name }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section --

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <!-- featured section -->
    <div class="service-detail">
        <div style="flex: 1;">
            <img src="{{ asset('storage/' . $data['event']->cover_image) }}"
                alt="{{ $data['event']->cover_image . ' image' }}" style="width: 100%;">
        </div>
        <div style="flex: 1;">
            <p class="blog-meta">
                <span class="author"><i class="fas fa-user"></i>{{ $data['event']->name }}</span>
                <span class="date"><i class="fas fa-calendar"></i>{{ $data['event']->updated_at }}</span>
            </p>
            <h2>{{ $data['event']->name }}</h2>
            <p>{!! nl2br(e($data['event']->description)) !!}</p>
        </div>
    </div>

    <style type="text/css">
        .service-detail {
            display: flex;
            flex-direction: row;
            padding: 5rem;
            gap: 2.4rem;
        }

        .container-sn {
            padding: 5rem;
            display: flex;
            gap: 2rem;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            width: 100%;
        }

        .image {
            width: 70%;
            margin-bottom: 2rem;
        }
    </style>
@endsection
