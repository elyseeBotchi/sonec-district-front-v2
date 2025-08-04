$(document).ready(function() {

    findAll();

    function findAll(){
        fetch(`/panel/partenaires/findAll`)
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
                    language: {
                        url: '//cdn.datatables.net/plug-ins/2.0.2/i18n/fr-FR.json',
                    },
                    data: results,
                    columns: [
                        { data: 'name' },
                        { data: 'percent',
                            render: function(data, type, row) {
                                return data + '%';
                            }
                        },
                        {data: 'state',
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
                                return `
                                    <a href="/panel/partenaires/${data}/edit" title="Modification de l'élement ${row.name}" class="btn btn-outline-warning btn-icon waves-effect waves-light material-shadow-none sendEditModuleLink"><i class="fa fa-edit"></i></a>
                                    <a href="/panel/partenaires/${data}/delete" title="Suppression de l'élement ${row.name}" class="btn btn-outline-danger btn-icon waves-effect waves-light material-shadow-none sendDeleteLink"><i class="fa fa-trash"></i></a>
                                `;
                            }
                        },
                    ]
                });
            })
    }
    // Create
    $("#container").on('submit', '.sendModuleForm', function (e) {

        e.preventDefault();

        var action = $(this).attr('action');
        var formData = new FormData(this);

        sendForm(action, formData, function(response) {
            // Faites quelque chose avec la réponse ici
            if(response.type === 'success'){

                sendSuccess(response.message);
                $('.modal').modal('hide');
                $('.sendModuleForm')[0].reset();
                findAll();

            }else{
                SendError(response.message);
            }
        });
    });

    //Edit
    $('#container').on('click', '.sendEditModuleLink', function(e){
        e.preventDefault();
        loader();

        const url = $(this).attr('href');
        $.get(url, function(data){
            loader('hide');

            var row = '';
            const result = data.data;

            row += `<div class="form-group">
                        <label for="name" class="form-label">Partenaire</label>
                        <input type="text" name="name" class="form-control" value="${result.name}">
                    </div>`;
            row += `<div class="form-group">
                        <label for="percent" class="form-label">Pourcentage</label>
                        <input type="number" name="percent" class="form-control" value="${result.percent}" min="0" max="100" required>
                    </div>`;

            $('.updateModalBody').html(row);
            $(".sendModuleUpdateForm").attr("action", '/panel/partenaires/' + result.uuid + '/update');
            $('#updateModal').modal('show');
        });
    });

    // Update
    $("#container").on('submit', '.sendModuleUpdateForm', function (e) {

        e.preventDefault();
        var action = $(this).attr('action');
        var formData = new FormData(this);

        sendForm(action, formData, function(response) {
            // Faites quelque chose avec la réponse ici

            if(response.type === 'success'){

                sendSuccess(response.message);
                $('.modal').modal('hide');
                findAll();

            }else{
                SendError(response.message);
            }
        });
    });

    // delete
    $('#container').on('click', '.sendDeleteLink', function(e){
        e.preventDefault();

        var source = $(this);
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
                        findAll();
                    }else{
                        SendError(data.message);
                    }
                });
            }
        });
    })

    // role permission

    $('input[name=permission]').on('change', function(){
        const source = $(this);
        const uuid = source.val();
        const url = '/panel/roles/' + uuid + '/permissions/update';

        source.parent().find('.permission-load').html('<i class="fas fa-spinner fa-spin text-primary"></i>');

        $.get(url, function(data){
            if(data.type === 'success'){
                source.parent().find('.permission-load').html('<i class="fas fa-check text-success"></i>');
            }else{
                source.parent().find('.permission-load').html('<i class="fas fa-window-close text-danger"></i>');
            }

            setInterval(() => {
                source.parent().find('.permission-load').html('');
            }, 1500);
        })
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
