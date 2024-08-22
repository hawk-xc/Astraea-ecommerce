@extends('guest.layouts.app')

@push('header_script')
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="{{ asset('guest/css/style.css') }}" rel="stylesheet" />
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
	            <div class="col-12">
	                <div class="checkout-accordion-wrap">
	                    <div class="accordion" id="accordionExample">
	                        <!-- Form Registrasi -->
							<div class="card single-accordion">
							    <div class="card-header" id="headingTwo">
							        <h5 class="mb-0">
							            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
							                Register
							            </button>
							        </h5>
							    </div>
							   
						        <div class="card-body">
						            <div class="billing-address-form"> <!-- Menyesuaikan kelas CSS -->
						                <form action="{{ route('register.customer') }}" method="POST">     
						                    @csrf
						                    <div class="row">
							                    <div class="col-md-6">
							                    	<p>
								                    	<label for="nama">Nama</label>
								                    	<input type="text" id="nama" name="name" placeholder="Nama" value="{{ old('name') }}" required>
								                    </p> <!-- Input untuk nama lengkap -->
								                    <p>
								                    	<label for="nama_pengguna">Nama pengguna</label>
								                    	<input type="text" id="nama_pengguna" name="username" placeholder="Nama Pengguna" value="{{ old('username') }}"  required>
								                    </p> <!-- Input untuk nama lengkap -->
								                    <p>
								                    	<label for="mail">Email</label>
								                    	<input type="email" id="mail" name="email" placeholder="Email" value="{{ old('email') }}"  required>
								                    </p>
								                    <p>
								                    	<label for="phone">No Telepon</label>
								                    	<input type="text" id="phone" name="phone" class="input-angka" placeholder="No Telepon" value="{{ old('phone') }}"  required>
								                    </p>
								                    <p>
								                    	<label for="wa">No Whatsapp</label>
								                    	<input type="text" id="wa" name="wa" class="input-angka" placeholder="No Whatsapp" value="{{ old('wa') }}"  required>
								                    </p>
								                    <p>
								                    	<label for="tgl_lahir">Tanggal Lahir</label>
								                    	<input type="text" id="tgl_lahir" name="tgl_lahir" class="tanggal-lahir" placeholder="Tanggal Lahir" value="{{ old('tgl_lahir') }}"  required>
								                    </p>
								                    <p>
								                    	<label for="phone">Jenis Kelamin</label>
								                    	<select id="phone" name="jenis_kelamin" class="jankel" placeholder="No Telepon" value="{{ old('phone') }}"  required>
								                    		<option value="" selected disabled>Pilih Jenis Kelamin</option>
								                    		<option value="male">Laki Laki</option>
								                    		<option value="female">Perempuan</option>
								                    	</select>
								                    </p>
							                    </div>
							                    <div class="col-md-6">
								                    <p>
								                    	<label for="district_id">Kabupaten / Kota</label>
								                    	<select name="district_id" id="district_id" class="district_id" required>
								                    	</select>
								                    		
								                    </p>
								                    <p>
								                    	<label for="alamat">Alamat</label>
								                    	<textarea type="text" id="alamat" name="address" placeholder="Alamat" required>{{ old('address') }}</textarea>
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
											        <p>
											        	<span>
											        		* At least one uppercase letter (A-Z)<br>
												            * At least one lowercase letter (a-z)<br>
												            * At least one number (0-9)<br>
												            * At least one special character<br>
												            * Minimum length of 8 characters'
											        	</span>
											        </p>
											        <p>Apakah Anda sudah mempunyai akun? <a href="{{ route('loginf.customer') }}">masuk</a></p>
								                    <input type="submit" class="btn btn-primary" value="Register">
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
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
			flatpickr(".tanggal-lahir", {
		        dateFormat: "Y-m-d",
		        enableTime: false,
		      });
        	$(".jankel").select2();
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
                            $(selector).append('<option value="' + selectedId + '" selected="selected">' + selectedText + '</option>').trigger('change');
                        });
                    }
                });
            }

            // Populate category select2
            var categorySelector = '.district_id';
            var categoryUrl = "{{ route('districs.data')}}";
            var selectedCategoryIds = [ '{{ old('district_id') }}', ];
            populateSelect2(categorySelector, categoryUrl, selectedCategoryIds, "Pilih Kota / Kabupaten");

        });

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