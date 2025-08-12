$(document).ready(function() {
    findAll();

    function findAll() { 
        //const Paiement_uuid = /* Assurez-vous que Paiement_uuid est défini correctement ici */;
        
        fetch(`/customer/services/facturation/taxe/data/info_paiement/${Paiement_uuid}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue lors de la récupération des données');
                }
                return response.json();
            })
            .then(data => {  
                //console.log(data.data);
                if (!data.data) {
                    throw new Error('Données manquantes ou incorrectes dans la réponse');
                }
    
                const results = data.data;
                const entete = results.entete || [];
                const pay_element = results.pay_element || {};
                const paiement = results.paiement || {};
                const entity = results.entity || {};
    
                // Vérification des données avant de les insérer dans le DOM
                if (!entity.name || !entity.front_name) {
                    throw new Error("Informations de l'entité manquantes");
                }
    
                document.getElementById('TaxeEntity').innerHTML = entity.name;
    
                let elements = document.getElementsByClassName('services');
                for (let i = 0; i < elements.length; i++) {
                    elements[i].innerHTML = entity.front_name;
                }
    
                let html_render = "";
                if (Array.isArray(entete) && entete.length > 0) {
                    entete.forEach(element => {

                        const slugifiedName = slugify(element.name);
                        const payElementValue = pay_element[slugifiedName] || ''; // Récupère la valeur correspondante dans pay_element
                    
                        html_render += `
                        <tr> 
                            <td>${element.name}</td> 
                            <td>${payElementValue}</td> 
                        </tr>`;
                    });
                } else {
                    html_render = "<tr><td colspan='2'>Aucune donnée disponible pour l'entête</td></tr>";
                }
    
                document.getElementById('html_render').innerHTML = html_render;
    
                // Vérification des données du paiement avant de les afficher
                if (paiement.reference && paiement.amount && paiement.operateur_uuid && paiement.updated_at && paiement.status) {
                    document.getElementById('reference').innerHTML = paiement.reference;
                    document.getElementById('montant_paye').innerHTML = paiement.amount;
                    document.getElementById('mode_paiement').innerHTML = paiement.operateur_uuid;
                    document.getElementById('created_at').innerHTML = paiement.updated_at;
                    document.getElementById('status_paiement').innerHTML = paiement.state;
                    //console.log(paiement)
          

                    receiptLink = (paiement.validate_by !== null && paiement.state === "success") 
                    ? `<a href="/landing/services/facturation/taxe/data/generate/carte/${paiement.uuid}" class="btn btn-sm btn-success">Télécharger la carte de stationnement</a>` 
                    : '';
                    if(pay_element.state ==="validate"){
                        document.getElementById('info-carte').innerHTML = receiptLink;
                    }

                } else {
                    console.warn('Informations de paiement manquantes ou incomplètes.');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                // Afficher un message utilisateur, par exemple un toast ou une alerte
                alert('Une erreur est survenue lors de la récupération des données.');
            });
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
            rubriqueSelect.innerHTML = ''; // Vider le contenu actuel du select

            // Vérification que les rubriques existent
            if (results.rubrique && results.rubrique.length > 0) {
                results.rubrique.forEach(rubrique => {
                    if (rubrique.rubrique_option.length > 0) {
                        // Créer un groupe d'options pour chaque rubrique ayant des options
                        let optgroup = document.createElement('optgroup');
                        optgroup.label = rubrique.name;

                        // Ajouter les options sous chaque rubrique
                        rubrique.rubrique_option.forEach(option => {
                            // Vérifier s'il y a des facturations pour l'option
                            if (option.facturation && option.facturation.length > 0) {
                                option.facturation.forEach(facturation => {
                                    let optionElement = document.createElement('option');
                                    optionElement.value = facturation.uuid; // Utiliser l'UUID de la facturation
                                    optionElement.textContent = `${option.option_name} - ${facturation.amount} Fr CFA / ${translatePeriodicity(facturation.periodicity)}`;

                                    // Ajouter l'option pour chaque facturation
                                    optgroup.appendChild(optionElement);
                                });
                            } else {
                                // Si pas de facturation, désactiver l'option
                                let optionElement = document.createElement('option');
                                optionElement.textContent = `${option.option_name} (Pas de facturation disponible)`;
                                optionElement.disabled = true;

                                // Ajouter l'option désactivée
                                optgroup.appendChild(optionElement);
                            }
                        });

                        // Ajouter l'optgroup au select
                        rubriqueSelect.appendChild(optgroup);
                    } else {
                        // Si la rubrique n'a pas d'options, ajouter la rubrique elle-même comme une option sélectionnable
                        let optionElement = document.createElement('option');
                        optionElement.value = rubrique.uuid;

                        // Vérification de la facturation pour la rubrique
                        if (rubrique.facturation && rubrique.facturation.length > 0) {
                            rubrique.facturation.forEach(facturation => {
                                let facturationOption = document.createElement('option');
                                facturationOption.value = facturation.uuid; // Utiliser l'UUID de la facturation
                                facturationOption.textContent = `${rubrique.name} - ${facturation.amount} Fr CFA / ${translatePeriodicity(facturation.periodicity)}`;

                                // Ajouter l'option pour chaque facturation
                                rubriqueSelect.appendChild(facturationOption);
                            });
                        } else {
                            // Désactiver la rubrique si pas de montant
                            optionElement.textContent = `${rubrique.name} (Pas de facturation disponible)`;
                            optionElement.disabled = true;

                            // Ajouter directement la rubrique désactivée
                            rubriqueSelect.appendChild(optionElement);
                        }
                    }
                });
            } else {
                // Si aucune rubrique n'est trouvée, afficher un message par défaut
                let defaultOption = document.createElement('option');
                defaultOption.textContent = 'Aucune rubrique disponible';
                defaultOption.disabled = true;
                rubriqueSelect.appendChild(defaultOption);
            }
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

