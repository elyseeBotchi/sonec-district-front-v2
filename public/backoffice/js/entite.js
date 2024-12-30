$(document).ready(function() {

    $('#civility-man').on('change', function(){
        $("#avatar-preview").attr("src", "/storage/admins/man.png");
    });
    $('#civility-woman').on('change', function(){
        $("#avatar-preview").attr("src", "/storage/admins/woman.png");
    });


    findAll();

    function findAll(){
        fetch(`/panel/entite/findAll`)
            .then(response => {
                if(!response.ok){
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                const results = data.data;
                
                // Vérifie si le tableau a déjà été initialisé
                if ($.fn.DataTable.isDataTable('#datatable-custom')) {
                    // Détruire l'instance existante
                    $('#datatable-custom').DataTable().destroy();
                }

                $('#datatable-custom').DataTable({
                   // language: frDTJson,
                    data: results,
                    columns: [
                        { data: 'name' },
                        {
                            data: 'current_version',
                            render: function(data, type, row) {
                                return 'Version '+data;
                            }
                        },
                        {
                            data: 'state',
                            render: function(data, type, row) {
                                if(data === 'init'){
                                    return `<span class="badge rounded-pill badge-secondary">En attente</span>`;
                                }else if(data === 'enable'){
                                    return `<span class="badge badge-pill badge-success">Actif</span>`;
                                }else if(data === 'disable'){
                                    return `<span class="badge rounded-pill badge-warning">Suspendu</span>`;
                                }else{
                                    return `<span class="badge rounded-pill badge-danger">Supprimé</span>`;
                                }
                            }
                        },
                        {
                            data: 'uuid',
                            render: function(data, type, row) {

                                var permissions = {
                                    show: canPermission('entites_voir_les_details_de_la_configuration_dune_entite'),
                                    edit: canPermission('entites_modifier_une_entite'),
                                    change: canPermission('entites_supprimer_une_entite')
                                };

                                var actions = '';
                                if (permissions.show) {
                                    actions += `<a href="/panel/entite/show/${data}" title="Voir le detail de l'entité " class="btn btn-outline-primary btn-icon waves-effect waves-light material-shadow-none"><i class="fa fa-eye"></i></a> &nbsp; `;
                                }
                                if (permissions.edit) {
                                    actions += `<a href="#" data-toggle="modal" data-target="#updateElement-modal" data-uuid="${data}" data-name="${row.name}" data-description="${row.description}" title="Modifier l'entité ${row.name}" class="btn btn-outline-warning btn-icon waves-effect waves-light material-shadow-none updateElement"><i class="fa fa-edit"></i></a> &nbsp; `;
                                }
                                if (permissions.change) {
                                    var icon = row.state === 'enable' ? '<i class="fa fa-lock"></i>' : '<i class="fa fa-unlock"></i>';
                                    var msg = row.state === 'enable' ? 'Verrouiller une entité' : 'Déverrouiller une entité';
                                    var className = row.state === 'enable' ? 'btn-outline-danger' : 'btn-outline-success';
                                    actions += `<a href="/panel/entite/delete" title="${msg}" class="btn btn-icon waves-effect waves-light material-shadow-none ${className} sendDeleteLink">${icon}</a>`;
                                }
                                return actions;

                            }
                        },
                    ]
                });
            })
    }

       
    $('#container').on('click', '.updateElement', function(e){
        e.preventDefault();
        const uuid = this.getAttribute('data-uuid');
        const name = this.getAttribute('data-name');
        const description = this.getAttribute('data-description');
          //  alert(name)
        document.getElementById('uuid').value = uuid;
        document.getElementById('target_update_name').innerHTML = name;
        document.getElementById('libelle').value = name;
        if(description !=="null"){
            document.getElementById('description').value = description; 
        }
    });


    //Edit
    $('#container').on('click', '.sendEditLink', function(e){
        e.preventDefault();
        loading();

        const url = $(this).attr('href');
        $.get(url, function(data){
            unloading();


            $('.updateModalBody').html(data.renderHtml);
            $(".sendUpdateForm").attr("action", data.urlback);
            $('#updateModal').modal('show');
        });
    });

    // Update
  
    $('.sendEntiteForm').submit(function (e) {
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
                    sendSuccess(data.message, data.urlback);
                    findAll()
                    const closeModalButton = document.querySelector('.closeModal');
                    if (closeModalButton) {
                        closeModalButton.click();
                    }
                    
                    const closeUpModalButton = document.querySelector('.closeUpModal');
                    if (closeUpModalButton) {
                        closeUpModalButton.click();
                    }
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
                    location.reload()
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
