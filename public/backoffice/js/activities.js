$(document).ready(function() {

    $('#civility-man').on('change', function(){
        $("#avatar-preview").attr("src", "/storage/admins/man.png");
    });
    $('#civility-woman').on('change', function(){
        $("#avatar-preview").attr("src", "/storage/admins/woman.png");
    });

    findStat();
    findAll();
    findAgent();

    function findAll(){
        fetch(`/panel/activity/agents/load/all/activities`)
            .then(response => {
                if(!response.ok){
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                const results = data.data;
                
                        
                var permissions = {
                    historique_des_controles: canPermission('activites_voir_lhistorique_des_controles'),
                };
                
                if(permissions.historique_des_controles){
                //console.log(results)
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
                                return row.civility_uuid+' '+data+' '+row.lastname;
                            }
                        },
                        { data: 'created_at' },
                        {
                            data: 'data_scanne',
                            render: function(data, type, row) {
                              let data_scanne =  JSON.parse(data);
                               // console.log(data_scanne)
                                return data_scanne.OTP;
                            }
                        },
                        {
                            data: 'element',
                            render: function(data, type, row) {
                              //  console.log(data);
                                if (data && data.numero_de_la_carte_grise) {
                                    return data.numero_de_la_carte_grise;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            data: 'status',
                            render: function(data, type, row) {
                                if(data === 'init'){
                                    return `<span class="v2-status v2-status--pending">En attente</span>`;
                                }else if(data === 'enable'){
                                    return `<span class="v2-status v2-status--success">Actif</span>`;
                                }else if(data === 'disable'){
                                    return `<span class="v2-status v2-status--pending">Suspendu</span>`;
                                }else if(data === 'error'){
                                    return `<span class="v2-status v2-status--danger">Incorrect</span>`;
                                }else if(data === 'success'){
                                    return `<span class="v2-status v2-status--success">Valide</span>`;
                                }
                                else if(data === 'expire'){
                                    return `<span class="v2-status v2-status--pending">Expiré</span>`;
                                }
                                else{
                                    return ``;
                                }
                            }
                        },
                    ]
                });
                }
            })
    }

    
    function searchData(datas){
        const results = datas;
        //console.log(results)
        // Vérifie si le tableau a déjà été initialisé
        var permissions = {
            historique_des_controles: canPermission('activites_voir_lhistorique_des_controles'),
        };
        
        if(permissions.historique_des_controles){
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
                        return row.civility_uuid+' '+data+' '+row.lastname;
                    }
                },
                { data: 'created_at' },
                {
                    data: 'data_scanne',
                    render: function(data, type, row) {
                      let data_scanne =  JSON.parse(data);
                       // console.log(data_scanne)
                        return data_scanne.OTP;
                    }
                },
                {
                    data: 'element',
                    render: function(data, type, row) {
                      //  console.log(data);
                        if (data && data.numero_de_la_carte_grise) {
                            return data.numero_de_la_carte_grise;
                        } else {
                            return '';
                        }
                    }
                },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        if(data === 'init'){
                            return `<span class="v2-status v2-status--pending">En attente</span>`;
                        }else if(data === 'enable'){
                            return `<span class="v2-status v2-status--success">Actif</span>`;
                        }else if(data === 'disable'){
                            return `<span class="v2-status v2-status--pending">Suspendu</span>`;
                        }else if(data === 'error'){
                            return `<span class="v2-status v2-status--danger">Incorrect</span>`;
                        }else if(data === 'success'){
                            return `<span class="v2-status v2-status--success">Valide</span>`;
                        }
                        else if(data === 'expire'){
                            return `<span class="v2-status v2-status--pending">Expiré</span>`;
                        }
                        else{
                            return ``;
                        }
                    }
                },
            ]
        });
    }
    }

    function findAgent() {
        fetch(`/panel/agents/autorisations/findAll`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                //console.log(data);
                const results = data.data;
                const agentSelect = document.getElementById('agent');
                
                // Vide le select avant d'ajouter de nouvelles options
                agentSelect.innerHTML = '<option value="">Tous</option>';
    
                // Parcourt les résultats et ajoute chaque agent comme option
                results.forEach(agent => {
                    const option = document.createElement('option');
                    option.value = agent.uuid; // Assurez-vous que 'id' est la bonne clé pour l'identifiant de l'agent
                    option.textContent =  agent.civility_uuid+' '+agent.firstname+' '+agent.lastname; // Assurez-vous que 'name' est la bonne clé pour le nom de l'agent
                    agentSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
    }
    
     
    function findStat() {
        fetch(`/panel/load/all/activities/statistique`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                //console.log(data);
                const results = data.data;
                //console.log(data);

                        
                var permissions = {
                    penalite_journalier: canPermission('activites_voir_les_scannes_journaliers'),
                    penalite_mensuel: canPermission('activites_voir_le_nombre_de_scanne_mensuel'),
                    scanne_journalier: canPermission('activites_voir_les_penalites_mensuel'),
                    scanne_mensuel: canPermission('activites_voir_le_nombre_de_scanne_mensuel'),
                };
                

                // Vide le select avant d'ajouter de nouvelles options
                if(permissions.penalite_journalier){
                    
                const penalite_journalier = document.getElementById('penalite_journalier');
                penalite_journalier.innerHTML = results.penalite_journalier;
                }

                if(permissions.penalite_mensuel){
                const penalite_mensuel = document.getElementById('penalite_mensuel');
                penalite_mensuel.innerHTML = results.penalite_mensuel;
                }
                if(permissions.scanne_journalier){
                const scanne_journalier = document.getElementById('scanne_journalier');
                scanne_journalier.innerHTML = results.scanne_journalier;
                }
                if(permissions.scanne_mensuel){
                const scanne_mensuel = document.getElementById('scanne_mensuel');
                scanne_mensuel.innerHTML = results.scanne_mensuel;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
    }
    
    
    $('.searchData').submit(function (e) {
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
                    searchData(data.data)
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
