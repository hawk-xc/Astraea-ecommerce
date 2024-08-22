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
	                                        Login
	                                    </button>
	                                </h5>
	                            </div>

                                <div class="card-body">
                                    <div class="billing-address-form">
                                        <form action="{{ route('login.customer') }}" method="POST">
                                            @csrf
                                            <p>
                                            	<label for="email">Email</label>
                                            	<input type="email" id="email" name="email" placeholder="Email">
                                            </p>
                                            <p>
                                            	<label for="passwordl">Password</label>
                                            	<input type="password" id="passwordl" name="password" placeholder="Password">
                                            </p>
                                            <p><a href="{{ route('change-password.index') }}">Lupa Password</a> | Apakah Anda belum mempunyai akun? <a href="{{ route('registerf.customer') }}">mendaftar</a></p>
                                            <input type="submit" class="btn btn-primary" value="Login">
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