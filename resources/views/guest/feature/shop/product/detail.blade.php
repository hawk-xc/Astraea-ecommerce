@extends('guest.layouts.app')

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

    <!-- single product -->
    <div class="single-product mt-150 mb-150">
        <div class="container">
            <div class="row">

                {{-- ejer --}}
                <div class="col-md-5">
                    <div class="single-product-img">
                        @if (isset($data['product']['images'][0]['name']))
                            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel"
                                data-bs-interval="3000">
                                <!-- Carousel indicators -->
                                <div class="carousel-indicators">
                                    @foreach ($data['product']['images'] as $index => $productImages)
                                        <button type="button" data-bs-target="#productCarousel"
                                            data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"
                                            aria-current="{{ $index == 0 ? 'true' : '' }}"
                                            aria-label="Slide {{ $index + 1 }}"></button>
                                    @endforeach
                                </div>

                                <!-- Carousel inner (slides) -->
                                <div class="carousel-inner">
                                    @foreach ($data['product']['images'] as $index => $productImages)
                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                            <img src="{{ asset('storage/' . $productImages['name']) }}"
                                                class="d-block square-image" alt="Product Image" draggable="false">
                                        </div>
                                    @endforeach
                                    <style>
                                        .square-image {
                                            width: 450px;
                                            height: 450px;
                                            object-fit: cover;
                                        }

                                        .carousel-control-prev-icon,
                                        .carousel-control-next-icon {
                                            background-color: rgb(231, 210, 210);
                                            border-radius: 50%;
                                            width: 40px;
                                            height: 40px;
                                        }

                                        .carousel-control-prev,
                                        .carousel-control-next {
                                            opacity: 1;
                                        }
                                    </style>
                                </div>

                                <!-- Carousel controls -->
                                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                </button>
                            </div>
                        @else
                            <img src="{{ asset('guest/img/latest-news/none_image.png') }}" alt="">
                        @endif
                    </div>

                    <!-- Thumbnails (Horizontal Scroll with Drag Support) -->
                    <div class="single-product-allimg" id="thumbnailContainer"
                        style="display: flex; flex-direction: row; overflow-x: auto; white-space: nowrap; cursor: grab;">
                        @foreach ($data['product']['images'] as $index => $productImages)
                            <div class="single-product-sub-img"
                                style="flex: 0 0 auto; margin-right: 10px; z-index:
                        999;">
                                <img src="{{ asset('storage/' . $productImages['name']) }}"
                                    style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                                    data-bs-target="#productCarousel" data-bs-slide-to="{{ $index }}"
                                    draggable="false" />
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- JavaScript for Drag-to-Scroll -->
                <script>
                    const thumbnailContainer = document.getElementById('thumbnailContainer');

                    let isDown = false;
                    let startX;
                    let scrollLeft;

                    thumbnailContainer.addEventListener('mousedown', (e) => {
                        isDown = true;
                        thumbnailContainer.classList.add('active');
                        startX = e.pageX - thumbnailContainer.offsetLeft;
                        scrollLeft = thumbnailContainer.scrollLeft;
                    });

                    thumbnailContainer.addEventListener('mouseleave', () => {
                        isDown = false;
                        thumbnailContainer.classList.remove('active');
                    });

                    thumbnailContainer.addEventListener('mouseup', () => {
                        isDown = false;
                        thumbnailContainer.classList.remove('active');
                    });

                    thumbnailContainer.addEventListener('mousemove', (e) => {
                        if (!isDown) return; // stop the fn from running
                        e.preventDefault();
                        const x = e.pageX - thumbnailContainer.offsetLeft;
                        const walk = (x - startX) * 2; //scroll-fast
                        thumbnailContainer.scrollLeft = scrollLeft - walk;
                    });

                    thumbnailContainer.addEventListener('touchmove', function(e) {
                        const x = e.touches[0].clientX - thumbnailContainer.offsetLeft;
                        thumbnailContainer.scrollLeft = scrollLeft - x;
                    });
                </script>

                {{-- ejraj --}}

                <div class="col-md-7">
                    <div class="single-product-content">
                        <h3>{{ $data['product']['name'] }}</h3>
                        <p class="single-product-pricing">{{ $data['product']->fPrice() }}</p>
                        {!! isset($data['avgrat'])
                            ? '<p class="star-ratingt"><span class="star full">&#9733;</span> ' . $data['avgrat'] . '  </p>'
                            : '' !!}
                        <strong>Stok:</strong> {{ $data['product']['stock'] }}</p>
                        <div class="single-product-form">
                            <form action="{{ route('fo.cart-product.update', $data['product']['id']) }}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="row mb-3">
                                    <input type="number" name="quantity" placeholder="0" class="mr-3 input-angka"
                                        value="1" min="1" required>
                                    <select name="color" id="color" class="mr-3 color-selectore">
                                        {{-- <option value="">Pilih warna</option> --}}
                                        @foreach ($data['product']['colors'] as $color)
                                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row d-flex mb-3">
                                    <button type="submit" class="boxed-btn float-right">
                                        <i class="fas fa-shopping-cart">
                                        </i> Add to Cart
                                    </button>
                                </div>
                            </form>
                            <div class="row">
                                <p><strong>Categories: </strong>{{ $data['product']->categories['name'] }},
                                    {{ $data['product']->subCategories['name'] }}</p>
                            </div>
                        </div>
                        <p>{!! nl2br(e($data['product']['description'])) !!}</p>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col">
                    <!-- Bagian Ulasan Produk -->
                    <div class="product-reviews">
                        @if ($data['avgrat'] != null)
                            <h4>Ulasan Produk</h4>

                            <!-- Ulasan Dummy -->
                            @foreach ($data['ulasans'] as $ulasan)
                                <div class="review mb-3">
                                    <div class="review-header">
                                        <div class="row">
                                            <div class="col">
                                                <h6 class="pt-3">{{ $ulasan['customerData']['name'] }}</h6>
                                            </div>
                                            <div class="col">
                                                <div class="star-rating mx-3">
                                                    {!! displayStars($ulasan['rating']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="review-content">
                                        <p>{{ $ulasan['ulasan'] }} </p>
                                    </div>
                                </div>
                            @endforeach
                            <div class="review mb-3">
                                <div class="review-header">
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="pt-3">{{ $data['ulasans']->links() }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Ulasan Dummy -->
                        @endif
                        @if ($data['cek_beli'] > 0)
                            <!-- Form Input Ulasan -->
                            <div class="review-form mt-3">
                                <form action="{{ route('ulasan.update', $data['product']['id']) }}" method="post"
                                    class="p-4 border rounded bg-light">
                                    @method('put')
                                    @csrf
                                    <div class="form-group">
                                        <label for="rating" class="font-weight-bold">Tambahkan Ulasan</label><br>
                                        <label for="rating" class="font-weight-bold">Rating:</label>
                                        <div class="star-rating">
                                            <?php
                                            $rating = 0;
                                            for ($count = 5; $count >= 1; $count--) {
                                                $checked = $count == $rating ? 'checked' : '';
                                                echo '<input type="radio" id="star' . $count . '" name="rating" value="' . $count . '" ' . $checked . '>';
                                                echo '<label for="star' . $count . '" title="' . $count . ' bintang">&#9733;</label>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="ulasan" class="font-weight-bold">Deskripsi:</label>
                                        <textarea class="form-control" id="ulasan" name="ulasan" rows="4" placeholder="Ulasan"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Kirim</button>
                                </form>
                            </div>
                            <!-- End Form Input Ulasan -->
                        @endif
                    </div>
                    <!-- End Bagian Ulasan Produk -->
                </div>
            </div>

        </div>
    </div>

    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> --}}

    <script src="https://cdn.tailwindcss.com"></script>
    <!-- end single product -->

    <!-- more products -->
    <div class="more-products mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="section-title">
                        <h3><span class="orange-text">Related</span> Products</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($data['related_products'] as $related_product)
                    <div class="col-lg-4 col-md-6 text-center">
                        <div class="single-product-item">
                            <a href="{{ route('shop-product.show', $related_product['name']) }}">
                                <div class="product-image">
                                    @if (isset($related_product['images'][0]['name']))
                                        <img src="{{ asset('storage/' . $related_product['images'][0]['name']) }}"
                                            class="product-img" alt="">
                                    @else
                                        <img src="{{ asset('guest/img/latest-news/none_image.png') }}"
                                            class="product-img" alt="">
                                    @endif
                                </div>
                                <h3>{{ $related_product['name'] }}</h3>
                                <p class="p-4">{!! nl2br(e($related_product->description)) !!}</p>
                                <p class="product-price">{{ $related_product->fPrice() }}</p>
                            </a>
                            <a class="boxed-btn" href="{{ route('shop-product.show', $related_product['name']) }}">Beli
                                sekarang</a>
                            {{-- <form action="{{ route('fo.cart-product.update', $product['name']) }}" method="post">
								@csrf
								@method('PUT')
								<button type="submit" class="boxed-btn">
									<i class="fas fa-shopping-cart">
									</i> Add to Cart
								</button>
							</form> --}}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- end more products -->

    <!-- logo carousel -->
    <div class="logo-carousel-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="logo-carousel-inner">
                        @foreach ($data['partners'] as $partner)
                            <div class="single-logo-item">
                                <a href="{{ route('fo.partner.show', $partner['id']) }}">
                                    <img src="{{ asset('storage/' . $partner['image']) }}" alt="">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end logo carousel -->
@endsection

@push('footer_script')
    <script>
        var smallImages = document.querySelectorAll('.single-product-sub-img');
        smallImages.forEach(function(img) {
            img.addEventListener('click', function() {
                var newSrc = img.querySelector('img').getAttribute('src');
                document.getElementById('single-product-img').setAttribute('src', newSrc);
            });
        });

        document.getElementById('color').addEventListener('change', (event) => {
            const selectedValue = event.target.value;

            // Data yang akan dikirim di body
            const dataToSend = {
                color_id: selectedValue,
                name: "{{ encrypt($data['product']['name']) }}" // Ganti dengan parameter tambahan yang diinginkan
            };

            // URL endpoint
            const url = "{{ route('product.refresh') }}";

            // Melakukan request menggunakan fetch dengan method POST dan body berisi data JSON
            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Menambahkan CSRF token jika diperlukan
                    },
                    body: JSON.stringify(dataToSend)
                })
                .then(response => response.json())
                .then(data => {
                    // Proses data yang diterima dan update data produk di halaman
                    document.getElementById('single-product-img').setAttribute('src',
                        `{{ asset('storage') }}/${data.product.images[0].name}`);
                    document.querySelector('.single-product-content h3').textContent = data.product.name;
                    document.querySelector('.single-product-pricing').textContent = data.product.fPrice;
                    document.querySelector('.star-ratingt').innerHTML = data.avgrat ?
                        `<span class="star full">&#9733;</span> ${data.avgrat}` : '';
                    document.querySelector('.single-product-content p strong').nextSibling.textContent =
                        ` ${data.product.stock}`;
                    document.querySelector('.single-product-content p:last-of-type').textContent = data.product
                        .description;

                    // Update gambar kecil produk
                    const smallImagesContainer = document.querySelector('.single-product-allimg');
                    smallImagesContainer.innerHTML = '';
                    data.product.images.forEach(image => {
                        const imgDiv = document.createElement('div');
                        imgDiv.classList.add('single-product-sub-img');
                        imgDiv.innerHTML = `<img src="{{ asset('storage') }}/${image.name}" alt="">`;
                        smallImagesContainer.appendChild(imgDiv);

                        // Tambahkan event listener untuk mengganti gambar besar saat gambar kecil diklik
                        imgDiv.addEventListener('click', function() {
                            document.getElementById('single-product-img').setAttribute('src',
                                `{{ asset('storage') }}/${image.name}`);
                        });
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('result').innerHTML = 'Error fetching data';
                });
        });

        document.querySelectorAll('.input-angka').forEach(function(input) {
            input.addEventListener('input', function(event) {
                var value = this.value;
                var newValue = '';
                var maximal = {{ $data['product']['stock'] }};

                // Hapus karakter yang bukan angka dari input
                for (var i = 0; i < value.length; i++) {
                    if (!isNaN(parseInt(value[i])) && value[i] !== ' ') {
                        newValue += value[i];
                    }
                }

                // Ubah nilai input menjadi hanya angka
                this.value = newValue;

                if (this.value > maximal) {
                    this.value = maximal;
                    alert('stok tidak mencukupi');
                }
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
    <?php
    function displayStars($rating)
    {
        if ($rating) {
            $rt = $rating;
        } else {
            $rt = 0;
        }
    
        $rating = max(0, min($rt, 5));
    
        $fullStars = floor($rating);
        $emptyStars = 5 - $fullStars;
    
        $starHTML = '<div class="star-ratingt text-center row d-flex flex-row-reverse">';
    
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
