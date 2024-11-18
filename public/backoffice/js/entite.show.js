$(document).ready(function() {

    $('#civility-man').on('change', function(){
        $("#avatar-preview").attr("src", "/storage/admins/man.png");
    });
    $('#civility-woman').on('change', function(){
        $("#avatar-preview").attr("src", "/storage/admins/woman.png");
    });


    findAll();
     findRubriques()

     function formatDate(dateString) {
        const optionsDate = { year: 'numeric', month: 'long', day: 'numeric' };
        const optionsTime = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', optionsDate) + ' à ' + date.toLocaleTimeString('fr-FR', optionsTime);
    }

    function findAll(){
        fetch(`/panel/entite/findOneConfig/${Entity_uuid}`)
            .then(response => {
                if(!response.ok){
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                const results = data.data;

                const entity = results.entity;
                const entity_attribute = results.entity_attribute[0];

                // Update the table with the entity information
                //document.getElementsByClassName('entity_name')[0].innerHTML = entity.name;
                let elements = document.getElementsByClassName('entity_name');
                for (let i = 0; i < elements.length; i++) {
                    elements[i].innerHTML = entity.name;
                }

                document.getElementById('current_version').innerHTML = entity.current_version;
                document.getElementById('attribute_table_name').innerHTML = entity.entity_attribute_table_name;
                document.getElementById('entity_state').innerHTML = translateStatus(entity.state);

                // Format and update the dates
                document.getElementById('created_at').innerHTML = formatDate(entity.created_at);
                document.getElementById('updated_at').innerHTML = formatDate(entity.updated_at);

                // Update the config schema

                // Format and update the config schema as JSON
                const formattedJson = JSON.stringify(JSON.parse(entity_attribute.config_schema), null, 4);
                document.getElementById('config_schema').innerHTML ="<code>" +formattedJson+"</code>";
          })
            .catch(error => {
                console.error('Erreur:', error);
            });
    }

    function translatePeriodicity(periodicity) {
        switch (periodicity) {
            case 'monthly':
                return 'mois';
            case 'quarterly':
                return 'trimestre';
            case 'yearly':
                return 'ans';
            case 'weekly':
                return 'semaine';
            case 'daily':
                return 'jour';
            default:
                return periodicity; // Si la périodicité n'est pas reconnue, on renvoie la valeur telle quelle
        }
    }
    
    function translateStatus(state) {
        switch (state) {
            case 'enable':
                return 'actif';
            case 'disable':
                return 'inactif';
            case 'delete':
                return 'supprimer';
            case '1':
                return 'actif';
            case '0':
                return 'inactif';
            case 'pending':
                return 'en attente';
            default:
                return state; // Si la périodicité n'est pas reconnue, on renvoie la valeur telle quelle
        }
    }
    

    function findRubriques() {

        fetch(`/panel/entite/rubrique/findOneConfig/${Entity_uuid}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                const results = data.data;
                //console.log(results);

                const rubriqueContainer = document.getElementById('rubrique_container');
                rubriqueContainer.innerHTML = ''; // Vider le contenu actuel
                let rubrique_status = 1;
                results.rubrique.forEach(rubrique => {
                    let optionsHTML = '';
                    let option_status = 1;
                    // Générer le HTML pour chaque option de rubrique
                    rubrique.rubrique_option.forEach(option => {
                        if(option.status === 1){
                            option_status = 0;
                        }else{
                            option_status = 1;
                        }
                        optionsHTML += `
                            <div class="row d-flex align-items-center" style="border-bottom: 1px solid silver;">`;
                        optionsHTML += `
                            <span class="col-md-6 d-flex align-items-center">
                                <a href="#" data-toggle="modal" data-target="#updateElement-modal" data-rubrique_uuid="${rubrique.uuid}" data-rubrique_option_uuid="${option.uuid}" data-target_rule="rubrique_options" data-name="${option.option_name}"  title="Modification de ${option.option_name}" class="addFacturation">
                                    <i class="fas fa-edit text-warning"></i>
                                </a> &nbsp;

                                 <a href="#"  data-uuid="${option.uuid}" data-urlback="findRubriques" data-name="${option.option_name}" data-token="${_token}"  data-url="/panel/entite/rubrique/option/delete" data-param="${option_status}" data-message="Vous êtes sur le point de supprimer cette sous rubrique. Etes vous sûr de vouloir poursuivre ?" data-title="Supression de  ${option.option_name}" title="Suppression de ${option.option_name}"  class="deleteConfirmation">
                                    <i class="fas fa-trash text-danger"></i>
                                </a> &nbsp;
                                <a href="#" data-toggle="modal" data-target="#addFacturation-modal" data-rubrique_uuid="${rubrique.uuid}" data-rubrique_option_uuid="${option.uuid}" data-target_rule="rubrique_options" data-name="${option.option_name}"  title="Ajouter une tarification à ${option.option_name}" class="addFacturation">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </a>

                                <span class="badge badge-pill badge-success ml-2">${option.option_name}</span>
                            </span>`;

                            if(option.facturation){
                                optionsHTML += `<span class="col-md-6 mb-2">`;
                                option.facturation.forEach(rubrique_option_facturation => {
                                    optionsHTML += `
                                        <a href="#" data-id="${rubrique_option_facturation.uuid}" title="Retrait de la tarification ${rubrique_option_facturation.amount} Fr CFA / ${rubrique_option_facturation.periodicity}" data-urlback="findRubriques"  data-token="${_token}" data-url="/panel/entite/rubrique/facturation/delete" data-param="0" data-message="Vous êtes sur le point de supprimer cette tarification. Etes vous sûr de vouloir poursuivre ?" data-title="Retrait de la tarification ${rubrique_option_facturation.amount} Fr CFA / ${rubrique_option_facturation.periodicity}"  class="badge badge-pill badge-info deleteConfirmation">
                                            ${rubrique_option_facturation.amount} Fr CFA / ${translatePeriodicity(rubrique_option_facturation.periodicity)}
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>`;
                                });

                                optionsHTML += `</span>`;
                            }

                        optionsHTML += `</div>`;
                    });

                    let rubrique_facturable = "";
                    let rubrique_facturation = "";
                    if(rubrique.status === 1){
                        rubrique_status = 0;
                    }else{
                        rubrique_status = 1;
                    }
                    // Générer le HTML pour chaque rubrique
                    let rubriqueHTML = `<tr class="border-top-1">`;

                    rubrique_facturable += `
                    <a href="#" data-toggle="modal" data-target="#updateElement-modal"  data-rubrique_uuid="${rubrique.uuid}" data-rubrique_option_uuid="" data-target_rule="rubriques" data-name="${rubrique.name}" title="Modification de ${rubrique.name}" class="addFacturation">
                        <i class="fas fa-edit text-warning"></i>
                    </a> &nbsp;
                     <a href="#" data-id="${rubrique.uuid}" data-urlback="findRubriques" data-name="${rubrique.name}" data-token="${_token}"  data-url="/panel/entite/rubrique/delete" data-param="${rubrique_status}" data-message="Vous êtes sur le point de supprimer cette rubrique. Etes vous sûr de vouloir poursuivre ?" data-title="Supression de  ${rubrique.name}" title="Supression de  ${rubrique.name}"  class="deleteConfirmation">
                        <i class="fas fa-trash text-danger"></i>
                    </a> &nbsp; `;

                    if(rubrique.rubrique_option.length === 0){
                        rubrique_facturable += `
                        <a href="#" data-toggle="modal" data-target="#addFacturation-modal"  data-rubrique_uuid="${rubrique.uuid}" data-rubrique_option_uuid="" data-target_rule="rubriques" data-name="${rubrique.name}" title="Ajouter une tarification à ${rubrique.name}" class="addFacturation">
                            <i class="fas fa-hand-holding-usd"></i>
                        </a> &nbsp;`;

                        if(rubrique.facturation){
                            rubrique_facturation += `<span class="col-md-4 mb-2">`;
                            rubrique.facturation.forEach(facturation => {
                                rubrique_facturation += `
                                    <a href="#" data-id="${facturation.uuid}" title="Retrait de la tarification ${facturation.amount} Fr CFA / ${facturation.periodicity}"  data-id="${facturation.uuid}" data-urlback="findRubriques"  data-token="${_token}" data-url="/panel/entite/rubrique/facturation/delete" data-param="0" data-message="Vous êtes sur le point de supprimer cette tarification. Etes vous sûr de vouloir poursuivre ?" data-title="Retrait de la tarification ${facturation.amount} Fr CFA / ${facturation.periodicity}"  class="badge badge-pill badge-info deleteConfirmation">
                                        ${facturation.amount} Fr CFA / ${translatePeriodicity(facturation.periodicity)}
                                        <i class="fa fa-trash text-danger"></i>
                                    </a> &nbsp;`;
                            });

                            rubrique_facturation += `</span>`;
                        }
                    }

                    rubriqueHTML += `
                        <td>
                            <div class="row d-flex align-items-center">
                                <span class="d-flex align-items-center">
                                    ${rubrique_facturable}
                                    <span >
                                        ${rubrique.name}
                                    </span>
                                    ${rubrique_facturation}
                                 </span>
                            </div>
                        </td>
                        <td class="border-top-1">
                            ${optionsHTML}
                        </td>`;
                    rubriqueHTML += `</tr>`;

                    // Ajouter la rubrique et ses options au conteneur
                    rubriqueContainer.insertAdjacentHTML('beforeend', rubriqueHTML);
                });
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
    }


        $('.sendRubriqueForm').submit(function (e) {
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
                        findRubriques()
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

         $('#container').on('click', '.addFacturation', function(e){
            e.preventDefault();
            const rubrique_uuid = this.getAttribute('data-rubrique_uuid');
            const rubrique_option_uuid = this.getAttribute('data-rubrique_option_uuid');
            const name = this.getAttribute('data-name');
            const target = this.getAttribute('data-target_rule');

            //alert(uuid)
            document.getElementById('tarification_target').innerHTML = name;
            document.getElementById('rubrique_target').value = rubrique_uuid;
            document.getElementById('rubrique_option_target').value = rubrique_option_uuid;
            document.getElementById('target_rule').value = target;

            document.getElementById('amount').value = '';
            document.getElementById('quantity').value = 1;

    
        });


        $("#container").on('click', '.deleteConfirmation', function() {

            //alert('*******');
            var type = $(this).data('type');
            var title = $(this).data('title');
            var message = $(this).data('message');
            var id = $(this).data('id');
            var token = $(this).data('token');
            var url = $(this).data('url');
            var urlback = $(this).data('urlback');
            var param = $(this).data('param');
            //var model_permission = document.getElementById('ckeditor').value;
        
             showConfirm_submit(id,token,url,title,message,param,urlback);
        });
        
       
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
            document.getElementById('target_name').innerHTML = name;


        });
 
        /*############################# MODAL ALERT############################*/
        function showConfirm_submit(id,token,url,title,message,param,urlback,element) {
            Swal.fire({
                title: title,
                text: message ?? 'Etes vous sûr de vouloir continuer ?',
                icon: "warning",
                buttons: true,
                dangerMode: true,
        
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, continuer !',
                confirmButtonClass: 'btn btn-warning',
                cancelButtonClass: 'btn btn-danger ml-1',
            })
                .then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: url,
                            type: "POST",
                            data: { id : id,_token :token ,param:param,urlback:urlback},
                            dataType: "json",
        
                            beforeSend: function() { // if form submit
                                loader();
                            },
                            success: function(data) {
                                loader('hide')
                                if(data.type==="success"){//if formData forme is very good
        
                                    toastr.success(data.message);
                                    
                                        findRubriques();
                             
                                    if(data.urlback ==="back"){
                                        location.reload();
                                    }
                                    else if(data.urlback ===""){
        
                                    }
                                    else{
                                        window.location.href =data.urlback;
                                    }
        
                                }
                                else{
                                    toastr.error(data.message);
                                }
                            },
                            error: function(data) {
                                loader('hide')
                                if(data.type==="error"){// if error occured
                                    toastr.error(data.message);
                                }
                            }
                        });
                    }
                    else if (result.dismiss === Swal.DismissReason.cancel) {
                        loader('hide')
                        /*Swal.fire({
                            title: 'Cancelled',
                            text: 'Your imaginary file is safe :)',
                            type: 'error',
                            confirmButtonClass: 'btn btn-success',
                        })*/
                    }
        
                });
        }
        
        /*############################# MODAL ALERT############################*/
        
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
            var button = document.getElementById("close-modal");
            /* button.addEventListener("click", function() {
                alert("Le bouton a été cliqué !");
            }); */

            button.click();
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

