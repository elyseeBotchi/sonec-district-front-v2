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
    <div class="container-fluid position-relative p-0 d-none d-md-block">
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
                    <a href="{{ route('about', ['service' => $services[0]['uuid'] ?? '', 'name' => $services[0]['name'] ?? '']) }}" class="nav-item nav-link">Nous contacter</a>

                    <div class="nav-item dropdown">
                       
                    </div>
                    <div class="nav-item dropdown">
                      
                    </div>
                </div>
                {{-- <butaton type="button" class="btn text-primary ms-3" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="fa fa-search"></i>
                </butaton> --}}
                <a href="{{ route('register', ['service' => $services[0]['uuid'] ?? '', 'name' => $services[0]['name'] ?? '']) }}" class="btn btn-primary py-2 px-4 ms-3">Mon compte</a>
                {{-- <a href="{{ route("login") }}" class="btn btn-primary py-2 px-4 ms-3">Connectez-vous</a> --}}
            </div>
        </nav>
        <div id="header-carousel" class="carousel slide carousel-fade  " data-bs-ride="carousel" >
            @isset($services)
                @isset($services[0])
                    @forelse ($services as $key => $service)
                        <div class="carousel-inner">
                            <div class="carousel-item @if($key == 0) active @endif">
                                <img class="w-100" src="{{ asset('template/start/img/carousel-'.$key.'.png') }}" alt="Image">
                                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                    <div class="p-3">
                                        <!-- style="max-width: 900px;" Texte avec classes et styles responsives -->
                                        <h1 class="text-white fw-bold text-center">
                                            DISTRICT AUTONOME D'ABIDJAN
                                        </h1>
                                        <center>
                                            <img 
                                                src="{{ asset('template/assets/images/logo.png') }}" 
                                                height="150px" 
                                                alt="Logo" 
                                                class="d-none d-md-block"
                                            >
                                        </center>
                                        <h5 class="text-white text-uppercase mb-3 animated slideInDown"></h5>
                                        <p class="display-1 text-white mb-md-4 animated zoomIn" style="font-size: 45px !important">
                                            Plateforme digitale de délivrance de la carte de stationnement
                                        </p>
                                        <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-3">
                                            <a href="{{ route('quick.liste', ['service' => $service['uuid'] ?? '', 'name' => $service['name'] ?? '']) }}" 
                                            class="btn btn-primary py-2 px-4 py-md-3 px-md-5 animated slideInLeft">
                                            VOIR LA LISTE ET MONTANT DES TAXES
                                            </a>

                                            <a href="{{ route('quick.acquitter',['service' => $services[0]['uuid'] ?? '','name' => $service['name'] ?? '']) }}" class="btn btn-outline-light py-2 px-4 py-md-3 px-md-5 animated slideInRight">
                                            COMMENT S'ACQUITTER DE SA TAXE
                                            </a>

                                            <a href="{{ route('quick.payment',['service' => $services[0]['uuid'] ?? '','name' => $service['name'] ?? '']) }}" class="btn btn-outline-light py-2 px-4 py-md-3 px-md-5 animated slideInRight">
                                                PAYER SA TAXE
                                            </a>

                                            <a href="{{ route('quick.proforma',['service' => $services[0]['uuid'] ?? '']) }}" class="btn btn-outline-light py-2 px-4 py-md-3 px-md-5 animated slideInRight">
                                                GENERER UNE PROFORMA
                                            </a>
                                            
                                            {{----}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                @endisset 

            @endisset
            <button class="carousel-control-prev" style="display: none" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next hidden" style="display: none" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        
    </div>
    <!-- Navbar & Carousel End -->



    <div class="container-fluid position-relative p-0 d-block d-md-none">
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

        
    <div class="container-fluid py-5" style="position:relative;top:-150px !important;">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-12">
                    <div class="container">
                        <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-3">
                            <a href="{{ route('quick.liste', ['service' => $services[0]['uuid'] ?? '', 'name' => $services[0]['name'] ?? '']) }}" 
                            class="btn btn-primary py-2 px-4 py-md-3 px-md-5 animated slideInLeft">
                            VOIR LA LISTE ET MONTANT DES TAXES ***
                            </a>
                            
                            <a href="{{ route('quick.acquitter',['service' => $services[0]['uuid'] ?? '','name' => $service['name'] ?? '']) }}" class="btn btn-primary py-2 px-4 py-md-3 px-md-5 animated  slideInRight">
                                COMMENT S'ACQUITTER DE SA TAXE
                            </a>

                            <a href="{{ route('quick.payment',['service' => $services[0]['uuid'] ?? '','name' => $services[0]['name'] ?? '']) }}" class="btn btn-primary py-2 px-4 py-md-3 px-md-5 animated slideInRight">
                                PAYER MA TAXE DE STATIONNEMENT
                            </a> 

                            <a href="{{ route('quick.payment',['service' => $services[0]['uuid'] ?? '','name' => $services[0]['name'] ?? '']) }}" class="btn btn-primary py-2 px-4 py-md-3 px-md-5 animated slideInRight">
                                GENERER UNE PROFORMA
                            </a> 
                            
                            {{----}}
                        </div>
    
             
                        <br>
        
                        <br>
                        
     
                    </div>
                </div>
    
            </div>
        </div>
    </div>
        <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-3">
            <a href="{{ route('quick.liste', ['service' => $service['uuid'] ?? '', 'name' => $service['name'] ?? '']) }}" 
               class="btn btn-primary py-2 px-4 py-md-3 px-md-5 animated slideInLeft">
               VOIR LA LISTE ET MONTANT DES TAXES
            </a>

            <a href="{{ route('quick.acquitter',['service' => $services[0]['uuid'] ?? '','name' => $service['name'] ?? '']) }}" class="btn btn-secondary py-2 px-4 py-md-3 px-md-5 animated slideInRight">
               COMMENT S'ACQUITTER DE SA TAXE
            </a>

            <a href="{{ route('quick.payment',['service' => $services[0]['uuid'] ?? '','name' => $service['name'] ?? '']) }}" class="btn btn-secondary py-2 px-4 py-md-3 px-md-5 animated slideInRight">
                PAYER SA TAXE
             </a>

             <a href="{{ route('quick.payment',['service' => $services[0]['uuid'] ?? '','name' => $service['name'] ?? '']) }}" class="btn btn-secondary py-2 px-4 py-md-3 px-md-5 animated slideInRight">
                GENERER UNE PROFORMA
             </a>
            
            {{----}}
        </div>
    </div>


@isset($lock)

    <div class="container-fluid py-5 wow" style="display: none">
        <div class="container py-5">
            <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 600px;">
                <h5 class="fw-bold text-primary text-uppercase">TYPES DE VEHICULES</h5>
             
            </div>
            <div class="row g-5">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-justify align-middle">
                            <thead class="table-primary">
                                <tr>
                                    {{-- <th class="text-uppercase">#</th> --}}
                                    <th class="text-uppercase">RUBRIQUES BUDGETAIRES</th>
                                    <th class="text-uppercase">Montant</th>
                                </tr>
                            </thead>
                            <tbody id="tarif_line"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            
            <center>
                <a href="{{ route('quick.payment', ['service' => $services[0]['uuid'] ?? '', 'name' => $services[0]['name'] ?? '']) }}" 
                    class="btn btn-primary py-2 px-4 py-md-3 px-md-5 animated slideInLeft">
                    
                </a>
            </center>

        </div>
    </div>




<!-- Features Start -->
<div class="container-fluid py-5 wow">
    <div class="container py-5">
        <div class="section-title text-center position-relative pb-3 mb-5 mx-auto">
            <h5 class="fw-bold text-primary text-uppercase">
                           
            </h5>
           <h1 class="mb-0" id="target-comment-sacquitter">
                Comment s'acquitter de sa taxe de stationnement 
            </h1> 
        </div>
        <div class="row g-5">
            <div class="col-lg-12">
                <div class="container">
                   
                    <div class="step">
                        <h2>Méthode 1 : PAIEMENT RAPIDE DE TAXES DE STATIONNEMENT</h2>
                        <div  style="font-size:20px;">
                            <br>
                            Le <b>DISTRICT AUTONOME D’ABIDJAN</b>  vous fait gagner du temps avec les paiements en ligne de la Taxe de stationnement.
                            <br>
                                C’est très simple !
                                <br>
                                Pour commencer, identifiez dans la liste des taxes, celle qui est applicable au type de véhicule en votre possession et ensuite payer en quelques clics

                                <br>
                                <br>
                        </div>
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
                                    Se rendre au siège du District au Plateau le jour indiqué pour le RDV , muni du reçu de paiement et des pièces afférentes au véhicule pour la validation et le retrait de la carte de stationnement.
                                </li>
                            </ul>
                        </div>
                    </div>
                    <br>
            
                    
                    <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-3">
                        <a href="{{ route('quick.liste', ['service' => $service['uuid'] ?? '', 'name' => $service['name'] ?? '']) }}" 
                        class="btn btn-primary py-2 px-4 py-md-3 px-md-5 animated slideInLeft">
                        VOIR LA LISTE ET MONTANT DES TAXES
                        </a>
                        <a href="{{ route('quick.payment',['service' => $services[0]['uuid'] ?? '','name' => $service['name'] ?? '']) }}" class="btn btn-primary py-2 px-4 py-md-3 px-md-5 animated slideInRight">
                            PAYER MA TAXE DE STATIONNEMENT
                        </a>  {{----}}
                    </div>

                    <br>
                    <br>
                    <br>

                    @isset($lock)@endisset 
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
                                    <li>Se rendre au district muni du reçu de paiement imprimé et des pièces afférentes au véhicule pour la validation et le retrait de la carte de stationnement.</li>
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
                    
                    <br>
    
                    <br>
                    <div class="note" >
                        <p><strong>NB :</strong></p>
                        
                            <h2 style="color: red;text-align:justify;">
                                Le reçu de Paiement ne constitue pas une Carte de stationnement. Vous devez obligatoirement vous rendre au district pour le retrait de votre carte de stationnement avant le 31 Mars 2025. Passer ce délai des pénalités automatiques s’appliqueront.
                            </h2>
                            <br>
                            <h2 style="color: red;text-align:justify;">
                                Toute tentative de fraude sur le montant de la taxe à payer sera sanctionnée par une pénalité d’office.
                            </h2>
                    </div>
    
 
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Features Start -->

             
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
@endisset

@endsection
@push('footer-script')
    @isset($services[0]['uuid'])
        <script>
            var Entity_uuid = @Json($services[0]['uuid'] ?? '');
        </script>
    @endisset
    @isset($lock)
    <script src="{{ asset('/backoffice/js/front/indexPage.js') }}"></script>
    @endisset

@endpush
