$(document).ready(function() {

    findAll();
    function findAll() {
      //  alert(Entity_uuid);
        fetch(`/panel/entite/gabari/findAll/${Entity_uuid}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                const results = data.data || [];

                const entity = data.entity;
                //console.log(entity);

                let elements = document.getElementsByClassName('entity_name');
                for (let i = 0; i < elements.length; i++) {
                    elements[i].innerHTML = entity.name;
                }
                // Vérifie si le tableau a déjà été initialisé
                if ($.fn.DataTable.isDataTable('#datatable-custom')) {
                    // Détruire l'instance existante
                    $('#datatable-custom').DataTable().clear().destroy();
                }
                 //   console.log(results)
                // Initialiser le DataTable avec ou sans données
                $('#datatable-custom').DataTable({
                    // language: frDTJson,
                    data: results,
                    columns: [
                        {
                            data: 'name',
                            render: function (data, type, row) {
                                return `<span class="v2-cell-icon"><span class="v2-cell-icon__glyph v2-cell-icon__glyph--success"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg></span>${data || 'N/A'}</span>`;
                            }
                        },
                        { data: 'created_at' },
                        {
                            data: 'admin_firstname',
                            render: function (data, type, row) {
                                return (data ? data : '') + ' ' + (row.admin_lastname ? row.admin_lastname : '');
                            }
                        },
                        { data: 'validate_date' },
                        {
                            data: 'validate_firstname',
                            render: function (data, type, row) {
                                return (data ? data : '') + ' ' + (row.validate_lastname ? row.validate_lastname : '');
                            }
                        },
                        {
                            data: 'state',
                            render: function (data, type, row) {
                                if (data === 'init' || data ==="pending") {
                                    return `<span class="v2-status v2-status--pending">En attente</span>`;
                                } else if (data === 'success') {
                                    return `<span class="v2-status v2-status--success">Validé</span>`;
                                } else if (data === 'enable') {
                                    return `<span class="v2-status v2-status--success">Actif</span>`;
                                } else if (data === 'disable') {
                                    return `<span class="v2-status v2-status--pending">Suspendu</span>`;
                                }  else if (data === 'fails') {
                                    return `<span class="v2-status v2-status--danger">rejeté</span>`;
                                } else {
                                    return `<span class="v2-status v2-status--danger">Supprimé</span>`;
                                }
                            }
                        },
                        {
                            data: 'uuid',
                            render: function (data, type, row) {
                                var permissions = {
                                    //show: canPermission('collaborateur_assigner_un_role_a_un_collaborateur'),
                                    change: canPermission('gabaris_valider_linsertion_des_donnees_dun_gabari')
                                };
    
                                var actions = '';
    
                                if (permissions.change && row.state === 'pending') {
                                    var icon = '<i class="fa fa-clipboard-check "></i>';
                                    var msg = 'Valider le fichier';
                                    var className = 'btn-outline-success';
                                    actions += `<a href="/panel/entite/gabari/validate/${data}/true" title="${msg}" class="btn btn-icon waves-effect waves-light material-shadow-none ${className} sendDeleteLink" caption ="Vous êtes sur le point d'inscrire les données du gabari">${icon}</a>`;

                                    var icon = '<i class="fa fa-trash"></i>';
                                    var msg = 'rejeter le fichier';
                                    var className = 'btn-outline-danger';
                                    actions += `&nbsp; <a href="/panel/entite/gabari/validate/${data}/false"  title="${msg}" class="btn btn-icon waves-effect waves-light material-shadow-none ${className} sendDeleteLink" caption ="Vous êtes sur le point de rejeter les données du gabari">${icon}</a>`;
                                }
                                return actions;
                            }
                        },
                    ],
                    // Si pas de données, afficher un message
                    language: {
                        emptyTable: "Aucune donnée disponible dans le tableau",
                        loadingRecords: "Chargement en cours...",
                    }
                });
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
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
                findAll()

            }else{
                SendError(response.message);
            }
        });
    });


    // Create role company
    $("#container").on('submit', '.sendFonctionCreateForm', function (e) {

        e.preventDefault();
        const action = $(this).attr('action');
        const formData = new FormData(this);

        sendForm(action, formData, function(response) {
            // Faites quelque chose avec la réponse ici

            if(response.type === 'success'){

                sendSuccess(response.message, response.urlback);
                $('.render-html').prepend('<li class="list-group-item px-0 pt-0" id="' + response.uuid + '">' + response.renderHtml + '</li>');
                $('.sendFonctionCreateForm')[0].reset();
            }else{
                SendError(response.message);
            }
        });
    });



    // Update
    $("#container").on('submit', '.sendUpdateForm', function (e) {

        e.preventDefault();
        var action = $(this).attr('action');
        var formData = new FormData(this);

        sendForm(action, formData, function(response) {
            // Faites quelque chose avec la réponse ici

            if(response.type === 'success'){

                sendSuccess(response.message, response.urlback);
                $('.modal').modal('hide');

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
            title: 'Attention Cette action est irreversible !',
            text: caption ? caption : 'Vous êtes sur le point d\'effectuer un changement ',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: `OUI, CONTINUER`,
            denyButtonText: `NON, FERMER`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {

                loader();

                $.get(`${action}`, function(data){
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

    // role permission

     //Edit
     $('#container').on('click', '.sendChangeLink', function(e){
        e.preventDefault();


        Swal.fire({
            icon : 'warning',
            title: 'Attention !',
            text: 'Vous êtes sur le point de changer le statut du collaborateur',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: `OUI, CONTINUER`,
            denyButtonText: `NON, FERMER`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {

                loading();

                const url = $(this).attr('href');
                $.get(url, function(data){
                    unloading();
                   findAll()
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
