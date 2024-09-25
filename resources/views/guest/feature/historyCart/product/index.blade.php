@extends('guest.layouts.app')

@push('header_script')
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

    <!-- cart -->
    <div class="cart-section mt-150 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        @if($data['order']['status'] != 'PENDING')
                        <div class="col-md-12 mb-3">
                            <div class="total-section">
                                <table class="total-table">
                                    <thead class="total-table-head">
                                        <tr class="table-total-row">
                                            <th colspan="2">Detail Pengiriman</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="total-data">
                                            <td><strong>Ekspedisi </strong></td>
                                            <td id="hsubtotal">
                                                {{ Str::upper($data['order']['shippingData']['name']) }}
                                            </td>
                                        </tr>
                                        <tr class="total-data">
                                            <td><strong>Berat</strong></td>
                                            <td>
                                                {{ $data['order']['shippingData']['weight'] }} Gram
                                            </td>
                                        </tr>

                                        <tr class="total-data">
                                            <td><strong>Layanan</strong></td>
                                            <td>
                                                {{ $data['order']['shippingData']['service'] }}
                                            </td>
                                        </tr>

                                        <tr class="total-data">
                                            <td><strong>Alamat </strong></td>
                                            <td>
                                                {{ $data['order']['shippingData']->districtData['province'] }}, {{ $data['order']['shippingData']->districtData['type'] }} {{ $data['order']['shippingData']->districtData['name'] }}, <br>
                                                {{ $data['order']['address'] }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($data['order']['status'] == 'PAID')
                        <div class="col-md-12 mb-3">
                            <div class="total-section">
                                <table class="total-table">
                                    <thead class="total-table-head">
                                        <tr class="table-total-row">
                                            <th colspan="2"> Detail Pembayaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="total-data">
                                            <td><strong>Status </strong></td>
                                            <td id="hsubtotal">
                                                {{ $data['order']['status'] }}
                                            </td>
                                        </tr>
                                        <tr class="total-data">
                                            <td><strong>Pada </strong></td>
                                            <td>
                                                {{ $data['order']['paymentData']->paidAt() }}
                                            </td>
                                        </tr>
                                        
                                        <tr class="total-data">
                                            <td><strong>Email </strong></td>
                                            <td>
                                                {{ $data['order']['paymentData']['payer_email'] }}
                                            </td>
                                        </tr>

                                        <tr class="total-data">
                                            <td><strong>Metode Pembayaran </strong></td>
                                            <td>
                                                {{ $data['order']['paymentData']['payment_channel'] }}
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-12 mb-3">
                            <div class="row">
                                <div class="col">
                                    <div class="cart-table-wrap">
                                        <table class="cart-table">
                                            <thead class="cart-table-head">
                                                <tr class="table-head-row">
                                                    @if($data['order']['status'] == 'PENDING')
                                                    <th class=""></th>
                                                    @endif
                                                    <th class="">Name</th>
                                                    <th class="">Price</th>
                                                    <th class="">Quantity</th>
                                                    <th class="">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data['orders'] as $order)
                                                    <tr class="table-body-row">
                                                        @if($data['order']['status'] == 'PENDING')
                                                        <td class="product-remove">
                                                            <form action="{{ route('fo.cart-product.destroy', $order['id']) }}"
                                                                method="post">
                                                                @method('delete')
                                                                @csrf
                                                                <button href="#" class="boxed-btn-delete"><i
                                                                        class="fa fa-times"></i></button>
                                                            </form>
                                                        </td>
                                                        @endif
                                                        <td class="product-name">{{ $order['product_data']['name'] }}</td>
                                                        <td class="product-price" id="price_{{ $order['id'] }}">Rp.
                                                            {{ number_format($order['product_data']['price'], 0, ',', '.') }}</td>
                                                        <td class="product-quantity">
                                                        @if($data['order']['status'] == 'PENDING')
                                                        <form action="#">
                                                            <div class="form-group" data-order-id="{{ $order['id'] }}">
                                                                <a class="boxed-btn-minus"
                                                                    onclick="updateQuantity('{{ $order['id'] }}', -1, {{ $order['product_data']['stock'] }})"> - </a>
                                                                <input type="text" name="quantity"
                                                                    id="quantity-input-{{ $order['id'] }}" placeholder="0"
                                                                    value="{{ $order['quantity'] }}" class="input-angka cart-input-quantity" maxlength="6"
                                                                    oninput="updateQuantity('{{ $order['id'] }}', 0, {{ $order['product_data']['stock'] }})">
                                                                <a class="boxed-btn-plus"
                                                                    onclick="updateQuantity('{{ $order['id'] }}', 1, {{ $order['product_data']['stock'] }})"> + </a>
                                                            </div>
                                                        </form>
                                                        @else
                                                            {{ $order['quantity'] }}
                                                        @endif
                                                        </td>
                                                        <td class="product-total" id="price_sub_total_product_{{ $order['id'] }}">Rp.
                                                            {{ number_format($order['sub_total_price'], 0, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               
            @endif

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
                                    <td id="hsubtotal">
                                        {{ $data['order']->fSTPrice() !== null ? $data['order']->fSTPrice() : '' }}
                                    </td>
                                </tr>
                                @if($data['order']['status'] != 'PENDING')
                                <tr class="total-data">
                                    <td><strong>Shipping </strong></td>
                                    <td>
                                        {{ $data['order']->fShipping() !== null ? $data['order']->fShipping() : '' }}
                                    </td>
                                </tr>

                                <tr class="total-data">
                                    <td><strong>Biaya Aplikasi </strong></td>
                                    <td>
                                        {{ $data['order']->fAdmin() }}
                                    </td>
                                </tr>
                                @if(isset($data['order']['discount_amount']))
                                <tr class="total-data">
                                    <td><strong>Discount </strong></td>
                                    <td>
                                        {{ isset($data['order']['discount_amount']) ? $data['order']['discount_amount'].'%': '0%' }}
                                    </td>
                                </tr>
                                @endif
                                <tr class="total-data">
                                    <td><strong>Total </strong></td>
                                    <td>{{ $data['order']->fTPrice() }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="cart-buttons">
                            @if($data['order']['status'] == 'PENDING')
                                <a href="{{ route('product-payment', $data['order']['no_nota']) }}" class="boxed-btn black">Check Out</a>
                            @elseif($data['order']['status'] == 'UNPAID')
                                <a href="{{ $data['order']['payment_link'] }}" target="__blank" class="boxed-btn black">Payment</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </div>
    <!-- end cart -->

@endsection
@push('footer_script')
    @if($data['order']['status'] == 'PENDING')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        document.querySelectorAll('.input-angka').forEach(function(input) {
            input.addEventListener('input', function(event) {
                var value = this.value;
                var newValue = '';

                // Hapus karakter yang bukan angka dari input
                for (var i = 0; i < value.length; i++) {
                    if (!isNaN(parseInt(value[i])) && value[i] !== ' ') {
                        newValue += value[i];
                    }
                }

                // Ubah nilai input menjadi hanya angka
                this.value = newValue;
            });

            input.addEventListener('keypress', function(event) {
                // Mendapatkan kode tombol dari event
                var key = event.which || event.keyCode;

                // Mengizinkan input hanya jika tombol yang ditekan adalah angka atau tombol kontrol
                if (key < 48 || key > 57) {
                    event.preventDefault();
                }
            });
        });
    </script>

    <script>
        function updateQuantity(orderDetailId, change, maximal) {
            let input = document.getElementById('quantity-input-'.concat(orderDetailId));
            let quantity = parseInt(input.value) || 0;
            quantity += change;

            if (quantity < 1) {
                quantity = 1;
            }

            if (quantity > maximal) {
                quantity = maximal;
                alert('stok tidak mencukupi');
            }

            input.value = quantity;

            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ route('cart-product-update-quantity') }}',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    quantity: quantity,
                    orderDetailId: orderDetailId
                }),
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        // Update the price display
                        $(`#price_${orderDetailId}`).text(response.price);
                        $(`#price_sub_total_product_${orderDetailId}`).text(response.price_sub_total_product);
                        $('#hsubtotal').text(response.price_sub_total);
                    } else {
                        console.error('Failed to update price:', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    var message;
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        var response = JSON.parse(xhr.responseText);
                        message = response.message;
                    } else {
                        message = "Unknown error occurred";
                    }
                    console.log(message);
                    alert(message);
                }
            });
        }
    </script>
    @endif
@endpush
