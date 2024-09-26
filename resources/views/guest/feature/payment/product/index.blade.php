@extends('guest.layouts.app')

@push('header_script')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="{{ asset('guest/css/style.css') }}" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    <!-- check out section -->
    <div class="checkout-section mt-150 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="checkout-accordion-wrap">
                        <div class="accordion" id="accordionExample">
                            <div class="card single-accordion">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Customer
                                        </button>
                                    </h5>
                                </div>

                                <div>
                                    <div class="card-body">
                                        <div class="billing-address-form">
                                            <p>Name</p>
                                            <p>{{ Auth()->guard('customer')->user()->name }}</p>
                                            <hr>
                                            <p>Email</p>
                                            <p>{{ Auth()->guard('customer')->user()->email }}</p>
                                            <hr>
                                            <p>Phone</p>
                                            <p>{{ Auth()->guard('customer')->user()->phone }}</p>
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card single-accordion">
                                <div class="card-header" id="headingTwo">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Shipping
                                        </button>
                                    </h5>
                                </div>
                                <div>
                                    <div class="card-body">
                                        <div class="billing-address-form">
                                            <form method="post"
                                                action="{{ route('fo.shipping-product.cekk', $data['id_nota']) }}">
                                                @csrf
                                                <p>
                                                    <label for="district_id">Kabupaten / Kota</label>
                                                    <select name="district_id" id="district_id" class="district_id"
                                                        required>
                                                    </select>

                                                </p>
                                                <p>
                                                    @php
                                                        $shippingName = old(
                                                            'expedisi',
                                                            $data['shipping']['name'] ?? '',
                                                        );
                                                    @endphp
                                                    <label for="expedisi">Ekspedisi</label>
                                                    <select name="expedisi" id="expedisi" class="expedisi" required>
                                                        <option disabled selected>Pilih Ekspedisi</option>
                                                        <option value="jne"
                                                            {{ $shippingName == 'jne' ? 'selected' : '' }}>JNE</option>
                                                        <option value="pos"
                                                            {{ $shippingName == 'pos' ? 'selected' : '' }}>POS</option>
                                                        <option value="tiki"
                                                            {{ $shippingName == 'tiki' ? 'selected' : '' }}>TIKI</option>
                                                        <option value="jnt"
                                                            {{ $shippingName == 'jnt' ? 'selected' : '' }}>JNT</option>
                                                    </select>
                                                </p>
                                                <p>
                                                    <label for="address">Address</label>
                                                    <textarea name="address" id="address" name="address" cols="30" rows="10" placeholder="Address" required>{{ old('address', isset($data['order']['address']) && !empty($data['order']['address']) ? $data['order']['address'] : Auth()->guard('customer')->user()->address) }}</textarea>
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

                <div class="col-lg-4">
                    <div class="total-section">
                        <table class="total-table">
                            <thead class="total-table-head">
                                <tr class="table-total-row">
                                    <th>Total</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="total-data">
                                    <td><strong>Subtotal </strong></td>
                                    <td>{{ $data['order']->fSTPrice() !== null ? $data['order']->fSTPrice() : '' }}</td>
                                </tr>

                                <tr class="total-data">
                                    <td><strong>Ongkir </strong></td>
                                    <td>
                                        {{ $data['order']->fShipping() !== null ? $data['order']->fShipping() : '' }}
                                    </td>
                                </tr>

                                <tr class="total-data">
                                    <td><strong>Biaya Aplikasi </strong></td>
                                    <td>
                                        {{ $data['app_fee'] }}
                                    </td>
                                </tr>

                                <tr class="total-data">
                                    <td><strong>Discount </strong></td>
                                    <td>
                                        {{ isset($data['order']['discount_amount']) ? $data['order']['discount_amount'] . '%' : '0%' }}
                                    </td>
                                </tr>

                                <tr class="total-data">
                                    <td><strong>Total </strong></td>
                                    <td>{{ $data['total_price'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="cart-buttons">
                            <a href="{{ route('fo.cart-product.index') }}" class="boxed-btn">Update Cart</a>
                        </div>
                    </div>

                    <div class="coupon-section">
                        <h3>Apply Coupon</h3>
                        <div class="coupon-form-wrap">
                            <form action="{{ route('fo.discount-apply.update', $data['id_order']) }}" method="post">
                                @method('put')
                                @csrf
                                <p><input type="text" placeholder="Coupon" name="coupon"></p>
                                <p><input type="submit" value="Apply"></p>
                            </form>
                        </div>
                    </div>

                    <div class="coupon-section">
                        <h3>Catatan</h3>
                        <div class="coupon-form-wrap">
                            <form action="{{ route('product-payment.create', $data['id_nota']) }}" method="post">
                                @csrf
                                <p>
                                    <input type="text" placeholder="Catatan (optional)" name="description"
                                        id="description">
                                </p>
                                <input type="submit" value="Check Out">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end check out section -->
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
            var selectedCategoryIds = [
                '{{ old('district_id', isset($data['shipping']['destination']) ? $data['shipping']['destination'] : $data['id_destination']) }}',
            ];
            populateSelect2(categorySelector, categoryUrl, selectedCategoryIds, "Pilih Kota / Kabupaten");

        });
    </script>
@endpush
