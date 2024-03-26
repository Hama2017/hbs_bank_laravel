<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title>HBS BANK</title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="manifest.json" />
    <meta name="csrf-token" content="{{ csrf_token() }}
    <!-- Favicons -->
    <link rel="apple-touch-icon" href="{{ asset('assets/img/favicon180.png') }}" sizes="180x180">
    <link rel="icon" href="{{ asset('assets/img/favicon32.png') }}" sizes="32x32" type="image/png">
    <link rel="icon" href="{{ asset('assets/img/favicon16.png') }}" sizes="16x16" type="image/png">

    <!-- Google fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- swiper carousel css -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/swiperjs-6.6.2/swiper-bundle.min.css') }}">

    <!-- style css for this template -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" id="style">

    <!-- SweetAlert CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.min.css">

</head>

<body class="body-scroll" data-page="index">


    <!-- loader section -->
    <div class="container-fluid loader-wrap">
        <div class="row h-100">
            <div class="col-10 col-md-6 col-lg-5 col-xl-3 mx-auto text-center align-self-center">
                <div class="loader-cube-wrap loader-cube-animate mx-auto">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">
                </div>
                <p class="mt-4">It's time for track budget<br><strong>Please wait...</strong></p>
            </div>
        </div>
    </div>
    <!-- loader section ends -->

    <!-- Sidebar main menu -->
    <!-- Ajoutez ici le contenu de votre sidebar -->

    <!-- Begin page -->
    <main class="h-100">

        <!-- Header -->
         <!-- Header -->
         <header class="header position-fixed">
            <div class="row">
                <div class="col-auto">
                    <a href="{{ route('customer.dashboard') }}"  class="btn btn-light btn-44 menu-btn">
                        <i class="bi bi-house-door"></i>
                    </a>
                </div>
                <div class="col align-self-center text-center">
                    <div class="logo-small">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="">
                        <h5>FiMobile</h5>
                    </div>
                </div>
                <div class="col-auto">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-light btn-44">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>

        </header>
        <!-- Header ends -->

        <!-- main page content -->
        <div class="main-container container">
            @yield('content')
        </div>
        <!-- main page content ends -->

    </main>
    <!-- Page ends-->

    <!-- Footer -->

    @yield('footer')



    <!-- Required jquery and libraries -->
    <script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>

    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap-5/js/bootstrap.bundle.min.js') }}"></script>

    <!-- cookie js -->
    <script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>

    <!-- Customized jquery file  -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/color-scheme.js') }}"></script>

    <!-- PWA app service registration and works -->
    <script src="{{ asset('assets/js/pwa-services.js') }}"></script>

    <!-- Chart js script -->
    <script src="{{ asset('assets/vendor/chart-js-3.3.1/chart.min.js') }}"></script>

    <!-- Progress circle js script -->
    <script src="{{ asset('assets/vendor/progressbar-js/progressbar.min.js') }}"></script>

    <!-- swiper js script -->
    <script src="{{ asset('assets/vendor/swiperjs-6.6.2/swiper-bundle.min.js') }}"></script>

    <!-- page level custom script -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <!-- SweetAlert JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.min.js"></script>

@yield('js')
</html>
