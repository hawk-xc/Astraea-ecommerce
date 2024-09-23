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
    <div class="breadcrumb-section breadcrumb-bg">
        <div class="container-nd">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="breadcrumb-text">
                        <h1>{{ $service->name }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section --

                                                                                                                                                                                                                                                                                                                    <!-- featured section -->
    <div class="service-detail">
        <div style="flex: 1;">
            <img src="{{ asset($service->image) }}" alt="{{ $service->name . ' image' }}" style="width: 100%;">
        </div>
        <div style="flex: 1;">
            <h2>{{ $service->name }}</h2>
            <p>{!! nl2br(e($service->description)) !!}</p>
        </div>
    </div>

    <style type="text/css">
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
