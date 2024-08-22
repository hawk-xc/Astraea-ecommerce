<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Responsive Bootstrap4 Shop Template, Created by Imran Hossain from https://imransdesign.com/">

	<!-- title -->
	<title>Astraea | {{ $ref['title'] }}</title>

	<!-- favicon -->
	<link rel="shortcut icon" type="image/png" href="{{ asset('admin/img/logo.webp') }}">
	<!-- google font -->
	<!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet"> -->
	<!-- fontawesome -->
	<link rel="stylesheet" href="{{ asset('guest/css/all.min.css') }}">
	<!-- bootstrap -->
	<link rel="stylesheet" href="{{ asset('guest/bootstrap/css/bootstrap.min.css') }}">
	<!-- owl carousel -->
	<link rel="stylesheet" href="{{ asset('guest/css/owl.carousel.css') }}">
	<!-- magnific popup -->
	<link rel="stylesheet" href="{{ asset('guest/css/magnific-popup.css') }}">
	<!-- animate css -->
	<link rel="stylesheet" href="{{ asset('guest/css/animate.css') }}">
	<!-- mean menu css -->
	<link rel="stylesheet" href="{{ asset('guest/css/meanmenu.min.css') }}">
	<!-- main style -->
	<link rel="stylesheet" href="{{ asset('guest/css/main.css') }}">
	<!-- responsive -->
	<link rel="stylesheet" href="{{ asset('guest/css/responsive.css') }}">
	<link rel="stylesheet" href="{{ asset('guest/css/style.css') }}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- CSS Files -->
    <script src="https://code.jquery.com/jquery-3.6.1.js"></script>
	@stack('header_script')

</head>
<body>
	
	<!--PreLoader-->
    <div class="loader">
        <div class="loader-inner">
            <div class="circle"></div>
        </div>
    </div>
    <!--PreLoader Ends-->
	
	<!-- header -->
	@include('guest.layouts.header.header')
	<!-- end header -->

	<!-- content page -->
	@yield('content')
	<!-- end content page -->

	<!-- footer -->
	@include('guest.layouts.footer.footer')
	<!-- end footer -->
	
	<!-- jquery -->
	<script src="{{ asset('guest/js/jquery-1.11.3.min.js') }}"></script>
	<!-- bootstrap -->
	<script src="{{ asset('guest/bootstrap/js/bootstrap.min.js') }}"></script>
	<!-- count down -->
	<script src="{{ asset('guest/js/jquery.countdown.js') }}"></script>
	<!-- isotope -->
	<script src="{{ asset('guest/js/jquery.isotope-3.0.6.min.js') }}"></script>
	<!-- waypoints -->
	<script src="{{ asset('guest/js/waypoints.js') }}"></script>
	<!-- owl carousel -->
	<script src="{{ asset('guest/js/owl.carousel.min.js') }}"></script>
	<!-- magnific popup -->
	<script src="{{ asset('guest/js/jquery.magnific-popup.min.js') }}"></script>
	<!-- mean menu -->
	<script src="{{ asset('guest/js/jquery.meanmenu.min.js') }}"></script>
	<!-- sticker js -->
	<script src="{{ asset('guest/js/sticker.js') }}"></script>
	<!-- main js -->
	<script src="{{ asset('guest/js/main.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	
	@stack('footer_script')
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('toast_success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'success',
                title: '{{ session('toast_success') }}'
            });
        @endif

        @if(session('toast_warning'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'warning',
                title: '{{ session('toast_warning') }}'
            });
        @endif
    </script>

    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                setTimeout(function(){
                    const Toast = Swal.mixin({
                          toast: true,
                          position: 'top-end',
                          showConfirmButton: false,
                          timer: 3000,
                          timerProgressBar: true,
                          didOpen: (toast) => {
                              toast.addEventListener('mouseenter', Swal.stopTimer)
                              toast.addEventListener('mouseleave', Swal.resumeTimer)
                          }
                    });
                    Toast.fire({
                        icon: 'warning',
                        title: 'Terjadi kesalahan',
                        text: '{{ $error }}\n'
                    });
                }, {{ $loop->index * 3000 }}); 
            @endforeach
        </script>
    @endif
</body>
</html>