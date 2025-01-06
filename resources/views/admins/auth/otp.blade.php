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
    <div class="container" style="transform: translateY(-70px);">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-6">
                <div class="bg-success rounded d-flex p-3">
                    <form class="mt-4 sendForm" action="{{ route('panel.otp.submit') }}" method="POST">
                        @csrf
                        <h3 class="text-light">FORMULAIRE DE CONNEXION BACKOFFICE</h3>
                        <h5>AUTHENTIFICATION</h5>
                        <div class="row">
                            
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="text-light mb-0" for="pwd">OTP</label>
                                        <span id="resend"  style="cursor: pointer;"
                                        data-href="{{ route('panel.otp.resend') }}" class="text-light">Renvoyer ?</span>
                                    </div>
                                    <input class="form-control mt-2" name="otp" id="pwd" type="text" placeholder="" autofocus required />
                                </div>
                                <br>
                            </div>

                            <div class="col-lg-12 text-center">
                                <button type="submit" class="btn btn-block btn-outline-light">Poursuivre</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
    document.addEventListener('DOMContentLoaded', function () {
    const resendButton = document.getElementById('resend');

    if (resendButton) {
        resendButton.addEventListener('click', function () {
            // Récupère l'URL à partir de l'attribut data-href
            const url = this.getAttribute('data-href');
            if (!url) {
                toastr.error('L\'URL n\'est pas spécifiée.', 'Echec');
                return;
            }

            // Affiche le loader
            loader();

            // Envoie une requête à l'URL
            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest', // Pour signaler une requête AJAX
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('La requête a échoué avec le statut ' + response.status);
                    }
                    return response.json(); // Parse la réponse en JSON
                })
                .then(data => {
                    if (data.type === "success") {
                        toastr.success(data.message, 'Succès');
                    } else {
                        toastr.error(data.message, 'Echec');
                    }
                })
                .catch(error => {
                    toastr.error('Une erreur est survenue : ' + error.message, 'Echec');
                })
                .finally(() => {
                    // Cache le loader
                    loader('hide');
                });
        });
    } else {
        console.warn('Le bouton avec l\'ID "resend" est introuvable dans le DOM.');
    }
});
</script>

@endsection