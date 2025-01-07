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
      <div class="container-fluid py-5 wow " >
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
                  
                    <div class="bg-primary rounded h-100 d-flex align-items-center p-5 wow" >
                        
                        <form  class="sendEmailForm" action="{{ route('about.send') }}">
                            <h3>FORMULAIRE DE PRISE DE CONTACT</h3>
                            
                            @csrf
                            <input type="hidden"  name="entity_uuid" id="SelectEntity" value="{{ $service_uuid ?? '' }}" required />
                            <div class="row g-3">
                                
                                <div class="col-12">
                                    <label class="form-label">Nom <code>*</code></label>
                                    <input type="text" name="nom" class="form-control bg-light border-0" id="nom" required  style="height: 55px;">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Prénoms <code>*</code></label>
                                    <input type="text" name="prenom" class="form-control bg-light border-0" id="prenoms" required style="height: 55px;">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Email <code>*</code></label>
                                    <input type="email" name="email" class="form-control bg-light border-0" placeholder=""  id="email" style="height: 55px;">
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
                                    <div id="quill-editor" style="height: 300px; background-color:white;"></div>
                                    <textarea name="message" id="message"class="d-none" style="width: 100%" cols="30" rows="10"></textarea>
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
   
   
<!-- Include Quill.js -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<script>
    // Initialisation de Quill
    const quill = new Quill('#quill-editor', {
        theme: 'snow', // Thème clair
        placeholder: 'Écrivez votre message ici...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'], // Styles de texte
                ['link', 'image'], // Liens et images
                [{ 'list': 'ordered' }, { 'list': 'bullet' }], // Listes
                [{ 'align': [] }], // Alignement
                ['clean'] // Effacer le formatage
            ]
        }
    });


    $('.sendEmailForm').submit(function (e) {
    e.preventDefault();
    const messageTextarea = document.getElementById('message');
        messageTextarea.value = quill.root.innerHTML;

    var action = $(this).attr('action');
    var formData = new FormData(this);
    $.ajax({
        url: action,
        type: 'POST',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            loader();
            // Remove previous error styles and messages
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
        },
        success: function (data) {
            loader('hide');
            if (data.type === "success") {
                // Handle success scenarios
                sendSuccess(data.message, data.urlback);
            }
            else if (data.type === "error_validator") {
                handleErrors(data.errors);
                var message = ""
                if (data.errors) {
                    $.each(data.errors, function (key, value) {
                        message += value.join('<br>') + '<br>';
                    });
                }

                toastr.error(message, 'Erreur', {
                    closeButton: true,
                    progressBar: true,
                    enableHtml: true  // Activer le support HTML pour les messages toastr
                });
            }
            else {
                SendError(data.message);
            }
        },
        error: function (xhr) {
            loader('hide');
            var errors = xhr.responseJSON.errors;
            handleErrors(errors);
            SendError('Veuillez corriger les erreurs ci-dessous.');
        },
        cache: false,
        contentType: false,
        processData: false
    });
});
    // Synchroniser le contenu Quill avec le textarea pour l'envoi du formulaire
   
</script>
@endisset

@endpush
