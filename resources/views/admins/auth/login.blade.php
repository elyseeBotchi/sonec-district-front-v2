@extends('layout.LandingPage')
@section('content')
@php
    $services = Entities_Customer();
@endphp
    <!-- Navbar & Carousel Start -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
            <a href="{{ route('welcome.index') }}" class="navbar-brand p-0">
                <h3 class="m-0">
                    <img src="{{ asset('template/assets/images/logo.png') }}"  height="70px" alt="Logo">
                </h3>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="{{ route('welcome.index') }}" class="nav-item nav-link active">Accueil</a>
                    <a href="{{ route('about', ['service' => $services[0]['uuid'] ?? '', 'name' => $services[0]['name'] ?? '']) }}" class="nav-item nav-link">Nous contacter</a>

                    <div class="nav-item dropdown"></div>
                    <div class="nav-item dropdown"></div>
                </div>
 
                <a href="{{ route('register', ['service' => $services[0]['uuid'] ?? '', 'name' => $services[0]['name'] ?? '']) }}" class="btn btn-primary py-2 px-4 ms-3">Mon compte</a>

            </div>
        </nav>

        <div class="container-fluid bg-primary py-3 bg-header" style="margin-bottom: 90px;">
            <div class="row py-5">
                <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                    <h1 class="display-6 text-white animated zoomIn">DISTRICT AUTONOME D'ABIDJAN</h1>
                    <a href="" class="h5 text-white">Plateforme digitale de délivrance de la carte de stationnement</a>
                    {{-- <i class="far fa-circle text-white px-2"></i>
                    <a href="" class="h5 text-white">About</a> --}}
                </div>
            </div>
        </div>
    </div>
    <!-- Navbar & Carousel End -->

 <!-- Quote Start -->
 <div class="container-fluid">
    <div class="container" style="transform: translateY(-114px);">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-6">
                <div class="bg-success rounded-3 d-flex p-3">
                    <form class="mt-4 sendForm" action="{{ route('panel.connexion') }}" method="POST">
                        @csrf
                        <h3 class="text-light">FORMULAIRE DE CONNEXION BACKOFFICE</h3>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="text-light" for="uname">Email</label>
                                    <input class="form-control" name="email" id="uname" type="email" placeholder="Email" required />
                                </div>
                                <br>
                            </div>
                           
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="text-light mb-0" for="pwd">Mot de passe</label>
                                        <a href="{{ route('panel.forget.password') }}" class="text-light">Oublié ?</a>
                                    </div>
                                    <input class="form-control mt-2" name="password" id="pwd" type="password" placeholder="Mot de passe" required />
                                </div>
                                <br>
                            </div>

                            <div class="col-lg-12 text-center">
                                <button type="submit" class="btn btn-block btn-outline-light">Connexion</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection




