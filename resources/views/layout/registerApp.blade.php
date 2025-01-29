<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="BENI Messan">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('template/assets/images/logo.png') }}">
    <title> {{ env('APP_NAME') }} | Création de compte </title>
    <link href="{{ asset('template/dist/css/style.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

</head>



<body>
<div class="main-wrapper">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
        @yield('content')
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- All Required js -->
<!-- ============================================================== -->
<script src="{{ asset('template/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template/assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
<script src="{{ asset('template/assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


<script>
    var _token = "{{csrf_token()}}";

    if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        // Ajouter les coordonnées GPS dans le formulaire avant soumission
        $('.sendForm').append('<input type="hidden" name="latitude_web" value="' + latitude + '">');
        $('.sendForm').append('<input type="hidden" name="longitude_web" value="' + longitude + '">');

    }, function(error) {
        console.error("Erreur de géolocalisation: ", error);
    });
} else {
    console.log("La géolocalisation n'est pas supportée par ce navigateur.");
  
}


$('.sendForm').submit(function (e) {
            e.preventDefault();

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
                    } else if (data.type === "error_validator") {
                        handleErrors(data.errors);
                        var message = "";
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
                    } else {
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

    function handleErrors(errors) {
        for (var field in errors) {
            if (errors.hasOwnProperty(field)) {
                var input = $('[name=' + field + ']');
                input.addClass('is-invalid');
                var errorMessages = errors[field].join(' ');
                input.after('<div class="invalid-feedback">' + errorMessages + '</div>');
            }
        }
    }


    function loader(state = "show") {
        switch (state) {
            case "show":
                JsLoadingOverlay.show({
                    'overlayBackgroundColor': '#666666',
                    'overlayOpacity': 0.4,
                    'spinnerIcon': 'ball-spin',
                    'spinnerColor': '#1fbd03',
                    'spinnerSize': '1x',
                    'overlayIDName': 'overlay',
                    'spinnerIDName': 'spinner',
                    'spinnerZIndex': 99999,
                    'overlayZIndex': 99998,
                    'lockScroll': true,
                });
                break;
            default:
                JsLoadingOverlay.hide();
                break;
        }
    }

    function sendSuccess(message, urlback=''){ // retour en cas de success d'envoi de formulaire
        if (urlback !== '') {
            if(urlback === 'back'){
                toastr.success(message, 'Succès');
                //Si url de retour exist
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }else {
                //Si url de retour exist
                toastr.success(message, 'Succès');
                setTimeout(() => {
                    window.location.href = urlback;
                }, 2000);
            }
        }
        else {
            //Si url de retour exist pas dans le retour du formulaire
            toastr.success(message, 'Succès');
        }
    }

    function SendError(messageError){ //fonction pour envoi de formulaire chargement loading
        toastr.error(messageError, 'Erreur');
    } //fin de la focntion SendError

</script>

<script src="{{ asset('template/dist/js/js-loading-overlay.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@stack('footer-script')

<!-- ============================================================== -->
<!-- This page plugin js -->
<!-- ============================================================== -->
<script>
    $(".preloader ").fadeOut();
</script>

</body>

</html>
