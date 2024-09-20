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
    <div class="container"
        style="display: flex; justify-content: center; align-items: center; flex-direction: column; padding: 3rem;">
        <img src="{{ asset($service->image) }}" alt="{{ $service->name . ' image' }}" class="image">
        <p>{{ $service->description }}</p>
    </div>

    <style type="text/css">
        .container-sn {
            padding: 5rem;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            width: 100%;
        }

        .image {
            width: 50%;
        }
    </style>
@endsection
