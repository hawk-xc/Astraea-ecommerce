@extends('guest.layouts.app')

@push('header_script')
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

    <!-- cart -->
    <div class="cart-section mt-150 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <div class="cart-table-wrap">
                        <table class="cart-table">
                            <thead class="cart-table-head">
                                <tr class="table-head-row">
                                    <th class=""></th>
                                    <th class="">Name</th>
                                    <th class="">Price</th>
                                    <th class="">Quantity</th>
                                    <th class="">Color</th>
                                    <th class="">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['orders'] as $order)
                                    <tr class="table-body-row">
                                        <td class="product-remove">
                                            <form action="{{ route('fo.cart-product.destroy', $order['id']) }}"
                                                method="post">
                                                @method('delete')
                                                @csrf
                                                <button href="#" class="boxed-btn-delete"><i
                                                        class="fa fa-times"></i></button>
                                            </form>
                                        </td>
                                        <td class="product-name">{{ $order['product_data']['name'] }}</td>
                                        <td class="product-price" id="price_{{ $order['id'] }}">Rp.
                                            {{ number_format($order['product_data']['price'], 0, ',', '.') }}</td>
                                        <td class="product-quantity">
                                            <form action="#">
                                                <div class="form-group" data-order-id="{{ $order['id'] }}">
                                                    <a class="boxed-btn-minus"
                                                        onclick="updateQuantity('{{ $order['id'] }}', -1, {{ \App\Models\ProductColor::where('product_id', $order['product_id'])->where('color_id', $order['color']['id'])->value('count') }})">
                                                        - </a>
                                                    <input type="text" name="quantity"
                                                        id="quantity-input-{{ $order['id'] }}" placeholder="0"
                                                        value="{{ $order['quantity'] }}"
                                                        class="input-angka cart-input-quantity" maxlength="6"
                                                        oninput="updateQuantity('{{ $order['id'] }}', 0, {{ \App\Models\ProductColor::where('product_id', $order['product_id'])->where('color_id', $order['color']['id'])->value('count') }})">
                                                    <a class="boxed-btn-plus"
                                                        onclick="updateQuantity('{{ $order['id'] }}', 1, {{ \App\Models\ProductColor::where('product_id', $order['product_id'])->where('color_id', $order['color']['id'])->value('count') }})">
                                                        + </a>
                                                </div>
                                            </form>
                                        </td>
                                        <td class="product-color">

                                            @isset($order['color'])
                                                {{ $order['color']['name'] }}
                                            @endisset
                                        </td>
                                        <td class="product-total" id="price_sub_total_product_{{ $order['id'] }}">Rp.
                                            {{ number_format($order['sub_total_price'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                                    <td id="hsubtotal">
                                        {{ $data['order']->fSTPrice() !== null ? $data['order']->fSTPrice() : '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="cart-buttons">
                            @auth('customer')
                                <a href="{{ route('product-payment', $data['order']['no_nota']) }}"
                                    class="boxed-btn black">Check Out</a>
                            @else
                                <a href="{{ route('loginf.customer') }}" class="boxed-btn black">Check Out</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end cart -->
@endsection
@push('footer_script')
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
                alert('produk sudah dicheckout');
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

                        // Jika respons gagal dan kuantitas stok tersedia dikirim, reset ke jumlah stok
                        if (input.value > response.available_quantity) {
                            input.value = response.available_quantity;
                            alert('Quantity exceeds stock. Maximum available quantity is ' + response.available_quantity);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var message;
                    var availableQuantity;

                    if (xhr.responseJSON) {
                        message = xhr.responseJSON.message;
                        availableQuantity = xhr.responseJSON.available_quantity;
                    } else if (xhr.responseText) {
                        var response = JSON.parse(xhr.responseText);
                        message = response.message;
                        availableQuantity = response.available_quantity;
                    } else {
                        message = "Unknown error occurred";
                    }

                    if(input.value > availableQuantity) {
                        input.value = availableQuantity;
                    }
                    
                    alert(message);
                }
            });
        }
    </script>
@endpush
