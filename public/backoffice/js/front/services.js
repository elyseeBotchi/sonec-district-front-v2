$(document).ready(function() {
    findAll();
    findRubriques();

    function findAll() {
        fetch(`/customer/services/taxe/findAll/${Entity_uuid}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue lors de la récupération des données');
                }
                return response.json();
            })
            .then(data => { 
                if (!data || !data.entete || !data.data || !data.entity) {
                    throw new Error('Données manquantes ou incorrectes dans la réponse');
                }
    
                const entete = data.entete;
                const results = data.data;
                const entity = data.entity;
               // console.log(data)
                // Générer le formulaire dynamiquement à partir des en-têtes
                generateForm(entete);
    
                document.getElementById('TaxeEntity').innerHTML = entity.name;
                // Mettre à jour les informations de l'entité dans les éléments HTML
                /* let elements = document.getElementsByClassName('services');
                for (let i = 0; i < elements.length; i++) {
                    elements[i].innerHTML = entity.front_name;
                } */
    
                // Construire dynamiquement les en-têtes du tableau
                let headerHtml = '<tr>';
                entete.forEach(col => {
                    headerHtml += `<th>${col.name}</th>`;
                });
                headerHtml += '<th>Validité</th><th>Statut</th><th style="width:150px !important;">Action</th></tr>'; // Ajout des colonnes "Statut" et "Action"
                $('#datatable-custom thead').html(headerHtml);
    
                // Vérifier si la DataTable a déjà été initialisée
                if ($.fn.DataTable.isDataTable('#datatable-custom')) {
                    // Détruire l'instance existante avant de recréer une nouvelle DataTable
                    $('#datatable-custom').DataTable().destroy();
                }
    
               
                // Initialisation de la DataTable avec les nouvelles données
                $('#datatable-custom').DataTable({
                    data: results,
                    columns: [
                        ...entete.map(col => ({ 
                            data: slugify(col.name),
                           // data: col.field // Utiliser le champ correspondant pour chaque colonne
                        })),
                        {
                            data: 'date_fin',
                            render: function(data, type, row) {
                                // Fonction pour formater une date au format dd - m - Y
                                function formatDate(dateString) {
                                    const date = new Date(dateString);
                                    const day = String(date.getDate()).padStart(2, '0');
                                    const month = String(date.getMonth() + 1).padStart(2, '0'); // Les mois commencent à 0
                                    const year = date.getFullYear();
                                    return `${day} - ${month} - ${year}`;
                                }

                                var date_actuelle = new Date().toISOString().split('T')[0]; // Date actuelle au format YYYY-MM-DD

                                const dateDebutFormatted = formatDate(row.date_debut);
                                const dateFinFormatted = formatDate(data);

                                if (data > date_actuelle) {
                                    return `<span class="badge badge-pill badge-success">${dateDebutFormatted}</span> au <span class="badge badge-pill badge-success">${dateFinFormatted}</span>`;
                                } else if (data < date_actuelle) {
                                    return `<span class="badge badge-pill badge-danger">${dateDebutFormatted}</span> au <span class="badge badge-pill badge-danger">${dateFinFormatted}</span>`;
                                }
                                else {
                                    return `<span class="badge badge-pill badge-danger">Date dépassée</span>`; // Gérer les cas où la condition n'est pas remplie
                                }
                            }
                        },
                        {
                            data: 'state',
                            render: function(data, type, row) {
                                switch(data) {
                                    case 'init':
                                        return `<span class="badge rounded-pill badge-secondary">En attente</span>`;
                                        case 'enable':
                                            return `<span class="badge badge-pill badge-warning">En attente de validation</span>`;
                                    case 'validate':
                                        return `<span class="badge badge-pill badge-success">Validé</span>`;
                                    case 'disable':
                                        return `<span class="badge rounded-pill badge-warning">Suspendu</span>`;
                                    default :
                                         return '';
                                   
                                }
                            }
                        },
                        {
                            data: 'uuid',
                            render: function(data, type, row) {
                                let permissions = {
                                    show: true,
                                    edit: true,
                                    change: true
                                };
    
                                let actions = '';
    
                                if (permissions.show) {
                                    actions += `<a href="/customer/services/taxe/show/${data}/${Entity_uuid}" title="Voir les détails" class="btn btn-sm btn-outline-primary btn-icon waves-effect waves-light material-shadow-none"><i class="fa fa-eye"></i></a> &nbsp; `;
                                }
    
                                if (permissions.edit) {
                                   // actions += `<a href="#" data-toggle="modal" data-target="#updateElement-modal" data-uuid="${data}" data-name="${row.name}" data-description="${row.description}" title="Modifier l'entité ${row.name}" class="btn btn-outline-warning btn-icon waves-effect waves-light material-shadow-none updateElement"><i class="fa fa-edit"></i></a> &nbsp; `;
                                }
    
                                if (permissions.change) {
                                    let icon = row.state === 'enable' ? '<i class="fa fa-lock"></i>' : '<i class="fa fa-unlock"></i>';
                                    let msg = row.state === 'enable' ? 'Verrouiller ' : 'Déverrouiller';
                                    let className = row.state === 'enable' ? 'btn-outline-danger' : 'btn-outline-success';
                                   // actions += `<a href="/customer/services/taxe/delete/${data}/${Entity_uuid}" title="${msg}" class="btn btn-sm btn-icon waves-effect waves-light material-shadow-none ${className} sendDeleteLink">${icon}</a>`;
                                }
                                var date_actuelle = new Date().toISOString().split('T')[0]; // Date actuelle au format YYYY-MM-DD

                                if (permissions.edit) {
                                    if(row.state === 'enable' && (row.date_fin > date_actuelle)){
                                        /* actions += ` &nbsp; <a href="#" data-uuid="${data}" data-pay_libelle=""  data-name="${entity.name}" title="Payer ${entity.name}" class="btn btn-sm btn-outline-primary btn-icon waves-effect waves-light material-shadow-none payElement"> Payer</a> `; */
                                    }
                                    else if(row.state === 'enable' && (row.date_fin < date_actuelle)){
                                        actions += ` &nbsp; <a href="#" data-uuid="${data}" data-pay_libelle=""  data-name="${entity.name}" title="Payer ${entity.name}" class="btn btn-sm btn-outline-primary btn-icon waves-effect waves-light material-shadow-none payElement"> Payer</a> `;
                                    }
                                    else if(row.state !== 'enable'){
                                       // actions += ` &nbsp; <a href="#" data-uuid="${data}" data-pay_libelle=""  data-name="${entity.name}" title="Payer ${entity.name}" class="btn btn-sm btn-outline-primary btn-icon waves-effect waves-light material-shadow-none payElement"> Payer</a> `;
                                    }
                                    else{
                                        actions += ` &nbsp; <a href="#" data-uuid="${data}" data-pay_libelle=""  data-name="${entity.name}" title="Payer ${entity.name}" class="btn btn-sm btn-outline-primary btn-icon waves-effect waves-light material-shadow-none payElement"> Payer</a> `;
                                        //  actions +='';
                                    }
                                }
    
                                return actions;
                            }
                        }
                    ],
                    paging: false, // Désactiver la pagination
                    searching: false, // Désactiver le filtre (champ de recherche)
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/fr-FR.json' // URL pour le fichier de traduction en français
                    }
                });
            })
            .catch(error => {
                console.error('Erreur:', error);
                // Vous pouvez afficher un message utilisateur ici, comme un toast ou une alerte
                alert('Une erreur est survenue lors de la récupération des données.');
            });
    }


    function generateForm(entete) {
        let formHtml = '';
      
        entete.forEach(field => {
            let validationAttributes = '';
    
            // Ajout de règles spécifiques pour chaque type de champ
            if (field.type_input === 'text') {
                //
                if(slugify(field.name)==="numero_dimmatriculation" || slugify(field.name)==="numro_dimmatriculation"){
                    validationAttributes = ' pattern="^([0-9]{1,4}[A-Z]{2}[0-9]{2})|([A-Z]{2}[0-9]{1,4}[A-Z]{2})$"';
                    validationAttributes += ' title="Le numéro d\'immatriculation doit être sous le format 1234AB01 ou AB1234CD"';
                }                
                else if(slugify(field.name)==="numero_de_la_carte_grise" || slugify(field.name)==="numro_de_la_carte_grise"){
                    validationAttributes = ' pattern="^[A-Z]{2}[0-9]{6}$|^[0-9]{6}[A-Z]{2}$|^[A-Z]{2}-[0-9]{4}-[A-Z]{2}$"';
                    validationAttributes += ' title="Le numéro de la carte grise doit être sous le format AB123456, 123456AB, ou encore AB-1234-CD"';
                }
                else{
                    validationAttributes = ' minlength="3" maxlength="50"';
                }

                
            } else if (field.type_input === 'email') {
                //validationAttributes = 'pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,4}$"';
            } else if (field.type_input === 'tel') {
                // Regex pour les numéros de téléphone en Côte d'Ivoire (format 10 chiffres, commence par 01, 05, 07, etc.)
                validationAttributes = ' pattern="^(0[1-9]|25)[0-9]{8}$" maxlength="10" title="Le numéro de téléphone doit commencer par 01, 02, 03, ..., ou 25 et contenir exactement 10 chiffres."';
                validationAttributes += ' title="Le numéro de téléphone doit contenir exactement 10 chiffres."';
            }
    
            formHtml += `
             <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">${field.name} </label>
                    <input type="${field.type_input}" class="form-control" placeholder="${field.name}" name="${ slugify(field.name)}"  required ${validationAttributes} />
                </div>
            </div>`;
        });
    
        // Insérer le formulaire généré dans un conteneur existant
        document.getElementById('form-container').innerHTML = formHtml;
    }


    function slugify(string) {
        // Remplacer les espaces et les caractères spéciaux par des tirets, et convertir en minuscule
        var data = string.toString().toLowerCase()
            .replace(/\s+/g, '-')           // Remplace les espaces par des tirets
            .replace(/[^\w\-]+/g, '')       // Supprime tous les caractères non alphanumériques
            .replace(/\-\-+/g, '-')         // Remplace les doubles tirets par un seul tiret
            .replace(/^-+/, '')             // Supprime les tirets au début
            .replace(/-+$/, '');   
            
            return convertSlugToName(data) ;
    }

    function convertSlugToName(slug) {
        // Remplacer les tirets par des underscores
        return slug.replace(/-/g, '_');
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
 
    $('#container').on('click', '.payElement', function(e){
        e.preventDefault();
        const uuid = this.getAttribute('data-uuid');
        const name = this.getAttribute('data-name');
        const pay_libelle = this.getAttribute('data-pay_libelle');
        
          //  alert(name)
        document.getElementById('pay_uuid').value = uuid;
        document.getElementById('target_pay_name').innerHTML = name;
      //  document.getElementById('pay_libelle').value = pay_libelle;

   
      /* VERIFICATION DE L'EXISTENCE D'UN PAIEMENT */
        const PayModalButton = new bootstrap.Modal(document.getElementById('payElement-modal'), {
            backdrop: 'static', // Empêche la fermeture en cliquant en dehors
            keyboard: true // Empêche la fermeture en appuyant sur la touche Échap
        });
    
        if(PayModalButton) {
            PayModalButton.show();
        }
        
      /* ######################################### */
    
    });

  
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


    $('.sendPayForm').submit(function (e) {
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
                   // findAll()
                    const closeModalButton = document.querySelector('.closeModal');
                    if (closeModalButton) {
                        closeModalButton.click();
                    }
                    
                    const closeUpModalButton = document.querySelector('.closeUpModal');
                    if (closeUpModalButton) {
                        closeUpModalButton.click();
                    }
                }
                
                else if (data.type === "standby") {
                    sendStandby(data.message,data.reference);
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
                        findAll();
                        sendSuccess(data.message);
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

/* function loader(state = "show") {
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
} */

    function loader(state = "show", message = "Chargement en cours...") {
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
                
                // Ajouter le texte sous le spinner
                const spinnerElement = document.getElementById('spinner');
                if (spinnerElement) {
                    const textElement = document.createElement('div');
                    textElement.id = 'loader-text';
                    textElement.style.marginTop = '10px';
                    textElement.style.color = '#ffffff';
                    textElement.style.textAlign = 'center';
                    textElement.textContent = message;
                    spinnerElement.parentElement.appendChild(textElement);
                }
                break;
            default:
                JsLoadingOverlay.hide();
                
                // Supprimer le texte si présent
                const textElement = document.getElementById('loader-text');
                if (textElement) {
                    textElement.remove();
                }
                break;
        }
    }

    
    let paymentCheckInterval;
    let paymentCheckTimeout;

    function loaderMessage(state = "show", reference = null, message = "Veuillez tapez la syntaxe *133# sur votre téléphone puis choisissez l'option retrait pour approuver le paiement") {
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
    
                    // Ajouter le message après l'affichage du loader
                    const messageDiv = document.createElement('div');
                    messageDiv.id = 'loader-message';
                    messageDiv.style.position = 'fixed';
                    messageDiv.style.top = '50%';
                    messageDiv.style.left = '50%';
                    messageDiv.style.transform = 'translate(-50%, 50px)';
                    messageDiv.style.zIndex = 100000; // Assurez-vous que le message est au-dessus du loader
                    messageDiv.style.color = '#070d14';
                    messageDiv.style.fontSize = '30px';
                    messageDiv.style.textAlign = 'center'; // Facultatif : aligne le texte au centre si le message contient plusieurs lignes
                    messageDiv.innerText = message;
                    
                    document.body.appendChild(messageDiv);


               // console.log(reference);
    
                // Vérifier le statut du paiement toutes les 30 secondes
                paymentCheckInterval = setInterval(() => checkPaymentStatus(reference), 20000);
    
                // Arrêter la vérification après 5 minutes
                paymentCheckTimeout = setTimeout(() => {
                    clearInterval(paymentCheckInterval);
                    loaderMessage("hide");
                    toastr.error("Temps écoulé. Veuillez réessayer.", 'Alerte');
                    location.reload();
                }, 300000);
                break;
    
            default:
                JsLoadingOverlay.hide();
    
                // Retirer le message lors de la fermeture du loader
                const existingMessageDiv = document.getElementById('loader-message');
                if (existingMessageDiv) {
                    existingMessageDiv.remove();
                }
    
                // Arrêter la vérification du paiement
                if (paymentCheckInterval) clearInterval(paymentCheckInterval);
                if (paymentCheckTimeout) clearTimeout(paymentCheckTimeout);
                break;
        }
    }
    

    async function checkPaymentStatus(reference) {
      //  console.log('Vérification du statut du paiement...');
        try {
            const response = await fetch(`/customer/services/facturation/verification-paiement/${reference}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            });
    
            if (!response.ok) {
                throw new Error('Erreur réseau lors de la vérification du statut du paiement.');
            }
    
            const result = await response.json();
           // console.log(result)
            const paymentStatus = result.paymentStatus;
    
            if (paymentStatus === 'success') {
               // console.log('Paiement approuvé.');
                loaderMessage("hide");
                sendSuccess('Le paiement a été approuvé !', result.urlback);
            } else if (paymentStatus === 'fail') {
                //console.log('Paiement échoué.');
                loaderMessage("hide");
                toastr.error('Le paiement a échoué !', 'Erreur');
            } else {
                toastr.warning('Paiement en attente.', 'Alerte');
            }
        } catch (error) {
            console.error('Erreur lors de la vérification du statut du paiement:', error);
            toastr.error('Impossible de vérifier le statut du paiement. Veuillez réessayer.', 'Erreur réseau');
        }
    }
    
    function sendStandby(message, reference) {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            timeOut: 120000,
            extendedTimeOut: 0,
            positionClass: 'toast-top-right',
            preventDuplicates: true,
            newestOnTop: true,
            hideDuration: 0,
            showDuration: 300,
        };
    
        if (message) {
            toastr.info(message, 'Information');
        }
        loaderMessage('show', reference);
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

    function findRubriques() {
        fetch(`/customer/services/rubrique/findOneConfig/${Entity_uuid}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                const results = data.data;
                const rubriqueSelect = document.getElementById('rubrique');
                rubriqueSelect.innerHTML = ''; // Réinitialiser le contenu
    
                 // Ajouter l'option vide "Type de véhicule"
                const defaultOption = document.createElement('option');
                defaultOption.textContent = 'Type de véhicule';
                rubriqueSelect.appendChild(defaultOption);

                if (results.rubrique && results.rubrique.length > 0) {
                    results.rubrique.forEach(rubrique => {
                        if (rubrique.rubrique_option.length > 0) {
                            // Ajouter un groupe d'options pour chaque rubrique avec des options
                            const optgroup = document.createElement('optgroup');
                            optgroup.label = rubrique.name;
    
                            rubrique.rubrique_option.forEach(option => {
                                if (option.facturation && option.facturation.length > 0) {
                                    option.facturation.forEach(facturation => {
                                        const optionElement = createOptionElement(
                                            facturation.uuid,
                                            `${option.option_name}`,
                                            facturation.amount
                                        );
                                        optgroup.appendChild(optionElement);
                                    });
                                } else {
                                    // Option désactivée si aucune facturation
                                    const optionElement = createOptionElement(
                                        '',
                                        `${option.option_name} (Pas de facturation disponible)`,
                                        '',
                                        true
                                    );
                                    optgroup.appendChild(optionElement);
                                }
                            });
    
                            rubriqueSelect.appendChild(optgroup);
                        } else {
                            // Ajouter la rubrique directement si aucune option
                            if (rubrique.facturation && rubrique.facturation.length > 0) {
                                rubrique.facturation.forEach(facturation => {
                                    const optionElement = createOptionElement(
                                        facturation.uuid,
                                        rubrique.name,
                                        facturation.amount
                                    );
                                    rubriqueSelect.appendChild(optionElement);
                                });
                            } else {
                                const optionElement = createOptionElement(
                                    '',
                                    `${rubrique.name} (Pas de facturation disponible)`,
                                    '',
                                    true
                                );
                                rubriqueSelect.appendChild(optionElement);
                            }
                        }
                    });
                } else {
                    // Aucun résultat trouvé
                    const defaultOption = createOptionElement('', 'Aucune rubrique disponible', '', true);
                    rubriqueSelect.appendChild(defaultOption);
                }
    
                // Afficher le bouton de soumission
                document.getElementById('submitBtn').style.display = 'block';
    
                // Gestion du changement pour mettre à jour le montant
                rubriqueSelect.addEventListener('change', () => {
                    const selectedOption = rubriqueSelect.options[rubriqueSelect.selectedIndex];
                    const selectedAmount = selectedOption?.getAttribute('data-amount') || '';
                   
                    document.getElementById('montant_pay').value = selectedAmount;
                });
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
    }
    
    function createOptionElement(value, text, amount = '', disabled = false) {
        const option = document.createElement('option');
        option.value = value;
        option.textContent = text;
        if (amount) option.setAttribute('data-amount', amount);
        if (disabled) option.disabled = true;
        return option;
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

