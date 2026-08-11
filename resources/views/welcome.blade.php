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
    <meta name="description" content="Appcraft portfolio Template">
    <!-- Page Kewords -->
    <meta name="keywords" content="DreamNetIndonesia">

    <!-- Site Author -->
    <meta name="author" content="DreamNetIndonesia">
    <!-- Title -->
    <title>Home 1 | DreamNetIndonesia</title>
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
		        <div></div>
		        <div></div>
		        <div></div>
		        <div></div>
		    </div>
		    <div class="box">
		        <div></div>
		        <div></div>
		        <div></div>
		        <div></div>
		    </div>
		    <div class="box">
		        <div></div>
		        <div></div>
		        <div></div>
		        <div></div>
		    </div>
		    <div class="box">
		        <div></div>
		        <div></div>
		        <div></div>
		        <div></div>
		    </div>
		</div>
		<p>LOADING . . .</p>
	</div>
	<!-- /.Section Preloader -->
	<!-- Section Navbar -->
	<nav class="navbar-1 navbar navbar-expand-lg">
        <div class="container navbar-container">
            <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ asset('assets1/images/logo.png') }}" alt="DreamNetIndonesia" style="width: 150px; height: auto;"></a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                	<li class="nav-item dropdown-submenu dropdown">
                        <a class="dropdown-item dropdown-toggle nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Home
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('/') }}" class="dropdown-item">Homepage 1</a></li>
                            <li><a href="#" class="dropdown-item">Homepage 2</a></li>
                        </ul>
                    </li>
	                <li class="nav-item">
	                    <a href="#section-features1" class="nav-link scroll-down">Features</a>
	                </li>
	                <li class="nav-item">
	                    <a href="#section-pricing1" class="nav-link scroll-down">Pricing</a>
	                </li>
	                <li class="nav-item dropdown-submenu dropdown">
                        <a class="dropdown-item dropdown-toggle nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            News
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#" class="dropdown-item">Blog List</a></li>
                            <li><a href="#" class="dropdown-item">Blog Detail</a></li>
                        </ul>
                    </li>
	                <li class="nav-item">
	                    <a href="#" class="nav-link">Contact</a>
	                </li>
                </ul>
            </div>
            <a href="{{ route('login') }}" class="btn-1 shadow1 style3 bgscheme">Login</a>
            <button type="button" id="sidebarCollapse" class="navbar-toggler active" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="true" aria-label="Toggle navigation">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
        <!-- container -->
    </nav>
	<!-- /.Section Navbar -->
	<!-- Section Slider 1 -->
    <div id="section-slider1">
	  	<div class="swiper-container">
		    <div class="swiper-wrapper d-none">
		    	<!-- Item -->
				<div class="swiper-slide">
					<div class="slider-content">
						<div class="container">
							<div class="row">
								<div class="left col-12 col-sm-12 col-md-7">
									<h1 class="ez-animate" data-animation="fadeInLeft">Internet Cepat & Tanpa Batas dari DreamNet.</h1>
									<p class="ez-animate" data-animation="fadeInLeft">Nikmati koneksi internet unlimited yang stabil dan terjangkau untuk kebutuhan rumah dan bisnis Anda. Bersama CV Laju Bersama Makmur, kami hadir memberikan solusi konektivitas terbaik!</p>
								</div>
								<div class="right ez-animate col-12 col-sm-12 col-md-5" data-animation="fadeInRight">
									
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /.Item -->
		    </div>
		</div>
	</div>
	<!-- /.Section Slider 1 -->
	<!-- Section Features 1 -->
	<div id="section-features1">
		<div class="container">
			<div class="row">
				<div class="left">
					<h6 class="clscheme">Fitur Unggulan</h6>
					<h2>Mengapa Memilih DreamNet?</h2>
					<ul>
						<li><i class="fa fa-long-arrow-left clscheme"></i></li>
						<li><i class="fa fa-long-arrow-right clscheme"></i></li>
					</ul>
				</div>
				<div class="right">
					<div class="swiper-container features1">
						<div class="swiper-wrapper">
							<!-- Item -->
							<div class="swiper-slide">
								<div class="item">
									<img src="{{ asset('assets1/images/img-icon1.png') }}" alt="Appcraft">
									<h3>Kecepatan Stabil</h3>
									<p>Koneksi internet cepat dan stabil tanpa hambatan, cocok untuk kebutuhan streaming, bekerja, hingga bermain game online.</p>
								</div>
							</div>
							<!-- /.Item -->
							<!-- Item -->
							<div class="swiper-slide">
								<div class="item">
									<img src="{{ asset('assets1/images/img-icon2.png') }}" alt="Appcraft">
									<h3>Tanpa Batas Kuota</h3>
									<p>Internet benar-benar unlimited tanpa FUP (Fair Usage Policy). Nikmati akses sebebasnya untuk seluruh keluarga Anda setiap hari.</p>
								</div>
							</div>
							<!-- /.Item -->
							<!-- Item -->
							<div class="swiper-slide">
								<div class="item">
									<img src="{{ asset('assets1/images/img-icon3.png') }}" alt="Appcraft">
									<h3>Dukungan 24/7</h3>
									<p>Tim teknisi profesional kami selalu siap membantu dan merespons keluhan Anda kapan saja secara cepat jika terjadi gangguan koneksi.</p>
								</div>
							</div>
							<!-- /.Item -->
							<!-- Item -->
							<div class="swiper-slide">
								<div class="item">
									<img src="{{ asset('assets1/images/img-icon1.png') }}" alt="Appcraft">
									<h3>Harga Terjangkau</h3>
									<p>Berbagai pilihan paket internet dengan harga kompetitif dan jujur tanpa ada biaya tersembunyi yang memberatkan Anda.</p>
								</div>
							</div>
							<!-- /.Item -->
							<!-- Item -->
							<div class="swiper-slide">
								<div class="item">
									<img src="{{ asset('assets1/images/img-icon2.png') }}" alt="Appcraft">
									<h3>Instalasi Cepat</h3>
									<p>Proses pemasangan perangkat dan aktivasi internet yang cepat sehingga Anda bisa segera menikmati koneksi dari DreamNet.</p>
								</div>
							</div>
							<!-- /.Item -->
							<!-- Item -->
							<div class="swiper-slide">
								<div class="item">
									<img src="{{ asset('assets1/images/img-icon3.png') }}" alt="Appcraft">
									<h3>Jangkauan Luas</h3>
									<p>Infrastruktur jaringan yang terus berkembang untuk memastikan koneksi yang lancar hingga ke berbagai wilayah dan pelosok.</p>
								</div>
							</div>
							<!-- /.Item -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.Section Features 1 -->

	<!-- Section Pricingn 1 -->
	<div id="section-pricing1">
		<div class="container">
			<div class="row">
				<div class="title1 col-12">
					<h6 class="clscheme">PRICING</h6>
					<h2>Built for your projects</h2>
				</div>
				<!-- Item -->
				<div class="item ez-animate col-md-6 col-lg-3 mb-4" data-animation="fadeInLeft">
					<div class="label bgsolidscheme">HEMAT</div>
					<div class="title">10 Mbps</div>
					<ul>
						<li>Internet Unlimited</li>
						<li>Stabil & Cepat</li>
						<li>Cocok untuk 2-3 Perangkat</li>
						<li>Browsing Lancar</li>
						<li>Bantuan 24/7</li>
					</ul>
					<div class="price clscheme"><span class="currency">Rp</span> 150 <span class="duration">Rb/bln</span></div>
					<div class="cta">
						<a href="https://wa.me/6285256486282?text=Halo%20Admin%20DreamNet,%20saya%20tertarik%20berlangganan%20paket%2010%20Mbps" class="btn-1 shadow1 style3 bgscheme">Daftar</a>
					</div>
				</div>
				<!-- /.Item -->
				<!-- Item -->
				<div class="item ez-animate active selected col-md-6 col-lg-3 mb-4" data-animation="fadeInUp">
					<div class="label bgsolidscheme">TERLARIS</div>
					<div class="title">15 Mbps</div>
					<ul>
						<li>Internet Unlimited</li>
						<li>Stabil & Cepat</li>
						<li>Cocok untuk 4-5 Perangkat</li>
						<li>Streaming Lancar</li>
						<li>Bantuan 24/7</li>
					</ul>
					<div class="price clscheme"><span class="currency">Rp</span> 180 <span class="duration">Rb/bln</span></div>
					<div class="cta">
						<a href="https://wa.me/6285256486282?text=Halo%20Admin%20DreamNet,%20saya%20tertarik%20berlangganan%20paket%2015%20Mbps" class="btn-1 shadow1 style3 bgscheme">Daftar</a>
					</div>
				</div>
				<!-- /.Item -->
				<!-- Item -->
				<div class="item ez-animate col-md-6 col-lg-3 mb-4" data-animation="fadeInRight">
					<div class="label bgsolidscheme">KELUARGA</div>
					<div class="title">20 Mbps</div>
					<ul>
						<li>Internet Unlimited</li>
						<li>Stabil & Cepat</li>
						<li>Keluarga Besar</li>
						<li>Mendukung Smart TV</li>
						<li>Bantuan 24/7</li>
					</ul>
					<div class="price clscheme"><span class="currency">Rp</span> 230 <span class="duration">Rb/bln</span></div>
					<div class="cta">
						<a href="https://wa.me/6285256486282?text=Halo%20Admin%20DreamNet,%20saya%20tertarik%20berlangganan%20paket%2020%20Mbps" class="btn-1 shadow1 style3 bgscheme">Daftar</a>
					</div>
				</div>
				<!-- /.Item -->
				<!-- Item -->
				<div class="item ez-animate col-md-6 col-lg-3 mb-4" data-animation="fadeInRight">
					<div class="label bgsolidscheme">PREMIUM</div>
					<div class="title">30 Mbps</div>
					<ul>
						<li>Internet Unlimited</li>
						<li>Sangat Cepat</li>
						<li>Banyak Perangkat</li>
						<li>Gaming & Streaming 4K</li>
						<li>Bantuan Prioritas</li>
					</ul>
					<div class="price clscheme"><span class="currency">Rp</span> 250 <span class="duration">Rb/bln</span></div>
					<div class="cta">
						<a href="https://wa.me/6285256486282?text=Halo%20Admin%20DreamNet,%20saya%20tertarik%20berlangganan%20paket%2030%20Mbps" class="btn-1 shadow1 style3 bgscheme">Daftar</a>
					</div>
				</div>
				<!-- /.Item -->
			</div>
		</div>
	</div>
	<!-- /.Section Pricingn 1 -->
	<!-- Section Testimonial 1 -->
	<!-- /.Section Testimonial 1 -->
	<!-- Section Subscribe 1 -->
	<div id="section-subscribe1">
		<div class="container">
			<div class="row">
				<div class="title1 col-12">
					<h6 class="clscheme">Hubungi Kami</h6>
					<h2>Hubungi kami untuk lebih lanjut</h2>
				</div>
				<div class="col-12 ez-animate text-center" data-animation="fadeInUp">
					<a href="https://wa.me/6285256486282?text=Halo%20Admin%20DreamNet,%20saya%20ingin%20bertanya%20informasi%20lebih%20lanjut" class="btn-1 shadow1 style3 bgscheme d-inline-flex align-items-center justify-content-center" style="font-size: 18px; padding: 15px 30px; border-radius: 30px; margin-top: 20px; color: #fff; text-decoration: none;">
                        <i class="fa fa-whatsapp fa-lg mr-2"></i> Pesan WhatsApp Admin
                    </a>
				</div>
			</div>
		</div>
	</div>
	<!-- /.Section Subscribe 1 -->
	<!-- Section Download 1 -->

	<!-- /.Section Download 1 -->
	<!-- Section Footer -->
	<div id="section-footer" style="background: #0f172a !important; padding: 60px 0 20px 0; color: #fff; position: relative; z-index: 10;">
		<div class="container">
			<div class="row mb-5">
				<!-- Column 1: Brand & Info -->
				<div class="col-lg-5 col-md-12 mb-5 mb-lg-0">
					<div class="d-flex align-items-center mb-3">
						<div style="background-color: #0ea5e9; border-radius: 8px; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
							<i class="fa fa-wifi" style="font-size: 20px; color: white;"></i>
						</div>
						<h4 class="mb-0 text-white" style="font-weight: 700;">Dreamnet Indonesia</h4>
					</div>
					<p style="color: #cbd5e1; font-size: 14px; line-height: 1.8; margin-bottom: 25px; padding-right: 20px;">
						Penyedia layanan internet terpercaya dengan teknologi fiber optik terdepan.
						Memberikan koneksi stabil dan kecepatan tinggi untuk kebutuhan digital Anda.
					</p>
					<div class="d-flex">
						<a href="#" style="width: 36px; height: 36px; background-color: #14b8a6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px; text-decoration: none; transition: 0.3s;"><i class="fa fa-facebook"></i></a>
						<a href="#" style="width: 36px; height: 36px; background-color: #14b8a6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px; text-decoration: none; transition: 0.3s;"><i class="fa fa-twitter"></i></a>
						<a href="#" style="width: 36px; height: 36px; background-color: #14b8a6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px; text-decoration: none; transition: 0.3s;"><i class="fa fa-instagram"></i></a>
						<a href="https://wa.me/6285256486282" style="width: 36px; height: 36px; background-color: #22c55e; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px; text-decoration: none; transition: 0.3s;"><i class="fa fa-whatsapp"></i></a>
					</div>
				</div>

				<!-- Column 2: Layanan -->
				<div class="col-lg-3 col-md-6 mb-5 mb-md-0">
					<h5 class="text-white mb-4" style="font-weight: 600;">Layanan</h5>
					<ul class="list-unstyled" style="line-height: 2.5;">
						<li><a href="#" style="color: #cbd5e1; text-decoration: none; font-size: 14px; transition: 0.3s;">Internet Rumah</a></li>
						<li><a href="#" style="color: #cbd5e1; text-decoration: none; font-size: 14px; transition: 0.3s;">Internet Bisnis</a></li>
						<li><a href="#" style="color: #cbd5e1; text-decoration: none; font-size: 14px; transition: 0.3s;">Dedicated Line</a></li>
						<li><a href="#" style="color: #cbd5e1; text-decoration: none; font-size: 14px; transition: 0.3s;">Hosting & Domain</a></li>
					</ul>
				</div>

				<!-- Column 3: Kontak -->
				<div class="col-lg-4 col-md-6">
					<h5 class="text-white mb-4" style="font-weight: 600;">Kontak</h5>
					<ul class="list-unstyled" style="line-height: 2.5;">
						<li style="color: #cbd5e1; font-size: 14px; display: flex; align-items: center;">
							<i class="fa fa-phone" style="color: #14b8a6; width: 25px;"></i> +62 085-256-486-282
						</li>
						<li style="color: #cbd5e1; font-size: 14px; display: flex; align-items: center;">
							<i class="fa fa-envelope" style="color: #14b8a6; width: 25px;"></i> support@dreamnetindonesia.com
						</li>
						<li style="color: #cbd5e1; font-size: 14px; display: flex; align-items: center;">
							<i class="fa fa-map-marker" style="color: #14b8a6; width: 25px;"></i> Makassar, Indonesia
						</li>
						<li style="color: #cbd5e1; font-size: 14px; display: flex; align-items: center;">
							<i class="fa fa-whatsapp" style="color: #22c55e; width: 25px;"></i> +62 085-256-486-282
						</li>
					</ul>
				</div>
			</div>

			<div style="border-top: 1px solid #1e293b; padding-top: 25px; display: flex; justify-content: space-between; flex-wrap: wrap;">
				<p style="color: #94a3b8; font-size: 13px; margin-bottom: 10px;">
					© {{ date('Y') }} Bayar Internet. All rights reserved.
				</p>
				<div style="font-size: 13px;">
					<a href="{{ route('privacy') }}" style="color: #94a3b8; text-decoration: none; margin-right: 15px; transition: 0.3s;">Privacy Policy</a>
					<a href="{{ route('terms') }}" style="color: #94a3b8; text-decoration: none; transition: 0.3s;">Terms of Service</a>
				</div>
			</div>
		</div>
	</div>
	<!-- /.Section Footer -->
	
	<!-- Javascript Files -->
	<script src="{{ asset('assets1/js/jquery.min.js') }}"></script>
	<!-- Bootstrap -->
	<script src="{{ asset('assets1/js/bootstrap.min.js') }}"></script>
	<!-- Swiper Slider -->
	<script src="{{ asset('assets1/js/swiper.min.js') }}"></script>
	<!-- OWL Carousel -->
	<script src="{{ asset('assets1/js/owl.carousel.min.js') }}"></script>
	<!-- Waypoint -->
	<script src="{{ asset('assets1/js/jquery.waypoints.min.js') }}"></script>
	<!-- Easy Waypoint -->
	<script src="{{ asset('assets1/js/easy-waypoint-animate.js') }}"></script>
	<!-- Scripts -->
	<script src="{{ asset('assets1/js/scripts.js') }}"></script>
	<!-- Carousel Features 1 -->
	<script src="{{ asset('assets1/js/carousel-features1.js') }}"></script>
	<!-- Carousel App Screen 1 -->
	<script src="{{ asset('assets1/js/carousel-appscreen1.js') }}"></script>
	<!-- Carousel Testimonial 1 -->
	<script src="{{ asset('assets1/js/carousel-testimonial1.js') }}"></script>

</body>
</html>
