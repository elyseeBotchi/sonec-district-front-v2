<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="BENI Messan">

    <!-- ========== Page Title ========== -->
    <title>{{ env('APP_NAME') }} -  </title>

    <!-- ========== Favicon Icon ========== -->
    <link rel="shortcut icon" href="{{ asset('template/assets/images/logo-icon.png') }}" type="image/x-icon">

    <!-- ========== Start Stylesheet ========== -->
        <link href="{{ asset('template/front/assets/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('template/front/assets/css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('template/front/assets/css/magnific-popup.css') }}" rel="stylesheet">
        <link href="{{ asset('template/front/assets/css/swiper-bundle.min.css') }}" rel="stylesheet">
        <link href="{{ asset('template/front/assets/css/animate.min.css') }}" rel="stylesheet">
        <link href="{{ asset('template/front/assets/css/validnavs.css') }}" rel="stylesheet">
        <link href="{{ asset('template/front/assets/css/helper.css') }}" rel="stylesheet">
        <link href="{{ asset('template/front/assets/css/unit-test.css') }}" rel="stylesheet">
        <link href="{{ asset('template/front/assets/css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('template/front/style.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <!-- ========== End Stylesheet ========== -->

</head>

<body>

    <!-- Start Preloader 
    ============================================= -->
    <div id="preloader">
        <div id="gixus-preloader" class="gixus-preloader">
            <div class="animation-preloader">
                <div class="spinner"></div>
                <div class="txt-loading">
                    <span data-text-preloader="T" class="letters-loading">
                        T
                    </span>
                    <span data-text-preloader="-" class="letters-loading">
                        -
                    </span>
                    <span data-text-preloader="C" class="letters-loading">
                        C
                    </span>
                    <span data-text-preloader="O" class="letters-loading">
                        O
                    </span>
                    <span data-text-preloader="N" class="letters-loading">
                        N
                    </span>
                    <span data-text-preloader="N" class="letters-loading">
                        N
                    </span>
                    <span data-text-preloader="E" class="letters-loading">
                        E
                    </span>
                    <span data-text-preloader="C" class="letters-loading">
                        C
                    </span>
                    <span data-text-preloader="T" class="letters-loading">
                        T
                    </span>
                </div>
            </div>
            <div class="loader">
                <div class="row">
                    <div class="col-3 loader-section section-left">
                        <div class="bg"></div>
                    </div>
                    <div class="col-3 loader-section section-left">
                        <div class="bg"></div>
                    </div>
                    <div class="col-3 loader-section section-right">
                        <div class="bg"></div>
                    </div>
                    <div class="col-3 loader-section section-right">
                        <div class="bg"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Preloader -->


    <!-- Header 
    ============================================= -->
    <header>
        <!-- Start Navigation -->
        <nav class="navbar mobile-sidenav navbar-sticky navbar-default validnavs navbar-fixed white no-background">

            <div class="container d-flex justify-content-between align-items-center">            
                

                <!-- Start Header Navigation -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                        <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="{{ route('welcome.index') }}">
                        <img src="{{ asset('template/assets/images/logo.png') }}" class="logo logo-display" alt="Logo">
                        <img src="{{ asset('template/assets/images/logo.png') }}" class="logo logo-scrolled" alt="Logo">
                    </a>
                </div>
                <!-- End Header Navigation -->

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-menu">

                    {{-- <div class="collapse-header">
                        <img src="assets/img/logo.png" alt="Logo">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    
                    <ul class="nav navbar-nav navbar-center" data-in="fadeInDown" data-out="fadeOutUp">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle active" data-toggle="dropdown" >Home</a>
                            <ul class="dropdown-menu">
                                <li><a href="index.html">Business Consultant</a></li>
                                <li><a href="index-2.html">It Solutions</a></li>
                                <li><a href="index-3.html">Creative Agency</a></li>
                                <li><a href="index-4.html">Transport & Logistics</a></li>
                                <li><a href="index-5.html">Financial Advisor</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" >Pages</a>
                            <ul class="dropdown-menu">
                                <li><a href="about-us.html">About Us</a></li>
                                <li><a href="about-us-2.html">About Us Two</a></li>
                                <li><a href="team.html">Team</a></li>
                                <li><a href="team-2.html">Team Two</a></li>
                                <li><a href="team-details.html">Team Details</a></li>
                                <li><a href="pricing.html">Pricing</a></li>
                                <li><a href="faq.html">FAQ</a></li>
                                <li><a href="contact-us.html">Contact Us</a></li>
                                <li><a href="404.html">Error Page</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="project.html" class="dropdown-toggle" data-toggle="dropdown" >Projects</a>
                            <ul class="dropdown-menu">
                                <li><a href="project.html">Project style one</a></li>
                                <li><a href="project-2.html">Project style two</a></li>
                                <li><a href="project-3.html">Project style two</a></li>
                                <li><a href="project-details.html">Project Details</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" >Services</a>
                            <ul class="dropdown-menu">
                                <li><a href="services.html">Services Version One</a></li>
                                <li><a href="services-2.html">Services Version Two</a></li>
                                <li><a href="services-3.html">Services Version Three</a></li>
                                <li><a href="services-details.html">Services Details</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" >Blog</a>
                            <ul class="dropdown-menu">
                                <li><a href="blog-standard.html">Blog Standard</a></li>
                                <li><a href="blog-with-sidebar.html">Blog With Sidebar</a></li>
                                <li><a href="blog-2-colum.html">Blog Grid Two Colum</a></li>
                                <li><a href="blog-3-colum.html">Blog Grid Three Colum</a></li>
                                <li><a href="blog-single.html">Blog Single</a></li>
                                <li><a href="blog-single-with-sidebar.html">Blog Single With Sidebar</a></li>
                            </ul>
                        </li>
                        <li><a href="contact-us.html">contact</a></li>
                    </ul> --}}
                </div><!-- /.navbar-collapse -->

                <div class="attr-right">
                    <!-- Start Atribute Navigation -->
                    <div class="attr-nav">
                        <ul>
                            <li class="contact">
                                <div class="call">
                                    <div class="icon">
                                        <i class="fas fa-comments-alt-dollar"></i>
                                    </div>
                                    <div class="info">
                                        <p>Vous avez un compte ?</p>
                                        <h5><a href="{{ route('login') }}">connectez-vous</a></h5>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- End Atribute Navigation -->
                </div>

            </div>   
            <!-- Overlay screen for menu -->
            <div class="overlay-screen"></div>
            <!-- End Overlay screen for menu -->
        </nav>
        <!-- End Navigation -->
    </header>
    <!-- End Header -->
    @yield('content')
    <!-- Start Footer 
    ============================================= -->
    <footer class="bg-gray overflow-hidden">
        @isset($lock)
            <div class="container">
                <div class="f-items default-padding">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 footer-item pr-30 pr-md-15 pr-xs-15">
                            <div class="f-item address">
                                <img src="assets/img/logo.png" alt="Image Not Found">
                                <p>
                                    Excellence decisively nay man twins impression maximum contrasted remarkably is perfect.
                                </p>
                                <ul class="footer-social">
                                    <li>
                                        <a href="#">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fab fa-youtube"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="contact-address">
                                    <li>
                                        <p>Our Location</p>
                                        <h4>175 10h Street, Office 375 Berlin, Devolina 21562</h4>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 footer-item">
                            <div class="f-item link">
                                <h4 class="widget-title">Quick Links</h4>
                                <ul>
                                    <li>
                                        <a href="about-us.html">Compnay Profile</a>
                                    </li>
                                    <li>
                                        <a href="contact-us.html">Help Center</a>
                                    </li>
                                    <li>
                                        <a href="about-us.html">Career</a>
                                    </li>
                                    <li>
                                        <a href="pricing.html">Plans & Pricing</a>
                                    </li>
                                    <li>
                                        <a href="blog-standard.html">News & Blog</a>
                                    </li>
                                    <li>
                                        <a href="contact-us.html">Contact</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @isset($lock)
                        <div class="col-lg-2 col-md-6 footer-item">
                            <div class="f-item link">
                                <h4 class="widget-title">Our Services</h4>
                                <ul>
                                    <li>
                                        <a href="services-details.html">Manage investment</a>
                                    </li>
                                    <li>
                                        <a href="services-details.html">Email Marketing</a>
                                    </li>
                                    <li>
                                        <a href="services-details.html">Growth Hacking</a>
                                    </li>
                                    <li>
                                        <a href="services-details.html">Lead Generation</a>
                                    </li>
                                    <li>
                                        <a href="services-details.html">Offline SEO</a>
                                    </li>
                                    <li>
                                        <a href="#">Manage investment</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 footer-item">
                            <div class="f-item newsletter">
                                <h4 class="widget-title">Newsletter</h4>
                                <p>
                                    Join our subscribers list to get the latest <br> news and special offers.
                                </p>
                                <form action="#">
                                    <input type="email" placeholder="Your Email" class="form-control" name="email">
                                    <button type="submit"> <i class="fa fa-paper-plane"></i></button>  
                                </form>
                                <fieldset>
                                    <input type="checkbox" id="privacy" name="privacy">
                                    <label for="privacy">I agree to the Privacy Policy</label>
                                </fieldset>
                            </div>
                        </div>
                        @endisset
                    </div>
                </div>
            </div>
        @endisset
        <!-- Start Footer Bottom -->
        <div class="footer-bottom bg-dark text-light">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <p> © {{ env('APP_NAME') }} All Rights Reserved. </p>
                    </div>
                    <div class="col-lg-6 text-end">
                        <ul class="link-list">
                            <li>
                                <a href="#">Terms</a>
                            </li>
                            <li>
                                <a href="#">Privacy</a>
                            </li>
                            <li>
                                <a href="#">Support</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Footer Bottom -->
    </footer>
    <!-- End Footer -->
    
    <!-- jQuery Frameworks
    ============================================= -->
    <script src="{{ asset('template/front/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('template/front/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/front/assets/js/jquery.appear.js') }}"></script>
    <script src="{{ asset('template/front/assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('template/front/assets/js/progress-bar.min.js') }}"></script>
    <script src="{{ asset('template/front/assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('template/front/assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('template/front/assets/js/magnific-popup.min.js') }}"></script>
    <script src="{{ asset('template/front/assets/js/count-to.js') }}"></script>
    <script src="{{ asset('template/front/assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('template/front/assets/js/circle-progress.js') }}"></script>
    <script src="{{ asset('template/front/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('template/front/assets/js/YTPlayer.min.js') }}"></script>
    <script src="{{ asset('template/front/assets/js/validnavs.js') }}"></script>
    <script src="{{ asset('template/front/assets/js/jquery.lettering.min.js') }}"></script>
    <script src="{{ asset('template/front/assets/js/jquery.circleType.js') }}"></script>
    <script src="{{ asset('template/front/assets/js/gsap.js') }}"></script>
    <script src="{{ asset('template/front/assets/js/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('template/front/assets/js/SplitText.min.js') }}"></script>
    <script src="{{ asset('template/front/assets/js/main.js') }}"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script src="{{ asset('backoffice/js/app_script.js') }}"></script>
    <script src="{{ asset('backoffice/js/js-loading-overlay.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    @stack('footer-script')

</body>
</html>