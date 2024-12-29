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
                    {{-- <a href="about.html" class="nav-item nav-link">About</a>
                    <a href="service.html" class="nav-item nav-link">Services</a> --}}
                    <div class="nav-item dropdown">
                        {{-- <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Blog</a>
                        <div class="dropdown-menu m-0">
                            <a href="blog.html" class="dropdown-item">Blog Grid</a>
                            <a href="detail.html" class="dropdown-item">Blog Detail</a>
                        </div> --}}
                    </div>
                    <div class="nav-item dropdown">
                        {{-- <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu m-0">
                            <a href="price.html" class="dropdown-item">Pricing Plan</a>
                            <a href="feature.html" class="dropdown-item">Our features</a>
                            <a href="team.html" class="dropdown-item">Team Members</a>
                            <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                            <a href="quote.html" class="dropdown-item">Free Quote</a>
                        </div> --}}
                    </div>
                    {{-- <a href="contact.html" class="nav-item nav-link">Contact</a> --}}
                </div>
                {{-- <butaton type="button" class="btn text-primary ms-3" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="fa fa-search"></i>
                </butaton> --}}
                <a href="{{ route('register', ['service' => $services[0]['uuid'] ?? '', 'name' => $services[0]['name'] ?? '']) }}" class="btn btn-primary py-2 px-4 ms-3">Créer un compte</a>

                <a href="{{ route("login") }}" class="btn btn-primary py-2 px-4 ms-3">Connectez-vous</a>
            </div>
        </nav>

    </div>
    <!-- Navbar & Carousel End -->

      <!-- Quote Start -->
      <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-7">
                    <div class="section-title position-relative pb-3 mb-5">
                        <h5 class="fw-bold text-primary text-uppercase">PAIEMENT RAPIDE @isset($service_name) DE {{ $service_name ?? '' }} @endisset</h5>
                        <h1 class="mb-0">Payez vos taxes en toute simplicité !</h1>
                    </div>
                   
                    <p class="mb-4">
                        Gagnez du temps avec notre plateforme de paiement rapide et sécurisé. Que ce soit pour les taxes de stationnement réglez vos obligations en quelques clics seulement, sans tracas ni files d'attente. Facile, rapide et efficace — simplifiez vos démarches dès aujourd'hui !                    
                    </p>

                 
                    {{-- 
                    <div class="d-flex align-items-center mt-2 wow zoomIn" data-wow-delay="0.6s">
                        <div class="bg-primary d-flex align-items-center justify-content-center rounded" style="width: 60px; height: 60px;">
                            <i class="fa fa-phone-alt text-white"></i>
                        </div>
                    </div>

                    <div class="row gx-3">
                        <div class="col-sm-6 wow zoomIn" data-wow-delay="0.2s">
                            <h5 class="mb-4"><i class="fa fa-reply text-primary me-3"></i>Reply within 24 hours</h5>
                        </div>
                        <div class="col-sm-6 wow zoomIn" data-wow-delay="0.4s">
                            <h5 class="mb-4"><i class="fa fa-phone-alt text-primary me-3"></i>24 hrs telephone support</h5>
                        </div>
                    </div> --}}

                    <h3 class="text-center mb-4">LISTE DES TAXES</h3>
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

                <div class="col-lg-5">
                  
                    <div class="bg-primary rounded h-100 d-flex align-items-center p-5 wow zoomIn" data-wow-delay="0.9s">
                        
                        <form  class="sendPayForm" action="{{ route('landing.entities.taxe.payment') }}">
                            <div class="alert alert-info" role="alert">
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
                                
                                <div id="form-container">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Type de véhicule <code>*</code></label>
                                    <select class="form-select bg-light border-0" name="rubrique_facturation_uuid" id="rubrique" style="height: 55px;">
                        
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Montant à payer</label>
                                    <input type="text" class="form-control bg-light border-0" placeholder="Montant à payer"  id="montant_pay" readonly disabled  style="height: 55px;">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Téléphone de paiement <code>*</code></label>
                                    <input type="tel" class="form-control bg-light border-0" placeholder="Téléphone de paiement"  id="num_pay"  name="numero_paiement" required="" minlength="10" maxlength="10" required  style="height: 55px;">
                                </div>

                                <div class="col-12">
                                    <label for="prenoms" class="col-form-label">Opérateurs autorisés </label>
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
    <!-- Quote End -->
   
@endsection
@push('footer-script')
@isset($services[0]['uuid'])
    <script>
        var Entity_uuid = @Json($services[0]['uuid'] ?? '');
    </script>
@endisset

<script src="{{ asset('/backoffice/js/front/landingPage.js') }}"></script>
@endpush
