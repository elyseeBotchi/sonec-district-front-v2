
@extends('layout.LandingPage')

@section('content')

    <!-- Navbar & Carousel Start -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
            <a href="{{ route('welcome.index') }}" class="navbar-brand p-0">
                <h3 class="m-0">
                    <img src="{{ asset('template/assets/images/logo.png') }}" height="70px" alt="Logo">
                </h3>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="{{ route('welcome.index') }}" class="nav-item nav-link active">Accueil</a>
                    
                    <div class="nav-item dropdown">
                       
                    </div>
                    <div class="nav-item dropdown">
                      
                    </div>
                </div>
                {{-- <butaton type="button" class="btn text-primary ms-3" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="fa fa-search"></i>
                </butaton> --}}
                <a href="{{ route("login") }}" class="btn btn-primary py-2 px-4 ms-3">Connectez-vous</a>
            </div>
        </nav>

        <div id="header-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            @isset($services)
                @forelse ($services as $key => $service)
                    <div class="carousel-inner">
                        <div class="carousel-item @if($key ==0) active @endif ">
                            <img class="w-100" src="{{ asset('template/start/img/carousel-'.$key.'.png') }}" alt="Image">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 900px;">
                                    <h5 class="text-white text-uppercase mb-3 animated slideInDown"></h5>
                                    <h1 class="display-1 text-white mb-md-4 animated zoomIn">{{ $service['name'] ?? '' }}</h1>
                                    <a href="{{ route('quick.payment',['service' => $service['uuid'] ?? '','name' => $service['name'] ?? '']) }}" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">
                                        PAYER MAINTENANT
                                    </a>
                                    <a href="{{ route('register',['service' => $service['uuid'] ?? '','name' => $service['name'] ?? '']) }}" class="btn btn-outline-light py-md-3 px-md-5 animated slideInRight">
                                        INSCRIVEZ-VOUS
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                @empty
                @endforelse
            @endisset 
            <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <!-- Navbar & Carousel End -->


    <br>

    <br>

    <br>

    <br>

    <div class="container-fluid py-5 wow">
        <div class="pricing-style-one-area default-padding bg-cover bg-gray" style="background-image: url(assets/img/shape/3.jpg);">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                        <div class="site-heading text-center">
                            <h4 class="sub-title">DETAIL DU PAIEMENT</h4>
                            {{-- <h2 class="title">Our Pricing packages</h2> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                
                <br>             
                <br>

                <div class="row">
                    <center>
                        <span class="alert alert-warning">
                            Merci de bien vouloir patienter ou de contacter l'assistance.
                        </span>
                    </center>
                </div>
            </div>
        </div>
                 
        <br>             
        <br>  
    </div>

    

    
@endsection

@push('footer-script')
<script>
    var Paiement_uuid = @Json($paiement_uuid ?? '');
</script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
@endpush
