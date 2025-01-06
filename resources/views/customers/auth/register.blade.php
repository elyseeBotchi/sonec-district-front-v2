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

        <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 90px;">
            <div class="row py-5">
                <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                    <h1 class="display-4 text-white animated zoomIn">DISTRICT AUTONOME D'ABIDJAN</h1>
                    <a href="" class="h5 text-white">Plateforme digitale de délivrance de la carte de stationnement</a>
                    {{-- <i class="far fa-circle text-white px-2"></i>
                    <a href="" class="h5 text-white">About</a> --}}
                </div>
            </div>
        </div>
    </div>
    <!-- Navbar & Carousel End -->

 <!-- Quote Start -->
 <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="section-title position-relative pb-3 mb-5">
                    {{-- <h5 class="fw-bold text-primary text-uppercase">
                        CREATION DE COMPTE
                    </h5> --}}
                      <h6 id="TaxeEntity" style="display: none"></h6>
                    <h1 class="mb-0">
                        Ce service est destiné aux particuliers et aux professionnels disposant d’une flotte de véhicules.
                    </h1>
                </div>
              
                <p class="mb-4" style="font-size: 20px">
                    Vous pourrez gérer et suivre les paiements de l’ensemble de votre flotte dans cet espace. 
                    <br>
                    Pour créer votre compte il vous faut juste renseigner le formulaire avec les informations du propriétaire et des véhicules . 
                </p>

                <div class="step">
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
                    <a href="#formulaire" 
                       class="btn btn-primary py-2 px-4 py-md-3 px-md-5 animated slideInLeft">
                       CREER MON COMPTE
                    </a>
                    
                    <a href="{{ route('login') }}" class="btn btn-primary py-2 px-4 py-md-3 px-md-5 animated slideInRight">
                       ACCEDER A MON COMPTE
                    </a>  {{----}} 
                </div>

                <h3 class="text-center mb-4" style="display: none">LISTE DES TAXES</h3>
                <div class="table-responsive"  style="display: none">
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

            <div class="col-lg-6">
              
                <div  style='background-color:#e69d64;' class="rounded h-100 d-flex p-5 wow zoomIn" data-wow-delay="0.9s">
                            
                        <form class="mt-4 sendForm" id="formulaire" action="{{ route('customer.register.submit') }}" method="POST">
                            @csrf
                            <h3>FORMULAIRE DE CREATION DE COMPTE</h3>

                            {{-- <div class="alert alert-info" role="alert">
                                <i class="fa fa-info-circle me-2" aria-hidden="true"></i>
                                <strong class="text-uppercase">Informations importantes :</strong>
                                <ul>
                                    <li>Les informations fournies sont sujettes à vérification.</li>
                                </ul>
                            </div><br> --}}
                            
                           
                                <div class="col-md-12 row">
                                     <input type="hidden" name="entity_uuid" value="{{ $entity_uuid ?? '' }}" required>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="text-dark" for="uname">Email</label>
                                            <input class="form-control" name="email" id="uname" type="email"
                                                   placeholder="Email" required/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="text-dark" for="pwd">Mot de passe</label>
                                            <input class="form-control" name="password" id="pwd" type="password" placeholder="" required />
                                        </div>
                                    </div>
            
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="text-dark" for="pwd">Confirmer votre mot de passe</label>
                                            <input class="form-control" name="confirm" id="confirm_pwd" type="password" placeholder="" required />
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="text-dark" for="pwd">Civilité</label>
                                            <select name="civility_uuid" class="form-control" id="">
                                                <option value="m">Monsieur</option>
                                                <option value="Mme">Madame</option>
                                                <option value="Mlle">Mademoiselle</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="text-dark" for="pwd">Nom</label>
                                            <input class="form-control" name="firstname" id="firstname" type="text" placeholder="" required />
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="text-dark" for="pwd">Prénoms</label>
                                            <input class="form-control" name="lastname" id="lastname" type="text" placeholder="" required />
                                        </div>
                                    </div>
                                </div>
                            
                                <div id="form-container" class="col-md-12 row"> <span class="fa fa-spinner fa-spin"></span> </div>
        
                                <br>
                                <center>
                                     <div class="col-lg-3 text-center">
                                        <button type="submit" class="btn btn-block btn-primary">Valider</button>
                                    </div>
                                </center>
                               
                            
                        </form> 
                </div>
            </div>
        </div>
    </div>
</div>


@endsection



@push('footer-script')
    <script>
        
        var Entity_uuid = @Json($entity_uuid);
        /* ########################################################## */
    </script>

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/front/register.js') }}"></script>
@endpush