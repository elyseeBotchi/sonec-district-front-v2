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
      <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container"  style="transform: translateY(-70px);">
            <div class="row g-5">
                <div class="col-lg-12">
                     <div class="section-title position-relative pb-3 mb-5">
                        <h1 class="fw-bold text-primary text-uppercase text-align-justify">PAIEMENT DE LA TAXE DE STATIONNEMENT</h5>  
                        <h3 class="mb-0">Le <b>DISTRICT AUTONOME D’ABIDJAN</b>  vous fait gagner du temps avec les paiements en ligne de la Taxe de stationnement.
                            <br>
                            C’est très simple !</h3>
                    </div> 
                    <p class="mb-4" style="font-size:20px;">
                        Pour commencer, identifiez dans la liste des taxes, celle qui est applicable au type de véhicule en votre possession et ensuite payer en quelques clics

                    </p>  

                    <div class="alert alert-danger" role="alert">
                        <i class="fa fa-info-circle me-2" aria-hidden="true"></i>
                        <strong class="text-uppercase">NB :</strong>
                            <h3 style="text-align: justify">
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
                        
                        <a href="#formulaire" class="btn btn-primary py-2 px-4 py-md-3 px-md-5 animated slideInRight">
                           PAYER SA TAXE DE STATIONNEMENT
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

                <div class="col-md-12 d-flex align-items-center justify-content-center">
                        <div class="col-lg-6">
                            
                            <div class="bg-primary rounded h-100 p-5 wow">
                                <form  class="sendPayForm" action="{{ route('landing.entities.taxe.payment') }}">
                                    <h3>FORMULAIRE DE PAIEMENT</h3>
                                    <div class="alert alert-info" role="alert" id="formulaire">
                                        <i class="fa fa-info-circle me-2" aria-hidden="true"></i>
                                        <strong class="text-uppercase">Informations importantes :</strong>
                                        <ul>
                                            <li>Les tarifs sont valables pour la période du 01/01/{{ date('Y') }} au 31/12/{{ date('Y') }}.</li>
                                            <li>Les tarifs sont soumis à des modifications sans préavis.</li>
                                            <li>Les informations fournies sont sujettes à vérification.</li>
                                        </ul>
                                    </div>
                                    <br>

                                    @csrf
                                    <input type="hidden"  name="entity_uuid" id="SelectEntity" value="{{ $service_uuid ?? '' }}" required />
                                    <div class="row g-3">
                                        
                                        <div id="form-container" >
                                            <i class="fa fa-spinner fa-spin"></i>
                                        </div>
                                    
                                        <div>
                                            
                                        <div class="col-12">
                                            <label class="form-label">Date de la dernière visite <code>*</code></label>
                                            <input type="date" class="form-control bg-light border-0" placeholder="" max="{{ date('Y-m-d') }}"  name="date_visite" required="" required style="height: 40px;">
                                        </div>
                                            <div class="col-12">
                                            <label class="form-label">Type de véhicule <code>*</code></label>
                                            <select class="form-select bg-light border-0 FindLieuRDV" name="rubrique_facturation_uuid" id="rubrique" style="height: 40px;">
                                
                                            </select>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Montant à payer</label>
                                            <input type="text" class="form-control bg-light border-0" placeholder="Montant à payer"  id="montant_pay" readonly disabled  style="height: 40px;">
                                        </div>

                                        

                                            
                                        <div class="col-12">
                                            <label class="form-label">Lieu de rendez-vous <code>*</code></label>
                                            <input type="text" class="form-control bg-light border-0" placeholder="Lieu de rendez vous"  id="lieu_rdv" readonly disabled  style="height: 40px;">
                                            <input type="hidden"  name="lieu_rdv" id="list_rdv" required style="height: 40px;">
                                        
                                            {{-- <select name="lieu_rdv" id="list_rdv" class="form-control bg-light border-0" style="height: 40px;">
                                                @isset($lieuRdv)
                                                    @forelse($lieuRdv as $key => $value)
                                                        <option value="{{ $value['uuid'] }}"> {{ $value['libelle'] ?? '' }} </option>
                                                    @empty
                                                    @endforelse
                                                @endisset
                                            </select> --}}
                                        </div>
                                            
                                        <div class="col-12">
                                            <label class="form-label">
                                                Date de rendez-vous <code>*</code>
                                            </label>
                                            <select name="rdv" id="rdv" class="form-control bg-light border-0" style="height: 40px;">
                                                @isset($dateValideRdv)
                                                    @isset($dateValideRdv[0])
                                                        @forelse($dateValideRdv as $key => $value)
                                                            @if($key < $limit)
                                                                <option value="{{ $value }}"> {{ date_create($value)->format('d-m-Y') }} </option>
                                                            @endif
                                                        @empty
                                                        @endforelse
                                                    @endisset    
                                                @endisset
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Téléphone de paiement <code>*</code></label>
                                            <input type="tel" class="form-control bg-light border-0" placeholder="Téléphone de paiement"  id="num_pay"  name="numero_paiement" required="" minlength="10" maxlength="10" required style="height: 40px;">
                                        </div>



                                        <div class="col-12">
                                            <label for="prenoms" class="col-form-label">Opérateurs autorisés </label>
                                            <div class="row">
                                                @isset($operateurs)
                                                    @isset($operateurs[0])
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
                                                @endisset 
                                            </div>
                                        </div>

                                        </div>
                                        
                                        <div class="col-12">
                                            <button class="btn btn-dark w-100 py-3" id="submitBtn" type="submit" style="display: none">Payer</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>           
                </div>

            </div>
        </div>
    </div>
    <!-- Quote End -->
   
@endsection
@push('footer-script')
@isset($services[0]['uuid'])
    <script>
        var Entity_uuid = @Json($services[0]['uuid'] ?? '0d7bd150-655a-11ef-8a7c-53d21c84c368');
    </script>
    @else
    <script>
        var Entity_uuid = '0d7bd150-655a-11ef-8a7c-53d21c84c368';
    </script>
@endisset

<script src="{{ asset('/backoffice/js/front/landingPage.js') }}"></script>
@endpush
