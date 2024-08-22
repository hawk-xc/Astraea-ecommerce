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
						        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
						          Service Pengiriman
						        </button>
						      </h5>
						    </div>

					      	<div class="card-body">
					       	 <div class="billing-address-form">
					        	<form method="post" action="{{ route('fo.shipping-hampers.update', $data['id_order']) }}">
					        		@method('put')
					        		@csrf
					        		@if(count($data['rajaongkir']) > 0)
									    @foreach($data['rajaongkir'] as $result)
									        <h4>{{ $result['name'] }}</h4>
									        @foreach($result['costs'] as $cost)
												<div class="container mt-4">
												    <div class="form-row">
												        <div class="radio-col">
												            <input type="radio" id="ongkir_{{$loop->index}}" name="ongkir" value="{{$loop->index}}">
												        </div>
												        <label for="ongkir_{{$loop->index}}" class="label-col pt-2">
												            Service : {{ $cost['service'] }} | 
												            Ongkos : Rp. {{ number_format($cost['cost'][0]['value'], 0, ',', '.') }} | 
												            Estimasi : {{ $cost['cost'][0]['etd'] }}
												        </label>
												    </div>
												    <hr>
												</div>
									        @endforeach
									    @endforeach
									@else
									    <p>Tidak dapat menemukan hasil ongkir.</p>
									@endif

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
