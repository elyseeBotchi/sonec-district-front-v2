$(document).ready(function() {

    $('#civility-man').on('change', function(){
        $("#avatar-preview").attr("src", "/storage/admins/man.png");
    });
    $('#civility-woman').on('change', function(){
        $("#avatar-preview").attr("src", "/storage/admins/woman.png");
    });


    findAll();

    function findAll(){
        fetch(`/panel/collaborateurs/autorisations/findAll`)
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
                        {
                            data: 'firstname',
                            render: function(data, type, row) {
                                return data+' '+row.lastname;
                            }
                        },
                        { data: 'email' },
                        { data: 'phone' },
                        {
                            data: 'status',
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
                                    show: canPermission('collaborateur_assigner_un_role_a_un_collaborateur'),
                                    change: canPermission('collaborateur_activer_ou_desactiver_un_collaborateur')
                                };

                                var actions = '';
                                if (permissions.show) {
                                    actions += `<a href="/panel/collaborateurs/${data}/show" title="Voir le detail du collaborateur" class="btn btn-outline-primary btn-icon waves-effect waves-light material-shadow-none"><i class="fa fa-eye"></i></a> &nbsp; `;
                                }
                                if (permissions.change) {
                                    var icon = row.status === 'enable' ? '<i class="fa fa-lock"></i>' : '<i class="fa fa-unlock"></i>';
                                    var msg = row.status === 'enable' ? 'Verrouiller le collaborateur' : 'Déverrouiller le collaborateur';
                                    var className = row.status === 'enable' ? 'btn-outline-danger' : 'btn-outline-success';
                                    actions += `<a href="/panel/collaborateurs/${data}/change" title="${msg}" class="btn btn-icon waves-effect waves-light material-shadow-none ${className} sendDeleteLink">${icon}</a>`;
                                }
                                return actions;

                            }
                        },
                    ]
                });
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

                setTimeout(() => {
                    window.location.href = response.urlback;
                }, 500);

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

    // role permission

    $('input[name=permission]').on('change', function(){
        const source = $(this);
        const uuid = source.val();
        const url = '/panel/gi/access/roles/' + uuid + '/permissions/update';

        source.parent().find('.permission-load').html('<i class="fas fa-spinner fa-spin text-primary"></i>');

        $.get(url, function(data){
            if(data.type === 'success'){
                source.parent().find('.permission-load').html('<i class="fas fa-check text-success"></i>');
            }else{
                source.parent().find('.permission-load').html('<i class="fas fa-window-close text-danger"></i>');
            }

            setTimeout(() => {
                source.parent().find('.permission-load').html('');
            }, 1500);
        })
    });

    $('#group_uuid').on('change', function(){
        const source = $(this);
        const url = '/panel/gi/setting/groups/' + source.val()  + '/sub-group';

        loading();
        $.get(url, function(data){
            unloading();

            $('.group-children-block').removeClass('d-none');

            $('#sub_group_uuid').html('');

            for(i=0; i<data.length; i++){
                $('#sub_group_uuid').append('<option value="' + data[i].uuid + '">' + data[i].name + '</option>');
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

    $('#container').on('change', '#show_manager_uuid', function(e){

        const source = $(this);
        const managerUuid = source.val();
        const userUuid = source.attr('data-uuid');

        const url = `/panel/gi/users/${userUuid}/${managerUuid}/add`;

        $.get(url, function(response){
            sendSuccess(response.message);

            setTimeout(() => {
                location.reload();
            }, 1000);
        })
    });

    $('input[name=signature]').on('change', function(){
        $('.sendChangeSigningForm').submit();
    });

    $('.sendChangeSigningForm').on('submit', function(e){
        e.preventDefault();

        var action = $(this).attr('action');
        var formData = new FormData(this);

        sendForm(action, formData, function(response) {
            // Faites quelque chose avec la réponse ici

            if(response.type === 'success'){

                $('#preview-avatar').attr('src', '/storage/' + response.path);
            }else{
                SendError(response.message);
            }
        });
    })
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
