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
                
                    <div class="nav-item dropdown"></div>
                    <div class="nav-item dropdown"></div>
                </div>
 
                <a href="{{ route('register', ['service' => $services[0]['uuid'] ?? '', 'name' => $services[0]['name'] ?? '']) }}" class="btn btn-primary py-2 px-4 ms-3">Créer un compte</a>

                <a href="{{ route("login") }}" class="btn btn-primary py-2 px-4 ms-3">Connectez-vous</a>
            </div>
        </nav>

    </div>


    <div class="container-fluid py-5 wow">
        <div class="container py-5">
            <div class="section-title position-relative pb-3 mb-5">
                <h5 class="fw-bold text-primary text-uppercase">LISTE DES TAXES</h5>
                <h1 class="mb-0">Vous avez la possibilité de payer votre taxe ou de créer un compte pour pouvoir gerer l'ensemble de vos taxes !</h1>
            </div>
           
            <p class="mb-4">
                           
             </p>

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
                <center>
                    <a href="{{ route('quick.payment', ['service' => $services[0]['uuid'] ?? '', 'name' => $services[0]['name'] ?? '']) }}" 
                        class="btn btn-primary py-2 px-4 py-md-3 px-md-5 animated slideInLeft">
                        PAYER LA TAXE
                    </a>
                </center>
            </div>
        </div>
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


   
@endsection
@push('footer-script')
@isset($services[0]['uuid'])
    <script>
        var Entity_uuid = @Json($services[0]['uuid'] ?? '');
    </script>
@endisset

<script src="{{ asset('/backoffice/js/front/indexPage.js') }}"></script>
@endpush
