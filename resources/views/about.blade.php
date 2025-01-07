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
                    <a href="{{ route('welcome.index') }}" class="nav-item nav-link">Accueil</a>
                    <a href="{{ route('about', ['service' => $services[0]['uuid'] ?? '', 'name' => $services[0]['name'] ?? '']) }}" class="nav-item nav-link active">Nous contacter</a>

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
        <div class="container" style="transform: translateY(-70px);">
            <div class="row g-5 d-flex justify-content-center" >
               {{--  <div class="col-lg-6">
                     <div class="col-lg-4 col-md-12 pt-5 mb-5">
                      
                       <div class="d-flex mb-2">
                            <i class="bi bi-telephone text-primary me-2"></i>
                            <p class="mb-0">+ 27 22 50 94 24 </p>
                        </div> 
                     
                    </div>
        
                </div>--}}

                <div class="col-lg-6">
                  
                    <div class="bg-primary rounded h-100 d-flex align-items-center p-5 wow zoomIn" data-wow-delay="0.9s">
                        
                        <form  class="sendForm" action="{{ route('about.send') }}">
                            <h3>FORMULAIRE DE PRISE DE CONTACT</h3>
                            
                            @csrf
                            <input type="hidden"  name="entity_uuid" id="SelectEntity" value="{{ $service_uuid ?? '' }}" required />
                            <div class="row g-3">
                                
                                <div class="col-12">
                                    <label class="form-label">Nom <code>*</code></label>
                                    <input type="text" class="form-control bg-light border-0" id="nom" required  style="height: 55px;">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Prénoms <code>*</code></label>
                                    <input type="text" class="form-control bg-light border-0" id="prenoms" required style="height: 55px;">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Email <code>*</code></label>
                                    <input type="text" class="form-control bg-light border-0" placeholder=""  id="email" style="height: 55px;">
                                </div>


                                <div class="col-12">
                                    <label class="form-label">Motif <code>*</code></label>
                                    <select class="form-select bg-light border-0" name="motif" id="motif" style="height: 55px;">
                                        <option value=""> Sélectionner un motif</option>
                                        <option value="paiement"> Paiement</option>
                                        <option value="type de taxe">Type de taxe de stationnement</option>
                                        <option value="autre"> Autre</option>
                                    </select>
                                </div>

                                <div class="col-12" id="autre_motif" style="display: none">
                                    <label class="form-label">Autre motif</label>
                                    <input type="text" name="autre_motif" class="form-control bg-light border-0" style="height: 55px;">
                                </div>

                                
                                <div class="col-12">
                                    <label class="form-label">
                                        Message<code>*</code>
                                    </label>
                                    <textarea name="message" id="" class="form-control" cols="30" rows="10"></textarea>
                                </div>
                                

                                

                                <div class="col-12">
                                    <button class="btn btn-dark w-100 py-3" id="submitBtn" type="submit" >Envoyer</button>
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
        document.getElementById('motif').addEventListener('change', function () {
        const autreMotifDiv = document.getElementById('autre_motif');
        if (this.value === 'autre') {
            autreMotifDiv.style.display = 'block'; // Afficher le champ
        } else {
            autreMotifDiv.style.display = 'none'; // Cacher le champ
        }
    });
    </script>
@endisset

@endpush
