@extends('layout.LandingPage')
@section('content')
<style>
    /* Style pour adapter le texte */
    .responsive-title {
        font-size: 3rem; /* Taille par défaut */
    }
    
    @media (max-width: 768px) {
        .responsive-title {
            font-size: 1.5rem; /* Taille pour les tablettes */
        }
    }
    
    @media (max-width: 576px) {
        .responsive-title {
            font-size: 0.7rem; /* Taille pour les mobiles */
        }
    }
    </style>

<style>

    .step {
        margin-bottom: 20px;
    }
    .step h2 {
        /* font-size: 18px; */
        color: #06a3da;
        margin-bottom: 10px;
    }
    .step ul {
        list-style: none;
        padding-left: 0;
    }
    .step ul li {
        margin-bottom: 10px;
        padding-left: 25px;
        position: relative;
        font-size: 20px;
    }
    .step ul li::before {
        content: '✔';
        position: absolute;
        left: 0;
        color: #28a745;
        font-weight: bold;
    }
    .note {
        margin-top: 20px;
        padding: 15px;
        background-color: #f8f9fa;
        border-left: 4px solid #06a3da;
        font-style: italic;
    }
    .note strong {
        color: #007BFF;
    }
</style>

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

    <div class="container-fluid py-5" style="position:relative;top:-150px !important;">
        <div class="container py-5">
            <div class="section-title text-center position-relative pb-3 mb-5 mx-auto">
               <h1 class="mb-0" id="target-comment-sacquitter">
                    Comment s'acquitter de sa taxe de stationnement 
                </h1> 
            </div>
            <div class="row g-5">
                <div class="col-lg-12">
                    <div class="container">
                       
                        <div class="step">
                            {{-- <h2>PAIEMENT RAPIDE DE TAXES DE STATIONNEMENT</h2>
                            <div  style="font-size:20px;">
                                <br>
                                Le <b>DISTRICT AUTONOME D’ABIDJAN</b>  vous fait gagner du temps avec les paiements en ligne de la Taxe de stationnement.
                                <br>
                                    C’est très simple !
                                    <br>
                                    Pour commencer, identifiez dans la liste des taxes, celle qui est applicable au type de véhicule en votre possession et ensuite payer en quelques clics
    
                                    <br>
                                    <br>
                            </div> --}}
                            <div>
                                <h3>Étape 1</h3>
                                <ul>
                                    <li>Se rendre sur le site : <a href="https://district-online.ci/" target="_blank">https://district-online.ci/</a></li>
                                    <li>Consulter la liste des taxes</li>
                                    <li>Renseigner le formulaire avec  les informations sur le  propriétaire et véhicule</li>
                                    <li>Procéder au paiement par  Mobile Money (Orange, MTN ou Wave)</li>
                                    <li>Imprimer votre reçu de paiement contenant votre date de RDV pour la validation et le retrait de votre carte de stationnement </li>
                                </ul>
                            </div>
                            <div>
                                <h3>Étape 2</h3>
                                <ul>
                                    <li>
                                        Se rendre au siège du District au Plateau le jour indiqué pour le RDV , muni du reçu de paiement et des pièces afférentes au véhicule pour la validation et le retrait de la quittance de stationnement.
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <br>
                        <div class="alert alert-danger" role="alert">
                            <i class="fa fa-info-circle me-2" aria-hidden="true"></i>
                            <strong class="text-uppercase">NB :</strong>
                                <h3 style="text-align: left">
                                    Le reçu de Paiement ne constitue pas une Carte de stationnement. Vous devez obligatoirement vous rendre au district pour le retrait de votre carte de stationnement avant le 31 Mars 2025. Passer ce délai des pénalités automatiques s’appliqueront.
                                <br> <br>
                             
                                    Toute tentative de fraude sur le montant de la taxe à payer sera sanctionnée par une pénalité d’office.
                                </h3>
                            </ul>
                        </div>
               
                        <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-3">
                            <a href="{{ route('quick.liste', ['service' => $services[0]['uuid'] ?? '', 'name' => $services[0]['name'] ?? '']) }}" 
                            class="btn btn-primary py-2 px-4 py-md-3 px-md-5 animated slideInLeft">
                            VOIR LA LISTE ET MONTANT DES TAXES
                            </a>
                            <a href="{{ route('quick.payment',['service' => $services[0]['uuid'] ?? '','name' => $services[0]['name'] ?? '']) }}" class="btn btn-primary py-2 px-4 py-md-3 px-md-5 animated slideInRight">
                                PAYER MA TAXE DE STATIONNEMENT
                            </a>  {{----}}
                        </div>
    
                        <br>
                        <br>
                        <br>
    
                        @isset($lock)
                            <div class="step">
                                <h2>Méthode 2 : CREER UN COMPTE </h2>
                                <div>
                                    <h3>Étape 1</h3>
                                    <ul>
                                        <li>Se rendre sur le site : <a href="https://district-online.ci/" target="_blank">https://district-online.ci/</a></li>                                
                                        <li>Creer votre compte </li>
                                        <li>Se connecter à son espace requérant</li>
                                        <li>Renseigner les informations afférentes aux véhicules </li>
                                        <li>Procéder au paiement avec l'un des opérateurs Mobile Money (Orange, MTN ou Wave)</li>
                                        <li>Imprimer votre reçu de paiement</li>
    
                                    </ul>
                                </div>
                                <div>
                                    <h3>Étape 2</h3>
                                    <ul>
                                        <li>Se rendre au district muni du reçu de paiement imprimé et des pièces afférentes au véhicule pour la validation et le retrait de la quittance de stationnement.</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <br>
                            <center>
                                   <a class="btn btn-primary py-2 px-4 py-md-3 px-md-5 animated slideInLeft text-uppercase" href="{{ route('register',['service' => $services[0]['uuid'] ?? '']) }}">
                                CREER UN COMPTE
                            </a>
                            </center>
                         
                            <br>
                        @endisset 
                        <br>
        
                        <br>
                        
     
                    </div>
                </div>
    
            </div>
        </div>
    </div>



   
@endsection
@push('footer-script')
@isset($services[0]['uuid'])
    <script>
        var Entity_uuid = @Json($services[0]['uuid'] ?? '');
    </script>
@endisset

@endpush
