


// Attacher l'événement input au champ de texte
$('.live-update').on('input', function() {
    // Soumettre le formulaire via AJAX
    var form = $(this).closest('form');
    var action = form.attr('action');
    var formData = form.serialize(); // Sérialiser les données du formulaire

    $.ajax({
        url: action,
        type: 'GET',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            $("#result-view").html("<center>Recherche en cours... </center>");
            // Remove previous error styles and messages
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
        },
        success: function(data) {
            $("#result-view").html("");
            if (data.type === "success") {
                if (data.dataReturn !== undefined && data.dataReturn !== "") {
                    $("#" + data.dataTarget).html(data.dataReturn);
                }
            } else if (data.type === "error_validator") {
                handleErrors(data.errors);
                var message = "";
                if (data.errors) {
                    $.each(data.errors, function(key, value) {
                        message += value.join('<br>') + '<br>';
                    });
                }

                toastr.error(message, 'Erreur', {
                    closeButton: true,
                    progressBar: true,
                    enableHtml: true // Activer le support HTML pour les messages toastr
                });
            } else {
                $("#result-view").html("");
                // SendError(data.message);
            }
        },
        error: function(xhr) {
            $("#result-view").html("");
            var errors = xhr.responseJSON.errors;
            handleErrors(errors);
            // SendError('Veuillez corriger les erreurs ci-dessous.');
        },
        cache: false,
    });
});

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
                'overlayBackgroundColor': '#101b3d',
                'overlayOpacity': 0.45,
                'spinnerIcon': 'ball-spin',
                'spinnerColor': '#ff7a1a',
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
            toastr.options.positionClass = "toast-top-right";
            toastr.success(message, 'Succès');
            //Si url de retour exist
            setTimeout(() => {
                location.reload();
            }, 2000);
        }else {
            //Si url de retour exist
            toastr.options.positionClass = "toast-top-right";
            toastr.success(message, 'Succès');
            setTimeout(() => {
                window.location.href = urlback;
            }, 2000);
        }
    }
    else {
        //Si url de retour exist pas dans le retour du formulaire
        toastr.options.positionClass = "toast-top-right";
        toastr.success(message, 'Succès');
    }
}

function SendError(messageError){ //fonction pour envoi de formulaire chargement loading
    toastr.options.positionClass = "toast-top-right";
    toastr.error(messageError, 'Erreur');
} //fin de la focntion SendError

// Vérifiez si l'élément uplfile existe avant d'ajouter un écouteur d'événement
const uplfileElement = document.getElementById('uplfile');
if (uplfileElement) {
    uplfileElement.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                const previousImage = document.getElementById('avatarPreview').src; // Sauvegarder l'image précédente

                reader.onload = function(event) {
                    document.getElementById('avatarPreview').src = event.target.result;
                };
                reader.readAsDataURL(file);

                // Préparer FormData pour l'envoi
                const formData = new FormData();
                formData.append('avatar', file);

                // Envoyer la requête au serveur
                fetch(`/panel/securite/compte/update/avatar`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        document.getElementById('avatarPreview').src = previousImage; // Restaurer l'ancienne image
                        sendSuccess(data.message, '');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    document.getElementById('avatarPreview').src = previousImage; // Restaurer l'ancienne image en cas d'erreur réseau
                    SendError('Erreur de réseau. L\'ancienne image a été restaurée.');
                });
            } else {
                SendError('Veuillez sélectionner une image valide.');
            }
        }
    });
}

// Vérifiez si l'élément uplfile existe avant d'ajouter un écouteur d'événement
const uploadfileElement = document.getElementById('uploadfile');
if (uploadfileElement) {
    uploadfileElement.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                const previousImage = document.getElementById('avatarPreview').src; // Sauvegarder l'image précédente

                reader.onload = function(event) {
                    document.getElementById('avatarPreview').src = event.target.result;
                };
                reader.readAsDataURL(file);

                // Préparer FormData pour l'envoi
                const formData = new FormData();
                formData.append('avatar', file);

                // Envoyer la requête au serveur
                fetch(`/customer/securite/compte/update/avatar`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        document.getElementById('avatarPreview').src = previousImage; // Restaurer l'ancienne image
                        sendSuccess(data.message, '');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    document.getElementById('avatarPreview').src = previousImage; // Restaurer l'ancienne image en cas d'erreur réseau
                    SendError('Erreur de réseau. L\'ancienne image a été restaurée.');
                });
            } else {
                SendError('Veuillez sélectionner une image valide.');
            }
        }
    });
}

       
$('#container').on('click', '.addFacturation', function(e){
    e.preventDefault();
    const rubrique_uuid = this.getAttribute('data-rubrique_uuid');
    const rubrique_option_uuid = this.getAttribute('data-rubrique_option_uuid');
    const target = this.getAttribute('data-target_rule');
    const name = this.getAttribute('data-name');

    //alert(uuid)
    document.getElementById('update_rubrique_target').value = rubrique_uuid;
    document.getElementById('update_rubrique_option_target').value = rubrique_option_uuid;
    document.getElementById('update_target_rule').value = target;
    document.getElementById('title_name').innerHTML = name;
    document.getElementById('update_element').value = name;


});

/* document.getElementById('uplfile').addEventListener('change', function(e) {
    if (e.target.files.length > 0) {
        const file = e.target.files[0];
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            const previousImage = document.getElementById('avatarPreview').src; // Sauvegarder l'image précédente

            reader.onload = function(event) {
                document.getElementById('avatarPreview').src = event.target.result;
            };
            reader.readAsDataURL(file);

            // Préparer FormData pour l'envoi
            const formData = new FormData();
            formData.append('avatar', file);

            // Envoyer la requête au serveur
            fetch(`/panel/securite/compte/update/avatar`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        document.getElementById('avatarPreview').src = previousImage; // Restaurer l'ancienne image
                       sendSuccess(data.message,'')
                       // alert('Erreur lors de la mise à jour de l\'avatar. L\'ancienne image a été restaurée.');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    document.getElementById('avatarPreview').src = previousImage; // Restaurer l'ancienne image en cas d'erreur réseau
                    SendError(data.message);
                   // alert('Erreur de réseau. L\'ancienne image a été restaurée.');
                });
        } else {
            SendError('Veuillez sélectionner une image valide.');
         //   alert('Veuillez sélectionner une image valide.');
        }
    }
});
 */

/*
document.getElementById('resend').addEventListener('click', function() {
    // Récupère l'URL à partir de l'attribut data-href
    const url = this.getAttribute('data-href');
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
                loader('hide');
                throw new Error('La requête a échoué avec le statut ' + response.status);
            }
            return response.json(); // Parse la réponse en JSON
        })
        .then(data => {
            loader('hide');
            if(data.type ==="success"){
                sendSuccess(data.message, data.urlback);

            }else{
                toastr.error(data.message, 'Echec');
            }
        })
        .catch(error => {
            loader('hide');
            toastr.error('Une erreur est survenue lors de la déconnexion.', 'Echec');
        });
}); */


