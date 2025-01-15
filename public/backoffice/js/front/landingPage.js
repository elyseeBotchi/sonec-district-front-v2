$(document).ready(function() {
  //  alert(slugify("numero_dimmatriculation"))
    findAll();
    findRubriques();
   let FormEntete;
    function findAll() { //alert(Entity_uuid)
        fetch(`/landing/services/taxe/findAll/${Entity_uuid}`)
            .then(response => {
                if (!response.ok) {
                  //  throw new Error('Une erreur est survenue lors de la récupération des données');
                }
                return response.json();
            })
            .then(data => { 
                if (!data || !data.entete || !data.data || !data.entity) {
                  //  throw new Error('Données manquantes ou incorrectes dans la réponse');
                }
    //console.log(data)
                const entete = data.entete;
                const entity = data.entity;
                FormEntete = entete;
                // Générer le formulaire dynamiquement à partir des en-têtes
                generateForm(entete);
    
                //document.getElementById('TaxeEntity').innerHTML = entity.name;
                // Mettre à jour les informations de l'entité dans les éléments HTML
               /*  let elements = document.getElementsByClassName('services');
                for (let i = 0; i < elements.length; i++) {
                    elements[i].innerHTML = entity.front_name;
                } */
          
            })
            .catch(error => {
                console.error('Erreur:', error);
                // Vous pouvez afficher un message utilisateur ici, comme un toast ou une alerte
               // alert('Une erreur est survenue lors de la récupération des données.');
            });
    }
    

    function generateForm(entete) {
        let formHtml = '';
      
        entete.forEach(field => {
            let validationAttributes = '';
            let required = "required";
            let email_message = "";
            // Ajout de règles spécifiques pour chaque type de champ
            if (field.type_input === 'text') {
                //
                if(slugify(field.name)==="numero_dimmatriculation" || slugify(field.name)==="numro_dimmatriculation"){
                   // validationAttributes = ' pattern="^([0-9]{1,4}[A-Z]{2}[0-9]{2})|([A-Z]{2}[0-9]{1,4}[A-Z]{2})$"';
                  // validationAttributes = 'pattern="^([0-9]{1,4}[A-Z]{2}[0-9]{2})|([A-Z]{2}[0-9]{1,4}[A-Z]{2})|([0-9]{5}[A-Z]{2}CI[0-9]{2})$"';
                  //validationAttributes = 'pattern="^([0-9]{1,4}[A-Z]{2}[0-9]{2})|([A-Z]{2}[0-9]{1,4}[A-Z]{2})|([0-9]{5}[A-Z]{2}CI[0-9]{2})|([0-9]{2,10}[A-Z]{2}CI[0-9]{2})$"';
                  //validationAttributes = 'pattern="^([0-9]{1,4}[A-Z]{2}[0-9]{2})|([A-Z]{2}[0-9]{1,4}[A-Z]{2})|([0-9]{5}[A-Z]{2}CI[0-9]{2})|([0-9]{2,10}[A-Z]{2}CI[0-9]{2})|([A-Z]{2}-[0-9]{1,4}-[A-Z]{2}-[0-9]{2})$"';
                    // validationAttributes = 'pattern="^([0-9]{1,4}[A-Z]{2}[0-9]{2})|([A-Z]{2}[0-9]{1,4}[A-Z]{2})|([0-9]{5}[A-Z]{2}CI[0-9]{2})|([0-9]{2,10}[A-Z]{2}CI[0-9]{2})|([A-Z]{2}-[0-9]{1,4}-[A-Z]{2}-[0-9]{2})|^[A-Z]{2}-[0-9]{1,4}-[A-Z]{2}$})$"';
                   // validationAttributes = 'pattern="^([0-9]{1,4}[A-Z]{2}[0-9]{2})|([A-Z]{2}[0-9]{1,4}[A-Z]{2})|([0-9]{5}[A-Z]{2}CI[0-9]{2})|([0-9]{2,10}[A-Z]{2}CI[0-9]{2})|([A-Z]{2}-[0-9]{1,4}-[A-Z]{2}-[0-9]{2})|([A-Z]{2}-[0-9]{1,4}-[A-Z]{2})$"';
                  //  validationAttributes = 'pattern="^([0-9]{1,4}[A-Z]{2}[0-9]{2})|([A-Z]{2}[0-9]{1,4}[A-Z]{2})|([0-9]{5}[A-Z]{2}CI[0-9]{2})|([0-9]{2,10}[A-Z]{2}CI[0-9]{2})|([A-Z]{2}-[0-9]{1,4}-[A-Z]{2}-[0-9]{2})|([A-Z]{2}-[0-9]{1,4}-[A-Z]{2})|(CH[A-Z]{1}[0-9]{4,5})|(P[0-9]{6,8})$"';
                 // validationAttributes = 'pattern="^([0-9]{1,4}[A-Z]{2}[0-9]{2})|([A-Z]{2}[0-9]{1,4}[A-Z]{2})|([0-9]{5}[A-Z]{2}CI[0-9]{2})|([0-9]{2,10}[A-Z]{2}CI[0-9]{2})|([A-Z]{2}-[0-9]{1,4}-[A-Z]{2}-[0-9]{2})|([A-Z]{2}-[0-9]{1,4}-[A-Z]{2})|(CH[A-Z]{1}[0-9]{4,5})|(P[0-9]{6,8})|(CHP[0-9]{4,9})$"';
                 validationAttributes = 'pattern="^([0-9]{1,4}[A-Z]{2}[0-9]{2})|([A-Z]{2}[0-9]{1,4}[A-Z]{2})|([0-9]{5}[A-Z]{2}CI[0-9]{2})|([0-9]{2,10}[A-Z]{2}CI[0-9]{2})|([A-Z]{2}-[0-9]{1,4}-[A-Z]{2}-[0-9]{2})|([A-Z]{2}-[0-9]{1,4}-[A-Z]{2})|(CH[A-Z]{1}[0-9]{4,5})|(P[0-9]{6,8})|(CHP[0-9]{7})|(R[0-9]{7})|(2024\\|[0-9]{8}[A-Z]{2}CI[0-9]{2})|(CH[0-9]{4,9})$"';

                  validationAttributes += ' title="Le numéro d\'immatriculation doit être sous le format 1234AB01, 12345WWCI01 ou AB1234CD"';
                }                
                else if(slugify(field.name)==="numero_de_la_carte_grise" || slugify(field.name)==="numro_de_la_carte_grise"){
                    //validationAttributes = ' pattern="^[A-Z]{2}[0-9]{8}$|^[0-9]{8}[A-Z]{2}$|^[A-Z]{2}-[0-9]{4}-[A-Z]{2}$"';
                    validationAttributes = ' pattern="^[A-Z]{2}(?[0-9]{6,8})$|^(?[0-9]{6,8}[A-Z]{2}$)|^[A-Z]{2}-[0-9]{4}-[A-Z]{2}$"';
                    validationAttributes += ' title="Le numéro de la carte grise doit être sous le format AB12345678, 123456AB,1234567AB,12345678AB, ou encore AB-1234-CD"';
                    required = ""
                }
                else{
                    validationAttributes = ' minlength="3" maxlength="50"';
                }

                
            } else if (field.type_input === 'email') {
                //validationAttributes = 'pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,4}$"';
                required = ""
                email_message = "Ce mail vous permettra de recevoir vos reçu de paiement"
            } 
            
            else if (field.type_input === 'tel') {
                // Regex pour les numéros de téléphone en Côte d'Ivoire (format 10 chiffres, commence par 01, 05, 07, etc.)
                validationAttributes = ' pattern="^(0[1-9]|25)[0-9]{8}$" maxlength="10" title="Le numéro de téléphone doit commencer par 01, 02, 03, ..., ou 25 et contenir exactement 10 chiffres."';
                validationAttributes += ' title="Le numéro de téléphone doit contenir exactement 10 chiffres."';
            }
    
            if(required ===""){
                formHtml += `
                <div class="col-12">
                    <label class="form-label">${field.name} <code> ${email_message} </code></label>
                    <input type="${field.type_input}" class="form-control bg-light border-0" placeholder="${field.name}" name="${slugify(field.name)}" style="height: 40px;" ${required} ${validationAttributes} />
                </div>`;
            }
            else{
                formHtml += `
                <div class="col-12">
                    <label class="form-label">${field.name} <code>*</code> </label>
                    <input type="${field.type_input}" class="form-control bg-light border-0" placeholder="${field.name}" name="${slugify(field.name)}" style="height: 40px;" ${required} ${validationAttributes} />
                </div>`;
            }
       
        });
    
        // Insérer le formulaire généré dans un conteneur existant
        document.getElementById('form-container').innerHTML = formHtml;
    }

    function generateRecap(entete) {
        let formHtml = `
            <table class="table table-bordered">
                
                <tbody>
        `;
    
        // Ajouter la rubrique sélectionnée
        let selectedRubrique = $('#rubrique option:selected').text();
        formHtml += `
            <tr>
                <td>Type de véhicule</td>
                <td><b>${selectedRubrique || 'Non renseigné'}</b></td>
            </tr>
        `;
    
        // Ajouter la date de rendez-vous sélectionnée
        let selectedText = $('#lieu_rdv').val();
        let dateRdv =  $(`#rdv option:selected`).text();
      
        formHtml += `
            <tr>
                <td>Date et lieu de rendez-vous</td>
                <td><b>${dateRdv || ''} | ${selectedText || ''}  </b></td>
            </tr>
        `;
    
        // Parcourir chaque champ dans `entete`
        entete.forEach(field => {
            let dataValue = ''; // Valeur par défaut
    
            // Identifier la valeur de l'input en fonction de son type
            if (field.type_input === 'text') {
                dataValue = $(`input[name="${slugify(field.name)}"]`).val();
            } else if (field.type_input === 'email') {
                dataValue = $(`input[name="${slugify(field.name)}"]`).val();
            } else if (field.type_input === 'tel') {
                dataValue = $(`input[name="${slugify(field.name)}"]`).val();
            }
    
            // Ajouter chaque champ dans une ligne du tableau
            formHtml += `
                <tr>
                    <td>${field.name}</td>
                    <td><b>${dataValue || 'Non renseigné'}</b></td>
                </tr>
            `;
        });
    
        let dateFormatted = ''; // Valeur par défaut
        let date_visite = $(`input[name="date_visite"]`).val(); // Valeur brute (format ISO: YYYY-MM-DD)

        if (date_visite) {
            // Convertir la date en objet `Date` et la formater en français
            let dateObject = new Date(date_visite);
            let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateFormatted = dateObject.toLocaleDateString('fr-FR', options);
        }
    
        formHtml += `
            <tr>
                <td>Date de la dernière visite</td>
                <td><b> ${dateFormatted || ''}</b></td>
            </tr>
        `;
        // Fermer les balises du tableau
        formHtml += `
                </tbody>
            </table>
        `;
    
        // Retourner le contenu HTML généré
        return formHtml;
    }
    
    
 
    function generateForm__(entete) {
        let formHtml = '';
    
        entete.forEach(field => { 
            formHtml += `
                <div class="col-12">
                    <label class="form-label">${field.name} <code>*</code> </label>
                    <input type="${field.type_input}" class="form-control bg-light border-0" placeholder="${field.name}" name="${ slugify(field.name)}" style="height: 55px;" required />
                </div>`;
        });
    
        // Insérer le formulaire généré dans un conteneur existant
        document.getElementById('form-container').innerHTML = formHtml;
    }

     
    function generateForm__(entete) {
        let formHtml = '';
    
        entete.forEach(field => { 
            formHtml += `
                <div class="form-group">
                <li><label class="form-label">${field.name} <code>*</code> </label></li>
                    <input type="${field.type_input}" class="form-control form-control-sm" name="${ slugify(field.name)}" required />
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
       // document.getElementById('pay_uuid').value = uuid;
       // document.getElementById('target_pay_name').innerHTML = name;
      //  document.getElementById('pay_libelle').value = pay_libelle;
    
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

    $('.sendPayForm_old').submit(function (e) {
        e.preventDefault();
       
    //SendError();
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
                }
                  
                else if (data.type === "standby") {
                    QuicksendStandby(data.message,data.reference);
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
    
        var dataCollect=`<div class="responsive" style="position:relative;text-align:left;"> `;
            dataCollect +=generateRecap(FormEntete);
            dataCollect +='</div>'
        // Ajout de la confirmation SweetAlert2
        Swal.fire({
            title: 'Veuillez vérifier les informations avant confirmation',
            html: dataCollect,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui, confirmer',
            cancelButtonText: 'Annuler',
        }).then((result) => {
            if (result.isConfirmed) {
                loader('show');
                // Continuer si l'utilisateur confirme
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
                       
                        // Remove previous error styles and messages
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').remove();
                    },
                    success: function (data) {
                        loader('hide');
                        if (data.type === "success") {
                            sendSuccess(data.message, data.urlback);
                        } else if (data.type === "standby") {
                            QuicksendStandby(data.message, data.reference);
                        } else if (data.type === "error_validator") {
                            handleErrors(data.errors);
                            var message = "";
                            if (data.errors) {
                                $.each(data.errors, function (key, value) {
                                    message += value.join('<br>') + '<br>';
                                });
                            }
    
                            toastr.error(message, 'Erreur', {
                                closeButton: true,
                                progressBar: true,
                                enableHtml: true
                            });
                        } else {
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
            } else {
                // Optionnel : afficher un message si l'utilisateur annule
             /*    Swal.fire(
                    'Annulé',
                    'L\'opération a été annulée.',
                    'info'
                ); */
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
                    'overlayHTML': '<div style="color:rgb(8, 2, 2); font-size: 16px; margin-top: 10px;">Veuillez patienter...</div>',
                });
                
                break;
            default:
                JsLoadingOverlay.hide();
                break;
        }
    }

        
    let paymentCheckInterval;
    let paymentCheckTimeout;

    function QuickloaderMessage(state = "show", reference = null, message = "Veuillez tapez la syntaxe *133# sur votre téléphone puis choisissez l'option retrait pour approuver le paiement") {
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
                    messageDiv.style.fontSize = '15px';
                    messageDiv.style.textAlign = 'center'; // Facultatif : aligne le texte au centre si le message contient plusieurs lignes
                    messageDiv.innerText = message;
                    
                    document.body.appendChild(messageDiv);


                console.log(reference);
    
                // Vérifier le statut du paiement toutes les 30 secondes
                paymentCheckInterval = setInterval(() => checkPaymentStatus(reference), 20000);
    
                // Arrêter la vérification après 5 minutes
                paymentCheckTimeout = setTimeout(() => {
                    clearInterval(paymentCheckInterval);
                    QuickloaderMessage("hide");
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
        console.log('Vérification du statut du paiement...');
        try {
            const response = await fetch(`/landing/services/facturation/verification-paiement/${reference}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            });
    
            if (!response.ok) {
                throw new Error('Erreur réseau lors de la vérification du statut du paiement.');
            }
    
            const result = await response.json();
            console.log(result)
            const paymentStatus = result.paymentStatus;
    
            if (paymentStatus === 'success') {
                console.log('Paiement approuvé.');
                QuickloaderMessage("hide");
                sendSuccess('Le paiement a été approuvé !', result.urlback);
            } else if (paymentStatus === 'fail') {
                console.log('Paiement échoué.');
                QuickloaderMessage("hide");
                toastr.error('Le paiement a échoué !', 'Erreur');
            } else {
                toastr.warning('Paiement en attente.', 'Alerte');
            }
        } catch (error) {
            console.error('Erreur lors de la vérification du statut du paiement:', error);
            toastr.error('Impossible de vérifier le statut du paiement. Veuillez réessayer.', 'Erreur réseau');
        }
    }
    
    function QuicksendStandby(message, reference) {
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
        QuickloaderMessage('show', reference);
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

    function findRubriques_old() {
        fetch(`/landing/services/rubrique/findOneConfig/${Entity_uuid}`)
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
                } 
                else {
                    // Si aucune rubrique n'est trouvée, afficher un message par défaut
                    let defaultOption = document.createElement('option');
                    defaultOption.textContent = 'Aucune rubrique disponible';
                    defaultOption.disabled = true;
                    rubriqueSelect.appendChild(defaultOption);
                }
                document.getElementById('submitBtn').style.display ='block';
                
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
    }
  
    function findRubriques_() {
        fetch(`/landing/services/rubrique/findOneConfig/${Entity_uuid}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                const results = data.data;
                const rubriqueSelect = document.getElementById('rubrique');
                let tarif_line = ""; // Initialiser correctement la variable
                rubriqueSelect.innerHTML = ''; // Vider le contenu actuel du select
                    console.log(results);
                // Ajouter l'option vide "Type de véhicule"
                const defaultOption = document.createElement('option');
                defaultOption.textContent = 'Type de véhicule';
                //defaultOption.value = '';
                rubriqueSelect.appendChild(defaultOption);
    
                // Vérification des rubriques
                if (results.rubrique && results.rubrique.length > 0) {
                    results.rubrique.forEach(rubrique => {
                        if (rubrique.rubrique_option.length > 0) {
                            // Créer un groupe d'options pour chaque rubrique ayant des options
                            const optgroup = document.createElement('optgroup');
                            optgroup.label = rubrique.name;
                            tarif_line += `<tr><td>${rubrique.name}</td>`;
    
                            // Ajouter les options sous chaque rubrique
                            rubrique.rubrique_option.forEach(option => {
                                if (option.facturation && option.facturation.length > 0) {
                                    option.facturation.forEach(facturation => {
                                        const optionElement = document.createElement('option');
                                        optionElement.value = facturation.uuid;
                                        optionElement.textContent = option.option_name;
                                        optionElement.setAttribute('data-amount', facturation.amount);
                                        optionElement.setAttribute('data-lieu_rendez_vous', facturation.libelle);
                                        optionElement.setAttribute('data-lieu_rendez_vous_uuid', facturation.lieu_rendez_vous_uuid);
    
                                        tarif_line += `<td>${facturation.amount} </td>`;
                                        optgroup.appendChild(optionElement);
                                    });
                                } else {
                                    const optionElement = document.createElement('option');
                                    optionElement.textContent = `${option.option_name} (Pas de facturation disponible)`;
                                    optionElement.disabled = true;
    
                                    tarif_line += `<td></td>`;
                                    optgroup.appendChild(optionElement);
                                }
                            });
    
                            tarif_line += "</tr>";
                            rubriqueSelect.appendChild(optgroup);
                        } else {
                            const optionElement = document.createElement('option');
                            optionElement.value = rubrique.uuid;
    
                            if (rubrique.facturation && rubrique.facturation.length > 0) {
                                rubrique.facturation.forEach(facturation => {
                                    const facturationOption = document.createElement('option');
                                    facturationOption.value = facturation.uuid;
                                    facturationOption.textContent = rubrique.name;
                                    facturationOption.setAttribute('data-amount', facturation.amount);
                                    optionElement.setAttribute('data-lieu_rendez_vous', facturation.libelle);
                                    optionElement.setAttribute('data-lieu_rendez_vous_uuid', facturation.lieu_rendez_vous_uuid);

                                    tarif_line += `<tr><td>${rubrique.name}</td><td>${facturation.amount}</td></tr>`;
                                    rubriqueSelect.appendChild(facturationOption);
                                });
                            } else {
                                optionElement.textContent = `${rubrique.name} (Pas de facturation disponible)`;
                                optionElement.disabled = true;
    
                                tarif_line += `<tr><td>${rubrique.name}</td><td></td></tr>`;
                                rubriqueSelect.appendChild(optionElement);
                            }
                        }
                    });
                } else {
                    const defaultOption = document.createElement('option');
                    defaultOption.textContent = 'Aucune rubrique disponible';
                    defaultOption.disabled = true;
                    rubriqueSelect.appendChild(defaultOption);
                }
    
                // Mise à jour des éléments HTML
                document.getElementById('submitBtn').style.display = 'block';
               // document.getElementById('tarif_line').innerHTML = tarif_line;
    
                // Gestionnaire d'événements pour la mise à jour du montant
                rubriqueSelect.addEventListener('change', () => {
                    const selectedOption = rubriqueSelect.options[rubriqueSelect.selectedIndex];
                    const selectedAmount = selectedOption?.getAttribute('data-amount') || '';
                    document.getElementById('montant_pay').value = selectedAmount;
                });
            })
            .catch(error => {
                console.error('Erreur :', error);
            });
    }
    
    
    function findRubriques() {
        fetch(`/landing/services/rubrique/findOneConfig/${Entity_uuid}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                const results = data.data;
                const rubriqueSelect = document.getElementById('rubrique');
                let tarif_line = ""; // Initialiser correctement la variable
                rubriqueSelect.innerHTML = ''; // Vider le contenu actuel du select
    
                // Ajouter l'option vide "Type de véhicule"
                const defaultOption = document.createElement('option');
                defaultOption.textContent = 'Type de véhicule';
                rubriqueSelect.appendChild(defaultOption);
    
                // Vérification des rubriques
                if (results.rubrique && results.rubrique.length > 0) {
                    results.rubrique.forEach(rubrique => {
                        if (rubrique.rubrique_option.length > 0) {
                            // Créer un groupe d'options pour chaque rubrique ayant des options
                            const optgroup = document.createElement('optgroup');
                            optgroup.label = rubrique.name;
                            tarif_line += `<tr><td>${rubrique.name}</td>`;
    
                            // Ajouter les options sous chaque rubrique
                            rubrique.rubrique_option.forEach(option => {
                                if (option.facturation && option.facturation.length > 0) {
                                    option.facturation.forEach(facturation => {
                                        const formattedAmount = parseFloat(facturation.amount).toLocaleString('fr-FR', {
                                            style: 'currency',
                                            currency: 'XOF',
                                        });
                                        const optionElement = document.createElement('option');
                                        optionElement.value = facturation.uuid;
                                        optionElement.textContent = option.option_name;
                                        optionElement.setAttribute('data-amount', facturation.amount);
                                        optionElement.setAttribute('data-lieu_rendez_vous', facturation.libelle);
                                        optionElement.setAttribute('data-lieu_rendez_vous_uuid', facturation.lieu_rendez_vous_uuid);
    
                                        tarif_line += `<td>${formattedAmount}</td>`;
                                        optgroup.appendChild(optionElement);
                                    });
                                } else {
                                    const optionElement = document.createElement('option');
                                    optionElement.textContent = `${option.option_name} (Pas de facturation disponible)`;
                                    optionElement.disabled = true;
    
                                    tarif_line += `<td></td>`;
                                    optgroup.appendChild(optionElement);
                                }
                            });
    
                            tarif_line += "</tr>";
                            rubriqueSelect.appendChild(optgroup);
                        } else {
                            const optionElement = document.createElement('option');
                            optionElement.value = rubrique.uuid;
    
                            if (rubrique.facturation && rubrique.facturation.length > 0) {
                                rubrique.facturation.forEach(facturation => {
                                    const formattedAmount = parseFloat(facturation.amount).toLocaleString('fr-FR', {
                                        style: 'currency',
                                        currency: 'XOF',
                                    });
                                    const facturationOption = document.createElement('option');
                                    facturationOption.value = facturation.uuid;
                                    facturationOption.textContent = rubrique.name;
                                    facturationOption.setAttribute('data-amount', facturation.amount);
                                    facturationOption.setAttribute('data-lieu_rendez_vous', facturation.libelle);
                                    facturationOption.setAttribute('data-lieu_rendez_vous_uuid', facturation.lieu_rendez_vous_uuid);

                                    tarif_line += `<tr><td>${rubrique.name}</td><td>${formattedAmount}</td></tr>`;
                                    rubriqueSelect.appendChild(facturationOption);
                                });
                            } else {
                                optionElement.textContent = `${rubrique.name} (Pas de facturation disponible)`;
                                optionElement.disabled = true;
    
                                tarif_line += `<tr><td>${rubrique.name}</td><td></td></tr>`;
                                rubriqueSelect.appendChild(optionElement);
                            }
                        }
                    });
                } else {
                    const defaultOption = document.createElement('option');
                    defaultOption.textContent = 'Aucune rubrique disponible';
                    defaultOption.disabled = true;
                    rubriqueSelect.appendChild(defaultOption);
                }
    
                // Mise à jour des éléments HTML
                document.getElementById('submitBtn').style.display = 'block';
                document.getElementById('tarif_line').innerHTML = tarif_line;
    
                // Gestionnaire d'événements pour la mise à jour du montant
                rubriqueSelect.addEventListener('change', () => {
                    const selectedOption = rubriqueSelect.options[rubriqueSelect.selectedIndex];
                    const selectedAmount = selectedOption?.getAttribute('data-amount') || '';
                    const formattedAmount = selectedAmount
                        ? parseFloat(selectedAmount).toLocaleString('fr-FR', {
                              style: 'currency',
                              currency: 'XOF',
                          })
                        : '';
                    document.getElementById('montant_pay').value = formattedAmount;
                    
                    const selectedLieuRDV = selectedOption?.getAttribute('data-lieu_rendez_vous') || '';
                    const selectedLieuRdvuuid = selectedOption?.getAttribute('data-lieu_rendez_vous_uuid') || '';
                    document.getElementById('lieu_rdv').value = selectedLieuRDV;
                    document.getElementById('list_rdv').value = selectedLieuRdvuuid;


                });
            })
            .catch(error => {
                console.error('Erreur :', error);
            });
    }
    
    
    
    