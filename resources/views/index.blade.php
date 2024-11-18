@extends('layout.HomePage')
@section('content')
@php
    $services = Entities_Customer();
@endphp
   @isset($lock)
        <!-- Start Banner Area 
        ============================================= -->
        <div class="banner-area banner-style-two content-right">
           
                <div class="banner-fade">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide banner-style-two">
                            <div class="banner-thumb bg-cover shadow dark" style="background: url({{ asset('template/assets/images/background/background.jpg') }});"></div>
                            <div class="container">
                                <div class="row align-center">
                                    <div class="col-xl-7 offset-xl-5 col-lg-10 offset-lg-1">
                                        <div class="content">
                                            <h4></h4>
                                            <h2><strong>Taxe de </strong> {{ $services[0]['name'] ?? '' }}.</h2>
                                            @isset($services[0])
                                            <div class="button">
                                                <a class="btn circle btn-gradient btn-md radius animation" href="{{ route('register',['service' => $services[0]['uuid'] ?? '']) }}">Inscrivez-vous</a>
                                            </div>
                                            @endisset
                                            <div class="shape-circle"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Shape -->
                            {{-- <div class="banner-angle-shape">
                                <div class="shape-item" style="background: url({{ asset('template/front/assets/img/shape/2.png') }});"></div>
                            </div> --}}
                            <!-- End Shape -->
                        </div>
                    </div>

                    <div class="swiper-pagination"></div>
                </div>
                 
        </div>
       @endisset   


    <!-- Start About
    ============================================= -->
    <div class="about-style-three-area default-padding">
        <div class="container">
            <div class="row align-center">
                
                <div class="col-lg-6">
                    <div class="about-style-three-info">
                        <h4 class="sub-title">PAIEMENT RAPIDE</h4>
                        <h2 class="title">Payez vos taxes en toute simplicité !</h2>
                        <p>
                            Gagnez du temps avec notre plateforme de paiement rapide et sécurisé. Que ce soit pour les taxes de stationnement, d'abattoir ou autres, réglez vos obligations en quelques clics seulement, sans tracas ni files d'attente. Facile, rapide et efficace — simplifiez vos démarches dès aujourd'hui !                        
                        </p>
                        <div class="info-grid mt-50">
                            <div class="left-info">
                                <div class="fun-fact-card-two">
                                    <h4 class="sub-title">Expertise</h4>
                                    <div class="counter-title">
                                        <div class="counter">
                                            <div class="timer" data-to="10" data-speed="2000">10</div>
                                            <div class="operator">+</div>
                                        </div>
                                    </div>
                                    <span class="medium">ans experiences</span>
                                </div>
                            </div>

                            <div class="right-info bg-gradient text-light">
                                <form class="sendPayForm" action="{{ route('landing.entities.taxe.payment') }}">
                                    @csrf
                                    <h5 class="sub-title text-black" >Formulaire de paiement</h4>
                                    <ul class="list-style-three">
                                        <li>Sélectionner votre taxe</li>
                                        <span class="col-md-12">
                                            <select name="entity_uuid" id="SelectEntity" class="form-control">
                                                @isset($services)
                                                    @forelse ($services as $service)
                                                        <option value="{{ $service['uuid'] }}" @if($service['uuid'] == $services[0]['uuid']) selected @endif > {{ $service['name'] ?? '' }} </option>
                                                    @empty 
                                                    @endforelse
                                                @endisset
                                            </select>
                                        </span>

                                        <span id="form-container" class="row">
                                            <i class="fa fa-spinner"> ...</i>
                                        </span>

                                        <li>
                                            <label class="form-label">Rubrique de facturation <code>*</code></label>
                                        </li>
                                        <div class="col-md-12">                                                
                                            <select name="rubrique_facturation_uuid" class="form-control" id="rubrique">
                                                <!-- Les options seront insérées ici par la fonction JavaScript -->
                                            </select>
                                        </div>

                                        <li>
                                            <label class="form-label">Téléphone de paiement <code>*</code></label>
                                        </li>
                                        <div class="col-md-12">                                                
                                            <input type="text" class="form-control form-control-sm" id="num_pay"  name="numero_paiement" required="" minlength="10" maxlength="10" required />
                                        </div>
                    
                                        <li>
                                            <label for="prenoms" class="col-form-label">Opérateurs autorisés </label>
                                        </li>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="row">
                                                @isset($operateurs)
                                                    @forelse($operateurs as $operateur)
                                                        <label class="col-md-2">
                                                            <input type="radio" name="paymode" value="{{ $operateur['nom_operateur'] ?? '' }}"  />
                                                            <img class="img-responsive img-thumbnail" width="64" height="64" src="{{ asset('/operateurs/'.$operateur['logo'] ?? '') }}">
                                                            {{ $operateur['nom'] ?? '' }}
                                                        </label>
                                                    @empty
                                                        <p>Aucun opérateur disponible.</p>
                                                    @endforelse
                                                @endisset 
                                            </div>
                                        </div>

                                        <center>
                                            <br>
                                            <button type="submit" id="submitBtn" class="btn-dark btn-sm" style="padding: 5px;display:none;">Payer</button>
                                        </center>
                                        
                                        {{-- <li>Mobile networking</li>
                                        <li>Cloud computing</li>
                                        <li>Information technology consulting</li>
                                        <li>Backup solutions</li>
                                        <li>Hardware support</li> --}}
                                    </ul>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    <div class="thumb-style-two">
                        <img src="{{ asset('template/front/assets/img/about/4.jpg') }}" alt="">
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- End About -->

    <!-- Start Features 
    ============================================= -->
    <div class="features-style-two-area default-padding bottom-less bg-gray">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">Nos Produits</h4>
                        <h2 class="title">Our goal is giving the best our customers</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">


                @isset($services)
                
                @forelse ($services as $service)
                     <!-- Single Item -->
                     <div class="col-xl-4 col-md-6 feature-style-two-item">
                        <div class="feature-style-two">
                            <div class="thumb">
                                <img src="{{ asset('template/front/assets/img/features/1.jpg') }}" alt="Thumb">
                                <div class="title">
                                    <div class="top">
                                        <img src="{{ asset('template/front/assets/img/icon/13.png') }}" alt="Icon Not Found">
                                        <h4>
                                            <a href="{{ route('register',['service' => $service['uuid'] ?? '']) }}">
                                                {{ $service['name'] ?? '' }}
                                            </a>
                                        </h4>
                                    </div>
                                    <a href="services-details.html"><i class="fas fa-long-arrow-right"></i></a>
                                </div>
                                <div class="overlay text-center">
                                    <div class="content">
                                        <div class="icon">
                                            <img src="{{ asset('template/front/assets/img/icon/13.png') }}" alt="Icon Not Found">
                                        </div>
                                        <h4>
                                            <a href="{{ route('register',['service' => $service['uuid'] ?? '']) }}" > {{ $service['name'] ?? '' }}</a>
                                        </h4>
                                        <p>
                                            {{ $service['description'] ?? '' }}
                                        </p>
                                        <a href="{{ route('register',['service' => $service['uuid'] ?? '']) }}" > Inscrivez-vous </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                @empty
                    
                @endforelse
                   
                @endisset 

                <!-- Single Item -->
                <div class="col-xl-4 col-md-6 feature-style-two-item">
                    <div class="feature-style-two">
                        <div class="thumb">
                            <img src="{{ asset('template/front/assets/img/features/2.jpg') }}" alt="Thumb">
                            <div class="title">
                                <div class="top">
                                    <img src="{{ asset('template/front/assets/img/icon/14.png') }}" alt="Icon Not Found">
                                    <h4><a href="services-details.html">Cyber Security</a></h4>
                                </div>
                                <a href="services-details.html"><i class="fas fa-long-arrow-right"></i></a>
                            </div>
                            <div class="overlay text-center">
                                <div class="content">
                                    <div class="icon">
                                        <img src="{{ asset('template/front/assets/img/icon/14.png') }}" alt="Icon Not Found">
                                    </div>
                                    <h4><a href="services-details.html">Cyber Security</a></h4>
                                    <p>
                                        Prevailed mr tolerably discourse assurance estimable everything melancholy uncommonly solicitude inhabiting projection.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Single Item -->
                <!-- Single Item -->
                <div class="col-xl-4 col-md-6 feature-style-two-item">
                    <div class="feature-style-two">
                        <div class="thumb">
                            <img src="{{ asset('template/front/assets/img/features/3.jpg') }}" alt="Thumb">
                            <div class="title">
                                <div class="top">
                                    <img src="{{ asset('template/front/assets/img/icon/15.png') }}" alt="Icon Not Found">
                                    <h4><a href="services-details.html">Digital Experience</a></h4>
                                </div>
                                <a href="services-details.html"><i class="fas fa-long-arrow-right"></i></a>
                            </div>
                            <div class="overlay text-center">
                                <div class="content">
                                    <div class="icon">
                                        <img src="{{ asset('template/front/assets/img/icon/14.png') }}" alt="Icon Not Found">
                                    </div>
                                    <h4><a href="services-details.html">Digital Experience</a></h4>
                                    <p>
                                        Prevailed mr tolerably discourse assurance estimable everything melancholy uncommonly solicitude inhabiting projection.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Single Item -->
            </div>
        </div>
    </div>
    <!-- End Features -->

    <!-- Start Partner 
    ============================================= -->
    <div class="partner-style-one-area default-padding bg-dark text-light" style="background-image: url({{ asset('template/front/assets/img/shape/25.png') }});">
        <div class="container">
            <div class="row align-center">
                <div class="col-xl-4">
                    <h2 class="title">Thrusted brands work with us</h2>
                </div>
                <div class="col-xl-8 pl-60 pl-md-15 pl-xs-15 brand-one-contents">
                    <div class="brand-style-one-items">
                        <div class="brand-style-one-carousel swiper">
                            <!-- Additional required wrapper -->
                            <div class="swiper-wrapper">
                
                                <!-- Single Item -->
                                <div class="swiper-slide">
                                    <div class="brand-one">
                                        <img src="{{ asset('template/front/assets/img/brand/11.png') }}" alt="">
                                    </div>
                                </div>
                                <!-- End Single Item -->
                                 <!-- Single Item -->
                                <div class="swiper-slide">
                                    <div class="brand-one">
                                        <img src="{{ asset('template/front/assets/img/brand/22.png') }}" alt="">
                                    </div>
                                </div>
                                <!-- End Single Item -->
                                 <!-- Single Item -->
                                <div class="swiper-slide">
                                    <div class="brand-one">
                                        <img src="{{ asset('template/front/assets/img/brand/55.png') }}" alt="">
                                    </div>
                                </div>
                                <!-- End Single Item -->
                                 <!-- Single Item -->
                                <div class="swiper-slide">
                                    <div class="brand-one">
                                        <img src="{{ asset('template/front/assets/img/brand/66.png') }}" alt="">
                                    </div>
                                </div>
                                <!-- End Single Item -->
    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Partner -->


    <!-- Start Why Choose Us 
    ============================================= -->
    <div class="choose-us-style-two-area relative bg-dark text-light">
        <div class="container">
            <div class="row align-center">
                
                <div class="col-xl-6 order-xl-last pl-80 pl-md-15 pl-xs-15 choose-us-style-two-content">
                    <div class="info-style-one">
                        <h4 class="sub-title">Why Choose Us</h4>
                        <h2 class="title">Empowering success in technology since 1968 </h2>
                        <p>
                            Continue indulged speaking the was out horrible for domestic position. Seeing rather her you not esteem men settle genius excuse. Deal say over you age from. Comparison new ham melancholy son themselves.
                        </p>
                        <ul class="list-sytle-four mt-30">
                            <li>
                                <h4>Tech Solution</h4>
                                <p>
                                    Continued at up to zealously necessary breakfast. Surrounded sir motionless she end literature.
                                </p>
                            </li>
                            <li>
                                <h4>Quick support</h4>
                                <p>
                                    Continued at up to zealously necessary breakfast. Surrounded sir motionless she end literature
                                </p>
                            </li>
                        </ul>
                        <a class="btn btn-md circle btn-gradient animation mt-20" href="#" data-toggle="modal" data-target="#add-modal" >Retrouver son reçu</a>
                    </div>

                    
            <div class="modal fade" id="customer-edit_add-modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <form class="modal-content sendEntiteForm" action="{{ route('customer.entities.taxe.store') }}" method="POST">
                        @csrf

                        <div class="modal-header">
                            <h5 class="mb-0 text-uppercase"> RECUPERATION DU RECU DE PAIEMENT </h5>
                            <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal">
                                <i class="fa fa-close f-20"></i>
                            </a>
                        </div>
                        <div class="modal-body">
                            <ul class="list-sytle-four mt-30">
                                <li>
                                    <h4>Recuperation du reçu</h4>
                                    <p>
                                        Veuillez renseigner l'identifant de la transaction reçu de l'opérateur lors que paiement !
                                    </p>
                                </li>
                            </ul>
                            <div class="row" >
                                <label> ID Transaction </label>
                                <input type="tel" class="form-control" name="id_transaction" placeholder="ID Transaction" required />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-shadow closeModal" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary btn-shadow">Soumettre</button>
                        </div>
                    </form>
                </div>
            </div>
                </div>
                <div class="col-xl-6">
                    <div class="thumb-style-three">
                        <img src="{{ asset('template/front/assets/img/illustration/7.png') }}" alt="">
                        <div class="circle-text" style="background-image: url({{ asset('template/front/assets/img/shape/26.png') }});">
                            <!-- curved-circle start-->
                            <div class="circle-text-item" data-circle-text-options='{"radius": 81, "forceWidth": true, "forceHeight": true }'>
                                .  Certified Company   .  IT Consulting Solution
                            </div>
                            <a href="#"><i class="fas fa-long-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Why Choose Us -->
    @isset($lock)
        <!-- Start Services 
        ============================================= -->
        <div class="services-style-three-area default-padding bottom-less bg-gray-secondary bg-cover" style="background-image: url({{ asset('template/front/assets/img/shape/24.png') }});">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                        <div class="site-heading text-center">
                            <h4 class="sub-title">Our Services</h4>
                            <h2 class="title">Empower your business with our services.</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <!-- Single Item -->
                    <div class="col-xl-4 col-lg-6 col-md-6 mb-30">
                        <div class="services-style-three-item">
                        <div class="item-title">
                                <img src="{{ asset('template/front/assets/img/icon/16.png') }}" alt="">
                                <h4><a href="services-details.html">Analytic Solutions</a></h4>
                                <p>
                                    Seeing rather her you not esteem men settle genius excuse. Deal say over you age from. Comparison new ham melancholy son themselves the perfect connections.
                                </p>
                                <div class="d-flex mt-30">
                                    <a href="services-details.html"><i class="fas fa-long-arrow-right"></i></a>
                                    <div class="service-tags">
                                        <a href="#">Management</a>
                                        <a href="#">Backup</a>
                                    </div>
                                </div>
                        </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-xl-4 col-lg-6 col-md-6 mb-30">
                        <div class="services-style-three-item">
                        <div class="item-title">
                                <img src="{{ asset('template/front/assets/img/icon/17.png') }}" alt="">
                                <h4><a href="services-details.html">Risk Management</a></h4>
                                <p>
                                    Regular rather her you not esteem men settle genius excuse. Deal say over you age from. Comparison new ham melancholy son themselves the perfect connections.
                                </p>
                                <div class="d-flex mt-30">
                                    <a href="services-details.html"><i class="fas fa-long-arrow-right"></i></a>
                                    <div class="service-tags">
                                        <a href="#">Hardware </a>
                                        <a href="#">Error</a>
                                    </div>
                                </div>
                        </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-xl-4 col-lg-6 col-md-6 mb-30">
                        <div class="services-style-three-item">
                        <div class="item-title">
                                <img src="{{ asset('template/front/assets/img/icon/18.png') }}" alt="">
                                <h4><a href="services-details.html">Firewall Advance</a></h4>
                                <p>
                                    Patient rather her you not esteem men settle genius excuse. Deal say over you age from. Comparison new ham melancholy son themselves the perfect connections.
                                </p>
                                <div class="d-flex mt-30">
                                    <a href="services-details.html"><i class="fas fa-long-arrow-right"></i></a>
                                    <div class="service-tags">
                                        <a href="#">Network</a>
                                        <a href="#">Firewall </a>
                                    </div>
                                </div>
                        </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                </div>
            </div>
        </div>
        <!-- End Services -->

        <!-- Start Speciality 
        ============================================= -->
        <div class="speciality-style-one-area default-padding-bottom">
            <div class="container">
                <div class="row align-center">
                    <div class="col-lg-4">
                        <div class="fun-fact-style-two text-light" style="background-image: url({{ asset('template/front/assets/img/shape/1.jpg') }});">
                            <div class="fun-fact">
                                <div class="counter-title">
                                    <div class="counter">
                                        <div class="timer" data-to="98" data-speed="2000">98</div>
                                        <div class="operator">%</div>
                                    </div>
                                </div>
                                <span class="medium">Successfull Projects</span>
                            </div>
                            <div class="fun-fact">
                                <div class="counter-title">
                                    <div class="counter">
                                        <div class="timer" data-to="38" data-speed="2000">38</div>
                                        <div class="operator">K</div>
                                    </div>
                                </div>
                                <span class="medium">Happy Clients</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-7 offset-xl-1 col-lg-8">
                        <div class="speciality-items">
                            <h4 class="sub-title">Our expertise</h4>
                            <h2 class="title">Our commitment <br> is client satisfaction </h2>
                            <div class="d-grid mt-40">
                                <ul class="list-style-two">
                                    <li>Organizational structure model </li>
                                    <li>Satisfaction guarantee</li>
                                    <li>Ontime delivery</li>
                                </ul>
                                <div class="progress-items">
                                    <div class="progress-box">
                                        <h5>IT Managment</h5>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" data-width="70">
                                                <span>70%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="progress-box">
                                        <h5>Data Security</h5>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" data-width="95">
                                                <span>95%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Speciality -->

        <!-- Start Gallery 
        ============================================= -->
        <div class="gallery-style-one-area bg-gray default-padding">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-9">
                        <div class="site-heading">
                            <h4 class="sub-title">Case Studies</h4>
                            <h2 class="title">Have a view of our amazing projects with our clients</h2>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-3">
                        <div class="project-navigation-items">
                            <!-- Navigation -->
                            <div class="project-swiper-nav">

                                <!-- Pagination -->
                                <div class="project-pagination"></div>

                                <div class="project-button-prev"></div>
                                <div class="project-button-next"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fill">
                <div class="row">
                    <div class="gallery-style-one-carousel swiper">
                        <!-- Additional required wrapper -->
                        <div class="swiper-wrapper">


                            <!-- Single Item -->
                            <div class="swiper-slide">
                                <div class="gallery-style-one">
                                    <img src="{{ asset('template/front/assets/img/projects/5.jpg') }}" alt="">
                                    <div class="overlay">
                                        <div class="info">
                                            <h4><a href="project-details.html">Cyber Security</a></h4>
                                            <span>Technology, IT</span>
                                            <p>
                                                Continued at up to zealously necessary breakfast. Surrounded sir motionless she end literature.
                                            </p>
                                        </div>
                                        <a href="project-details.html">Explore <i class="fas fa-long-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Item -->


                            <!-- Single Item -->
                            <div class="swiper-slide">
                                <div class="gallery-style-one">
                                    <img src="{{ asset('template/front/assets/img/projects/6.jpg') }}" alt="">
                                    <div class="overlay">
                                        <div class="info">
                                            <h4><a href="project-details.html">IT Counsultancy</a></h4>
                                            <span>Security, Firewall</span>
                                            <p>
                                                Continued at up to zealously necessary breakfast. Surrounded sir motionless she end literature.
                                            </p>
                                        </div>
                                        <a href="project-details.html">Explore <i class="fas fa-long-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Item -->

                            <!-- Single Item -->
                            <div class="swiper-slide">
                                <div class="gallery-style-one">
                                    <img src="{{ asset('template/front/assets/img/projects/7.jpg') }}" alt="">
                                    <div class="overlay">
                                        <div class="info">
                                            <h4><a href="project-details.html">Analysis of Security</a></h4>
                                            <span>Support, Tech</span>
                                            <p>
                                                Continued at up to zealously necessary breakfast. Surrounded sir motionless she end literature.
                                            </p>
                                        </div>
                                        <a href="project-details.html">Explore <i class="fas fa-long-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Item -->
                            <!-- Single Item -->
                            <div class="swiper-slide">
                                <div class="gallery-style-one">
                                    <img src="{{ asset('template/front/assets/img/projects/8.jpg') }}" alt="">
                                    <div class="overlay">
                                        <div class="info">
                                            <h4><a href="project-details.html">Business Analysis</a></h4>
                                            <span>Network, Error</span>
                                            <p>
                                                Continued at up to zealously necessary breakfast. Surrounded sir motionless she end literature.
                                            </p>
                                        </div>
                                        <a href="project-details.html">Explore <i class="fas fa-long-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Item -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Gallery -->
    @endisset
    @isset($lock)
        <!-- Start Team 
        ============================================= -->
        <div class="team-style-two-area default-padding">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                        <div class="site-heading text-center">
                            <h4 class="sub-title">Team Members</h4>
                            <h2 class="title">Meet the talented team form our company</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <!-- Single Item -->
                    <div class="col-lg-4 col-md-6 team-style-two">
                        <div class="team-style-two-item" style="background-image: url({{ asset('template/front/assets/img/shape/15.webp') }});">
                            <div class="thumb">
                                <img src="assets/img/team/v4.jpg" alt="">
                                <a href="#"><i class="fas fa-envelope"></i></a>
                            </div>
                            <div class="info">
                                <h4><a href="team-details.html">Aleesha Brown</a></h4>
                                <span>CEO & Founder</span>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-lg-4 col-md-6 team-style-two">
                        <div class="team-style-two-item" style="background-image: url({{ asset('template/front/assets/img/shape/15.webp') }});">
                            <div class="thumb">
                                <img src="assets/img/team/v5.jpg" alt="">
                                <a href="#"><i class="fas fa-envelope"></i></a>
                            </div>
                            <div class="info">
                                <h4><a href="team-details.html">Kevin Martin</a></h4>
                                <span>Product Manager</span>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-lg-4 col-md-6 team-style-two">
                        <div class="team-style-two-item" style="background-image: url({{ asset('template/front/assets/img/shape/15.webp') }});">
                            <div class="thumb">
                                <img src="{{ asset('template/front/assets/img/team/v1.jpg') }}" alt="">
                                <a href="#"><i class="fas fa-envelope"></i></a>
                            </div>
                            <div class="info">
                                <h4><a href="team-details.html">Sarah Albert</a></h4>
                                <span>Financial Consultant</span>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-lg-4 col-md-6 team-style-two">
                        <div class="team-style-two-item" style="background-image: url({{ asset('template/front/assets/img/shape/15.webp') }});">
                            <div class="thumb">
                                <img src="assets/img/team/v2.jpg" alt="">
                                <a href="#"><i class="fas fa-envelope"></i></a>
                            </div>
                            <div class="info">
                                <h4><a href="team-details.html">Amanulla Joey</a></h4>
                                <span>Developer</span>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-lg-4 col-md-6 team-style-two">
                        <div class="team-style-two-item" style="background-image: url({{ asset('template/front/assets/img/shape/15.webp') }});">
                            <div class="thumb">
                                <img src="{{ asset('template/front/assets/img/team/v3.jpg') }}" alt="">
                                <a href="#"><i class="fas fa-envelope"></i></a>
                            </div>
                            <div class="info">
                                <h4><a href="team-details.html">Kamal Abraham</a></h4>
                                <span>Co Founder</span>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                </div>
            </div>
        </div>
        <!-- End Team -->
    

        <!-- Start Testimonials 
        ============================================= -->
        <div class="testimonial-style-two-area bg-dark default-padding text-light bg-cover" style="background-image: url({{ asset('template/front/assets/img/shape/5.jpg') }});">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="testimonial-two-info">
                            <div class="icon">
                                <img src="assets/img/quote.png" alt="">
                            </div>
                            <h2>Over 50K clients and 5,000 projects across the globe.</h2>
                            <div class="review-card">
                                <h6>Excellent 18,560+ Reviews</h6>
                                <div class="d-flex">
                                    <div class="icon">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span>4.8/5</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 pl-60 pl-md-15 pl-xs-15">
                        <div class="testimonial-style-two-carousel swiper">
                            <!-- Additional required wrapper -->
                            <div class="swiper-wrapper">
                                <!-- Single item -->
                                <div class="swiper-slide">
                                    <div class="testimonial-style-two">
                                        
                                        <div class="item">
                                            <div class="text-info">
                                                <p>
                                                    “Targeting consultation apartments. ndulgence creative under folly death wrote cause her way spite. Plan upon yet way get cold spot its week.
                                                </p>
                                            </div>
                                            <div class="content">
                                                <div class="thumb">
                                                    <img src="assets/img/team/v1.jpg" alt="">
                                                </div>
                                                <div class="info">
                                                    <h4>Matthew J. Wyman</h4>
                                                    <span>Senior Consultant</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single item -->
                                <!-- Single item -->
                                <div class="swiper-slide">
                                    <div class="testimonial-style-two">
                                        <div class="item">
                                            <div class="text-info">
                                                <p>
                                                    “Consultation discover apartments. ndulgence off under folly death wrote cause her way spite. Plan upon yet way get cold spot its week.
                                                </p>
                                            </div>
                                            <div class="content">
                                                <div class="thumb">
                                                    <img src="assets/img/team/v2.jpg" alt="">
                                                </div>
                                                <div class="info">
                                                    <h4>Anthom Bu Spar</h4>
                                                    <span>Marketing Manager</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single item -->
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Testimonials -->
   
    <!-- Start Blog 
    ============================================= -->
    <div class="blog-area home-blog blog-2-col default-padding bottom-less">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">Blog Insight</h4>
                        <h2 class="title">Valuable insights to change your startup idea</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <!-- Single Item -->
                <div class="col-xl-6 col-md-6 col-lg-6 mb-30">
                    <div class="home-blog-style-one-item animate" data-animate="fadeInUp" data-delay="100ms">
                        <div class="home-blog-thumb">
                            <img src="assets/img/blog/2.jpg" alt="">
                            <ul class="home-blog-meta">
                                <li>
                                    <a href="#">loan</a>
                                </li>
                                <li>October 18, 2024</li>
                            </ul>
                        </div>
                        <div class="content">
                            <div class="info">
                                <h2 class="blog-title">
                                    <a href="blog-single-with-sidebar.html">This prefabrice passive house is memorable highly sustainable</a>
                                </h2>
                                <a href="blog-single-with-sidebar.html" class="btn-read-more">Read More <i class="fas fa-long-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Single Item -->
                <!-- Single Item -->
                <div class="col-xl-6 col-md-6 col-lg-6 mb-30">
                    <div class="home-blog-style-one-item animate" data-animate="fadeInUp" data-delay="200ms">
                        <div class="home-blog-thumb">
                            <img src="assets/img/blog/3.jpg" alt="">
                            <ul class="home-blog-meta">
                                <li>
                                    <a href="#">insentive</a>
                                </li>
                                <li>August 26, 2024</li>
                            </ul>
                        </div>
                        <div class="content">
                            <div class="info">
                                <h2 class="blog-title">
                                    <a href="blog-single-with-sidebar.html">Announcing if attachment resolution performing the regular sentim.</a>
                                </h2>
                                <a href="blog-single-with-sidebar.html" class="btn-read-more">Read More <i class="fas fa-long-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Single Item -->
            </div>
        </div>
    </div>
    <!-- End Blog -->
    @endisset
@endsection
@push('footer-script')
@isset($services[0]['uuid'])
    <script>
        var Entity_uuid = @Json($services[0]['uuid'] ?? '');
    </script>
@endisset

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
<script src="{{ asset('/backoffice/js/front/landingPage.js') }}"></script>
@endpush
