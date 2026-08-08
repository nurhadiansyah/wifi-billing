<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Uniocde -->
    <meta charset="utf-8">
    <!--[if IE]>
    <meta http-equiv="X-UA Compatible" content="IE=edge">
    <![endif]-->
    <!-- First Mobile Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Pgae Description -->
    <meta name="description" content="DreamNet Indonesia">
    <!-- Page Kewords -->
    <meta name="keywords" content="DreamNetIndonesia">

    <!-- Site Author -->
    <meta name="author" content="DreamNetIndonesia">
    <!-- Title -->
    <title>DreamNet Indonesia</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets1/images/favicon.png') }}">
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('assets1/css/bootstrap.min.css') }}" type="text/css">
    <!-- Swiper Slider -->
    <link rel="stylesheet" href="{{ asset('assets1/css/swiper.min.css') }}" type="text/css">
    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('assets1/fonts/fontawesome/font-awesome.min.css') }}">
    <!-- OWL Carousel -->
    <link rel="stylesheet" href="{{ asset('assets1/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets1/css/owl.theme.default.min.css') }}" type="text/css">
    <!-- CSS Animate -->
    <link rel="stylesheet" href="{{ asset('assets1/css/animate.min.css') }}" type="text/css">
    <!-- Style -->
    <link rel="stylesheet" href="{{ asset('assets1/css/style.css') }}" type="text/css">
</head>
<body>
	<!-- Section Preloader -->
	<div id="section-preloader">
		<div class="boxes">
		    <div class="box">
		        <div></div><div></div><div></div><div></div>
		    </div>
		    <div class="box">
		        <div></div><div></div><div></div><div></div>
		    </div>
		    <div class="box">
		        <div></div><div></div><div></div><div></div>
		    </div>
		    <div class="box">
		        <div></div><div></div><div></div><div></div>
		    </div>
		</div>
		<p>LOADING . . .</p>
	</div>
	<!-- /.Section Preloader -->
	<!-- Section Navbar -->
	<nav class="navbar-1 navbar navbar-expand-lg">
        <div class="container navbar-container">
            <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ asset('assets1/images/logo.png') }}" alt="DreamNetIndonesia" style="max-height: 200px; width: auto;"></a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
	                <li class="nav-item">
	                    <a href="{{ url('/') }}" class="nav-link">Home</a>
	                </li>
                </ul>
            </div>
            <a href="{{ route('login') }}" class="btn-1 shadow1 style3 bgscheme">Login</a>
        </div>
    </nav>
	<!-- /.Section Navbar -->

    <div style="min-height: 60vh; padding-top: 100px;">
        @yield('content')
    </div>

	<!-- Section Footer -->
	<div id="section-footer">
		<div class="container">
			<div class="footer-widget">
				<div class="row">
					<div class="left col-md-6">
						<a href="{{ url('/') }}"><img src="{{ asset('assets1/images/logo.png') }}" alt="DreamNetIndonesia" style="max-height: 50px; width: auto;"></a>
					</div>
					<div class="right col-md-6">
						<div class="social-links d-flex align-items-center justify-content-end">
                            <span class="mr-3 text-white"><i class="fa fa-phone"></i> Hubungi Kami: </span>
			                <a href="https://wa.me/6285256486282" title="0852-5648-6282" style="width: auto; padding: 0 10px; font-size: 14px;"><i class="fa fa-whatsapp fa-lg mr-1"></i> 0852-5648-6282</a>
							<a href="mailto:[EMAIL_ADDRESS]" title="support@dreamnetindonesia.com" style="width: auto; padding: 0 10px; font-size: 14px;"><i class="fa fa-mail fa-lg mr-1"></i>support@dreamnetindonesia.com</a>
			            </div>
					</div>
				</div>
			</div>
		</div>
		<div class="footer-copyright container-fluid ">
			<div class="col-12 text-center">
				<p>© {{ date('Y') }} Copyrights <a href="#">CV Laju Bersama Makmur - DreamNet Indonesia</a></p>
				<p class="mt-2" style="font-size: 13px;">
					<a href="{{ route('privacy') }}" style="color: #bbb;" class="mr-3">Kebijakan Privasi</a> | 
					<a href="{{ route('about') }}" style="color: #bbb;" class="mx-3">Tentang Kami</a> | 
					<a href="{{ route('terms') }}" style="color: #bbb;" class="ml-3">Syarat & Ketentuan</a>
				</p>
			</div>
		</div>
	</div>
	<!-- /.Section Footer -->
	
	<!-- Javascript Files -->
	<script src="{{ asset('assets1/js/jquery.min.js') }}"></script>
	<script src="{{ asset('assets1/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('assets1/js/swiper.min.js') }}"></script>
	<script src="{{ asset('assets1/js/scripts.js') }}"></script>
</body>
</html>
