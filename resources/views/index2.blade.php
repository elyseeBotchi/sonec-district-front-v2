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
                <a href="{{ route('register', ['service' => $services[0]['uuid'] ?? '', 'name' => $services[0]['name'] ?? '']) }}" class="btn btn-primary py-2 px-4 ms-3">Créer un compte</a>
                <a href="{{ route("login") }}" class="btn btn-primary py-2 px-4 ms-3">Connectez-vous</a>
            </div>
        </nav>
        <div id="header-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            @isset($services)
                @forelse ($services as $key => $service)
                    <div class="carousel-inner">
                        <div class="carousel-item @if($key == 0) active @endif">
                            <img class="w-100" src="{{ asset('template/start/img/carousel-'.$key.'.png') }}" alt="Image">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 900px;">
                                    <!-- Texte avec classes et styles responsives -->
                                    <h1 class="text-white fw-bold text-center d-none d-md-block">
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
                                    <h1 class="display-1 text-white mb-md-4 animated zoomIn">{{ $service['name'] ?? '' }}</h1>
                                    <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-3">
                                        <a href="{{ route('quick.liste', ['service' => $service['uuid'] ?? '', 'name' => $service['name'] ?? '']) }}" 
                                           class="btn btn-primary py-2 px-4 py-md-3 px-md-5 animated slideInLeft">
                                            LISTE DES TAXES
                                        </a>
                                        <a href="#target-comment-sacquitter" class="btn btn-outline-light py-2 px-4 py-md-3 px-md-5 animated slideInRight">
                                           COMMENT S'ACQUITTER DE SA TAXE
                                        </a>  {{----}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
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
                    PAYER LA TAXE
                </a>
            </center>

        </div>
    </div>


@isset($lock)
<!-- Blog Start -->
<div class="container-fluid py-5 wow">
    <div class="container py-5">
        <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 600px;">
            <h5 class="fw-bold text-primary text-uppercase">NOS TAXES</h5>
            <h1 class="mb-0"></h1>
        </div>

        <div class="row g-5">
            
            @isset($services)
                @forelse ($services as $key => $service)
                    <div class="col-lg-4 wow">
                        <div class="blog-item bg-light rounded overflow-hidden">
                            <div class="blog-img position-relative overflow-hidden">
                                <img class="img-fluid" src="{{ asset('template/front/assets/img/features/'.$key.'.jpg') }}" alt="">
                                {{-- <a class="position-absolute top-0 start-0 bg-primary text-white rounded-end mt-5 py-2 px-4" href="">
                                    {{ $service['name'] ?? '' }}
                                </a> --}}
                            </div>
                            <div class="p-4">
                                {{-- <div class="d-flex mb-3">
                                    <small class="me-3">
                                        <i class="far fa-user text-primary me-2"></i>
                                        John Doe
                                    </small>
                                    <small>
                                        <i class="far fa-calendar-alt text-primary me-2"></i>
                                        01 Jan, 2045
                                    </small>
                                </div> --}}
                                <h4 class="mb-3">
                                    <a class="text-decoration-none text-uppercase" href="">
                                        {{ $service['name'] ?? '' }}
                                    </a>
                                </h4>
                                <p>{{ $service['description'] ?? '' }}</p>
                                <div class="row">
                                    <a class="col-md-6 text-uppercase" href="{{ route('register',['service' => $service['uuid'] ?? '','name' => $service['name'] ?? '']) }}">
                                        Inscrivez-vous
                                    </a>
                                    <a class="col-md-6 text-uppercase" href="{{ route('quick.payment',['service' => $service['uuid'] ?? '','name' => $service['name'] ?? '']) }}"> 
                                        <span class="text-right">
                                            Payer maintenant  
                                        </span> 
                                    </a>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse 
            @endisset
            
            @isset($lock)
            <div class="col-lg-4 wow slideInUp" data-wow-delay="0.6s">
                <div class="blog-item bg-light rounded overflow-hidden">
                    <div class="blog-img position-relative overflow-hidden">
                        <img class="img-fluid" src="{{ asset('template/start/img/blog-2.jpg') }}" alt="">
                        <a class="position-absolute top-0 start-0 bg-primary text-white rounded-end mt-5 py-2 px-4" href="">Web Design</a>
                    </div>
                    <div class="p-4">
                        {{-- <div class="d-flex mb-3">
                            <small class="me-3"><i class="far fa-user text-primary me-2"></i>John Doe</small>
                            <small><i class="far fa-calendar-alt text-primary me-2"></i>01 Jan, 2045</small>
                        </div> --}}
                        <h4 class="mb-3">How to build a website</h4>
                        <p>Dolor et eos labore stet justo sed est sed sed sed dolor stet amet</p>
                        <div class="row">
                            <a class="col-md-6 text-uppercase" href="{{ route('register',['service' => $service['uuid'] ?? '']) }}">
                                Inscrivez-vous
                            </a>
                            <a class="col-md-6 text-uppercase" href="{{ route('quick.payment',['service' => $service['uuid'] ?? '']) }}"> 
                                <span class="text-right">
                                    Payer maintenant  
                                </span> 
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 wow slideInUp" data-wow-delay="0.9s">
                <div class="blog-item bg-light rounded overflow-hidden">
                    <div class="blog-img position-relative overflow-hidden">
                        <img class="img-fluid" src="{{ asset('template/start/img/blog-3.jpg') }}" alt="">
                        <a class="position-absolute top-0 start-0 bg-primary text-white rounded-end mt-5 py-2 px-4" href="">Web Design</a>
                    </div>
                    <div class="p-4">
                        {{-- <div class="d-flex mb-3">
                            <small class="me-3"><i class="far fa-user text-primary me-2"></i>John Doe</small>
                            <small><i class="far fa-calendar-alt text-primary me-2"></i>01 Jan, 2045</small>
                        </div> --}}
                        <h4 class="mb-3">How to build a website</h4>
                        <p>Dolor et eos labore stet justo sed est sed sed sed dolor stet amet</p>
                        <div class="row">
                            <a class="col-md-6 text-uppercase" href="{{ route('register',['service' => $service['uuid'] ?? '']) }}">
                                Inscrivez-vous
                            </a>
                            <a class="col-md-6 text-uppercase" href="{{ route('quick.payment',['service' => $service['uuid'] ?? '']) }}"> 
                                <span class="text-right">
                                    Payer maintenant  
                                </span> 
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endisset 
        </div>
    </div>
</div>
@endisset 
<!-- Blog Start -->

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
                        <h2>Méthode 1 : Paiement rapide</h2>
                        <div>
                            <h3>Étape 1</h3>
                            <ul>
                                <li>Se rendre sur le site : <a href="https://district-online.ci/" target="_blank">https://district-online.ci/</a></li>
                                <li>Consulter la liste des taxes</li>
                                <li>Renseigner les informations personnelles du propriétaire du véhicule et les informations afférentes au véhicule</li>
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
                    <a class=" btn btn-primary py-2 px-4 py-md-3 px-md-5 animated slideInLeft text-uppercase" href="{{ route('quick.payment',['service' => $services[0]['uuid'] ?? '','name' => $service['name'] ?? '']) }}"> 
                        <span class="text-right">
                            PAYER MA TAXE 
                        </span> 
                    </a>
                    <br>
                    <br>
                    <br>

                    @isset($lock)@endisset 
                        <div class="step">
                            <h2>Méthode 2 : Création de compte et paiement</h2>
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
                        <a class="btn btn-primary py-2 px-4 py-md-3 px-md-5 animated slideInLeft text-uppercase" href="{{ route('register',['service' => $services[0]['uuid'] ?? '']) }}">
                            CREER UN COMPTE
                        </a>
                        <br>
                    
                    <br>
    
                    <br>
                    <div class="note">
                        <p><strong>NB :</strong></p>
                        <ul>
                            <li>Pour les paiements rapides, votre quittance est émise par email.</li>
                            {{-- <li>Pour les paiements avec un compte, votre quittance est disponible dans votre espace requérant et également émise par email.</li> --}}
                        </ul>
                    </div>
    

                @isset($lock)
                <div class="step">
                    <h2>Méthode 1 : Paiement rapide</h2>
                    <div>
                        <h3>Étape 1</h3>
                        <ul>
                            <li>Aller sur le site : <a href="https://district-online.ci/" target="_blank">https://district-online.ci/</a></li>
                            <li>Cliquer sur le bouton <strong>Liste des taxes</strong></li>
                            <li>Aller au bas de la page et cliquer sur le bouton <strong>PAYER LA TAXE</strong></li>
                            <li>Remplir le formulaire affiché à droite</li>
                            <li>Cliquer sur le bouton <strong>Payer</strong></li>
                            <li>Effectuer le paiement selon le mode de paiement choisi</li>
                            <li>Télécharger le reçu affiché à l’écran</li>
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
                <a class=" btn btn-primary py-2 px-4 py-md-3 px-md-5 animated slideInLeft text-uppercase" href="{{ route('quick.payment',['service' => $services[0]['uuid'] ?? '','name' => $service['name'] ?? '']) }}"> 
                    <span class="text-right">
                        PAYER MA TAXE 
                    </span> 
                </a>
                <br>
                <br>
                <br>

                <div class="step">
                    <h2>Méthode 2 : Création de compte et paiement</h2>
                    <div>
                        <h3>Étape 1</h3>
                        <ul>
                            <li>Aller sur le site : <a href="https://district-online.ci/" target="_blank">https://district-online.ci/</a></li>
                            <li>Cliquer sur le bouton <strong>Créer un compte</strong> en haut à droite</li>
                            <li>Remplir le formulaire de création de compte et cliquer sur <strong>Valider</strong></li>
                            <li>Cliquer sur <strong>Mes Véhicules</strong> dans le menu latéral gauche</li>
                            <li>Cliquer sur le bouton <strong>Ajouter</strong> en haut à gauche</li>
                            <li>Renseigner les informations du véhicule et cliquer sur <strong>Sauvegarder</strong></li>
                            <li>Cliquer sur <strong>Payer</strong> devant la liste affichée dans le tableau</li>
                            <li>Renseigner les informations de paiement</li>
                            <li>Cliquer sur le bouton <strong>Valider</strong></li>
                            <li>Effectuer le paiement selon le mode de paiement choisi</li>
                            <li>Télécharger le reçu affiché à l’écran</li>
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
                <a class="btn btn-primary py-2 px-4 py-md-3 px-md-5 animated slideInLeft text-uppercase" href="{{ route('register',['service' => $services[0]['uuid'] ?? '']) }}">
                    CREER UN COMPTE
                </a>
                <br>
                <br>

                <br>
                <div class="note">
                    <p><strong>NB :</strong></p>
                    <ul>
                        <li>Pour les paiements rapides, votre quittance est émise par email.</li>
                        <li>Pour les paiements avec un compte, votre quittance est disponible dans votre espace utilisateur et également émise par email.</li>
                    </ul>
                </div>

               @endisset  
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


@endsection
@push('footer-script')
@isset($services[0]['uuid'])
    <script>
        var Entity_uuid = @Json($services[0]['uuid'] ?? '');
    </script>
@endisset

<script src="{{ asset('/backoffice/js/front/indexPage.js') }}"></script>
@endpush
