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
                                        <form action="{{ $data['url'] }}" method="POST">
                                        	@method('put')
                                            @csrf
                                            <div class="row">
					                    	<div class="col-md-6">
												<p>
							                    	<label for="password">Password</label>
							                    	<div class="input-group">
							                    	<input type="password" id="password" name="password" placeholder="Password" class="form-control pass-inp-usr" required>
							                    	<span class="toggle-password input-group-text bi bi-eye" onclick="togglePassword('password')"><i></i></span>
											            </div>
							                    </p>
							                    <p>
							                    	<label for="cofmpass">Confirm Password</label>
											        	<div class="input-group">
											            <input type="password" id="cofmpass" class="form-control pass-inp-usr" name="password_confirmation" placeholder="Confirm Password" required>
											            <span class="toggle-password input-group-text bi bi-eye" onclick="togglePassword('cofmpass')"><i></i></span>
											            </div>
											      </p>
					                    	</div>
					                    	<div class="col-md-6 pt-5">
					                    		<p>
										        	<span>
										        		* At least one uppercase letter (A-Z)<br>
											            * At least one lowercase letter (a-z)<br>
											            * At least one number (0-9)<br>
											            * At least one special character<br>
											            * Minimum length of 8 characters'
										        	</span>
										        </p>
							                    <input type="submit" class="btn btn-primary" value="Save">
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

@push('footer_script')
	<script>
		function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleButton = passwordField.nextElementSibling;
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleButton.classList.remove('bi-eye');
                toggleButton.classList.add('bi-eye-slash');
            } else {
                passwordField.type = "password";
                toggleButton.classList.remove('bi-eye-slash');
                toggleButton.classList.add('bi-eye');
            }
        }
    </script>
@endpush