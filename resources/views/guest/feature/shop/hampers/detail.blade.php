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
				<div class="col-md-5">
					<div class="single-product-img">
						@if(isset($data['product']['images'][0]['name']))
						<img src="{{ asset('storage/' .$data['product']['images'][0]['name']) }}" id="single-product-img" alt="">
						@else
						<img src="{{ asset('guest/img/latest-news/none_image.png') }}" alt="">
						@endif
					</div>
					<div class="single-product-allimg">
						@foreach($data['product']['images'] as $productImages)
							<div class="single-product-sub-img">
								<img src="{{ asset('storage/' .$productImages['name']) }}" alt="">
							</div>
						@endforeach
					</div>
				</div>
				<div class="col-md-7">
					<div class="single-product-content">
						<h3>{{ $data['product']['name'] }}</h3>
						<p class="single-product-pricing">{{ $data['product']->fPrice() }}</p>
						{!! isset($data['avgrat']) ? '<p class="star-ratingt"><span class="star full">&#9733;</span> ' . $data['avgrat'] . '  </p>' : '' !!}
						<strong>Stok:</strong> {{ $data['product']['stock'] }}</p>
						<div class="single-product-form">
							<form action="{{ route('fo.cart-hampers.update', $data['product']['slug']) }}" method="post">
								@csrf
								@method('PUT')
								<input type="number" name="quantity" placeholder="0" class="mr-3 input-angka" value="1" required>
								<button type="submit" class="boxed-btn">
									<i class="fas fa-shopping-cart">
									</i> Add to Cart
								</button>
							</form>
							<p><strong>Categories : </strong>{{ $data['product']->categories['name']}}, {{ $data['product']->subCategories['name']}}</p>
						</div>
						<p>{{ $data['product']['description'] }}</p>
					</div>
				</div>
			</div>
			<div class="row mt-5">
			    <div class="col">
			        <!-- Bagian Ulasan Produk -->
			        <div class="product-reviews">
			            @if($data['avgrat'] != null)
			            <h4>Ulasan Produk</h4>

			            <!-- Ulasan Dummy -->
			            @foreach($data['ulasans'] as $ulasan)
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
			            @if($data['cek_beli'] > 0)
			            <!-- Form Input Ulasan -->
			            <div class="review-form mt-3">
			                <form action="{{ route('ulasan.update', $data['product']['id']) }}" method="post" class="p-4 border rounded bg-light">
				                @method('put')
				                @csrf
				                <div class="form-group">
				                    <label for="rating" class="font-weight-bold">Tambahkan Ulasan</label><br>
				                    <label for="rating" class="font-weight-bold">Rating:</label>
				                    <div class="star-rating">
				                        <?php
				                            $rating = 0;
				                            for ($count = 5; $count >= 1; $count--) {
				                                $checked = ($count == $rating) ? 'checked' : '';
				                                echo '<input type="radio" id="star'.$count.'" name="rating" value="'.$count.'" '.$checked.'>';
				                                echo '<label for="star'.$count.'" title="'.$count.' bintang">&#9733;</label>';
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
				@foreach($data['related_products'] as $related_product)
					<div class="col-lg-4 col-md-6 text-center">
						<div class="single-product-item">
							<a href="{{ route('shop-product.show', $related_product['slug']) }}">
								<div class="product-image">
									@if(isset($related_product['images'][0]['name']))
									<img src="{{ asset('storage/' .$related_product['images'][0]['name']) }}" class="product-img" alt="">
									@else
									<img src="{{ asset('guest/img/latest-news/none_image.png') }}" class="product-img" alt="">
									@endif
								</div>
							</a>
							<h3>{{ $related_product['name']}}</h3>
							<p class="product-price">{{ $related_product->fPrice() }}</p>
							<form action="{{ route('fo.cart-hampers.update', $related_product['slug']) }}" method="post">
								@csrf
								@method('PUT')
								<button type="submit" class="boxed-btn">
									<i class="fas fa-shopping-cart">
									</i> Add to Cart
								</button>
							</form>
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

    document.querySelectorAll('.input-angka').forEach(function(input) {
            input.addEventListener('input', function(event) {
                var value = this.value;
                var newValue = '';
                var maximal = {{ $data['product']['stock']}};

                // Hapus karakter yang bukan angka dari input
                for (var i = 0; i < value.length; i++) {
                    if (!isNaN(parseInt(value[i])) && value[i] !== ' ') {
                        newValue += value[i];
                    }
                }

                // Ubah nilai input menjadi hanya angka
                this.value = newValue;

                if(this.value > maximal )
                {
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
    <?php
	function displayStars($rating) {
	    $rating = max(0, min($rating, 5));

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
</script>
@endpush