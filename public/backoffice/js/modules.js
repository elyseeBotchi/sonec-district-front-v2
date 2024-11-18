$(document).ready(function() {

    findAll();

    function findAll(){

        fetch(`/panel/modules/findAll`)
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
                        {
                            data: 'uuid',
                            render: function(data, type, row) {
                                return `
                                    <a href="/panel/modules/permissions/${data}/index" title="Configuration de l'élement ${row.name}" class="btn btn-outline-primary btn-icon waves-effect waves-light material-shadow-none"><i class="fa fa-cogs"></i></a>
                                    <a href="/panel/modules/${data}/edit" title="Modification de l'élement ${row.name}" class="btn btn-outline-warning btn-icon waves-effect waves-light material-shadow-none sendEditModuleLink"><i class="fa fa-edit"></i></a>
                                    <a href="/panel/modules/${data}/delete" title="Voulez vous supprimer l'élement ?" class="btn btn-outline-danger btn-icon waves-effect waves-light material-shadow-none m-1 sendDeleteLink"><i class="fa fa-trash"></i></a>
                                `;
                            }
                        },
                    ],
                    destroy: true,
                    responsive: true,
                    dom: '<"top"f>rt<"bottom"lp><"clear">'
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
                        <label for="name" class="form-label">Module</label>
                        <input type="text" name="name" class="form-control" value="${result.name}">
                    </div>`;

            $('.updateModalBody').html(row);
            $(".sendModuleUpdateForm").attr("action", '/panel/modules/' + result.uuid + '/update');
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
