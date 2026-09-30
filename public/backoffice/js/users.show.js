$(document).ready(function() {

    findAll();
    findRoles();

    function findAll(){
        fetch(`/panel/collaborateurs/autorisations/${userUuid}/findAllOffice`)
            .then(response => {
                if(!response.ok){
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                const results = data.data;

                var row = '';

                results.forEach(element =>{
                    const statusBadge = element.status === 'enable'
                        ? '<span class="v2-status v2-status--success">Actif</span>'
                        : '<span class="v2-status v2-status--danger">Suspendu</span>';
                    const iconColor = element.status === 'enable' ? "btn-outline-danger" : "btn-outline-light";
                    const icon = element.status === 'enable' ? '<i class="fa fa-trash"></i>' : '<i class="fas fa-check"></i>';
                    const manager = element.is_manager ? 'OUI' : 'NON';
                    row += `<tr>
                                <td>${element.role_name} ${statusBadge}</td>
                                <td>
                                    <a href="/panel/collaborateurs/${element.uuid}/officeChangeStatus" class="sendDeleteLink btn btn-icon waves-effect waves-light material-shadow-none ${iconColor}">${icon}</a>
                                </td>
                            </tr>`;/* <td class="${textColor}">${element.heading_name ? (element.heading_name) : (`Non Précisé`)}</td>*/
                });

                $('#roles-table tbody').html(row);
            })
    }

    function findRoles(){
        fetch(`/panel/roles/findAll`)
            .then(response => {
                if(!response.ok){
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                const results = data.data;

                var row = '';

                results.forEach(element =>{
                    row += `<option value="${element.uuid}">${element.name}</option>`;
                });

                $('#role-uuid').html(row);
            })
    }


    // Create
    $(".sendCreateForm").on('submit', function (e) {

        e.preventDefault();
        const action = $(this).attr('action');
        const formData = new FormData(this);

        sendForm(action, formData, function(response) {
            // Faites quelque chose avec la réponse ici

            if(response.type === 'success'){

                sendSuccess(response.message);
                $('.modal').modal('hide');
                $('.sendCreateForm')[0].reset();
                findAll();

            }else{
                SendError(response.message);
            }
        });
    });

    // delete
    $('#container').on('click', '.sendDeleteLink', function(e){
        e.preventDefault();

        var action = $(this).attr('href');
        var caption = $(this).attr('caption');

        Swal.fire({
            icon : 'warning',
            title: 'Attention !',
            text: caption ? caption : 'Vous êtes sur le point d\'effectuer un changement',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: `OUI, CONTINUER`,
            denyButtonText: `NON, FERMER`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {

                loader();

                $.get(action, function(data){
                    loader('hide');

                    if(data.type === 'success'){
                        sendSuccess(data.message);
                        findAll();
                    }else{
                        SendError(data.message);
                    }
                });
            }
        });
    });
});





function sendForm(action, formData, callback) {
    $.ajax({
        url: action,
        type: 'POST',
        data: formData,
        // dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            loader();
        },
        success: function (data) {
            loader('hide');
            callback(data); // Appel de la fonction de rappel avec la réponse
        },
        error: function (data) {
            if (data.type === "error") {
                SendError("messageError");
            }
        },
        cache: false,
        contentType: false,
        processData: false
    });
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
