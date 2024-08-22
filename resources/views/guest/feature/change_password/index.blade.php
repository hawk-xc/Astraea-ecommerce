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
	            <div class="col-12">
	                <div class="checkout-accordion-wrap">
	                    <div class="accordion" id="accordionExample">
	                        <!-- Form Login -->
	                        <div class="card single-accordion">
	                            <div class="card-header" id="headingOne">
	                                <h5 class="mb-0">
	                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
	                                        {{ $ref['title'] }}
	                                    </button>
	                                </h5>
	                            </div>

                                <div class="card-body">
                                    <div class="billing-address-form">
                                        <form action="{{ route('change-password.store') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                            	<div class="col-md-6">
		                                            <p>
		                                            	<label for="email">Email</label>
		                                            	<input type="email" id="email" name="email" placeholder="Email">
		                                            </p>
                                            	</div>
                                            	<div class="col-md-6 mt-3">
                                            		<p></p>
		                                            <input type="submit" class="btn btn-primary" value="Send">
                                            	</div>
                                            </div>
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


@endsection