@extends('guest.layouts.app')

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

	<!-- contact form -->
	<div class="contact-from-section mt-150 mb-150">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 mb-5 mb-lg-0">
					<div class="form-title">
						<h2>Have you any question?</h2>
					</div>
				 	<div id="form_status"></div>
					<div class="contact-form">
						<form id="fruitkha-contact" action="{{ route('fo.contact.store') }}" method="POST">
							@csrf
							<p>
								<input type="text" placeholder="Name" name="name" id="name" value="{{ old('name') }}">
								<input type="email" placeholder="Email" name="email" id="email" value="{{ old('email') }}">
							</p>
							<p>
								<input type="tel" placeholder="Phone" name="phone" id="phone" value="{{ old('phone') }}">
								<input type="text" placeholder="Subject" name="subject" id="subject" value="{{ old('subject') }}">
							</p>
							<p><textarea name="message" id="message" cols="30" rows="10" placeholder="Message">{{ old('message') }}</textarea></p>
							<p><input type="submit" value="Submit"></p>
						</form>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="contact-form-wrap">
						<div class="contact-form-box">
							<h4><i class="fas fa-map"></i> Shop Address</h4>
							<p>{{ $data['contact']['address'] }}</p>
						</div>
						<div class="contact-form-box">
							<h4><i class="fas fa-address-book"></i> Contact</h4>
							<p>Phone: {{ $data['contact']['phone_number'] }} <br>
							 Whatsapp: {{ $data['contact']['whatsapp'] }} <br>
							 Email: {{ $data['contact']['email'] }}</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end contact form -->

	<!-- find our location -->
	<div class="find-location blue-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<p> <i class="fas fa-map-marker-alt"></i> Find Our Location</p>
				</div>
			</div>
		</div>
	</div>
	<!-- end find our location -->

	<!-- google map section -->
	<div class="embed-responsive embed-responsive-21by9">
		{!! $data['contact']['maps'] !!}
	</div>
	<!-- end google map section -->
@endsection
	