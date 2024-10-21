@extends('guest.layouts.app')

@push('header_script')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="{{ asset('guest/css/style.css') }}" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

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

    <!-- check out section -->
    <div class="checkout-section mt-150 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="checkout-accordion-wrap">
                        <div class="accordion" id="accordionExample">
                            <div class="card single-accordion">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Billing Address
                                        </button>
                                    </h5>
                                </div>

                                <div class="card-body">
                                    <div class="billing-address-form">
                                        <form method="post"
                                            action="{{ route('fo.shipping-product.cekk', $data['order']) }}">
                                            @csrf
                                            <p>
                                                <label for="district_id">Kabupaten / Kota</label>
                                                <select name="district_id" id="district_id" class="district_id" required>
                                                </select>

                                            </p>
                                            <p>
                                                <label for="expedisi">Ekspedisi</label>
                                                <select name="expedisi" id="expedisi" class="expedisi" required>
                                                    <option disabled selected>Pilih Ekspedisi</option>
                                                    <option value="jne">JNE</option>
                                                    <option value="pos">POS</option>
                                                    <option value="tiki">TIKI</option>
                                                    <option value="jnt">JNT</option>
                                                </select>
                                            </p>
                                            <p>
                                                <label for="address">Address</label>
                                                <textarea name="address" id="address" name="address" cols="30" rows="10" placeholder="Say Something"></textarea>
                                            </p>

                                            <input type="submit" class="boxed-btn" value="Cek">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end check out section -->

    <!-- logo carousel -->
    <div class="logo-carousel-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="logo-carousel-inner">
                        <div class="single-logo-item">
                            <img src="assets/img/company-logos/1.png" alt="">
                        </div>
                        <div class="single-logo-item">
                            <img src="assets/img/company-logos/2.png" alt="">
                        </div>
                        <div class="single-logo-item">
                            <img src="assets/img/company-logos/3.png" alt="">
                        </div>
                        <div class="single-logo-item">
                            <img src="assets/img/company-logos/4.png" alt="">
                        </div>
                        <div class="single-logo-item">
                            <img src="assets/img/company-logos/5.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end logo carousel -->
@endsection

@push('footer_script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            function populateSelect2(selector, url, selectedIds, placeholder) {
                $(selector).select2({
                    placeholder: placeholder,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        cache: true
                    },
                    templateResult: function(data) {
                        return $('<span>' + data.text + '</span>');
                    }
                });

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        selectedIds.forEach(function(selectedId) {
                            var selectedText = '';
                            response.results.forEach(function(item) {
                                if (item.id === selectedId) {
                                    selectedText = item.text;
                                }
                            });
                            $(selector).append('<option value="' + selectedId +
                                    '" selected="selected">' + selectedText + '</option>')
                                .trigger('change');
                        });
                    }
                });
            }

            // Populate category select2
            var categorySelector = '.district_id';
            var categoryUrl = "{{ route('districs.data') }}";
            var selectedCategoryIds = ['{{ old('district_id') }}', ];
            populateSelect2(categorySelector, categoryUrl, selectedCategoryIds, "Pilih Kota / Kabupaten");

        });
    </script>
@endpush
