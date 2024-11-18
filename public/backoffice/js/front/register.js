$(document).ready(function() {
    findAll();
    function findAll() { 
        fetch(`/customer/services/taxe/entetes/${Entity_uuid}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue lors de la récupération des données');
                }
                return response.json();
            })
            .then(data => { 
                console.log(data)
                if (!data || !data.entete || !data.entity) {
                    throw new Error('Données manquantes ou incorrectes dans la réponse');
                }
    
                const entete = data.entete;
                const entity = data.entity;
                console.log(entete)
                // Générer le formulaire dynamiquement à partir des en-têtes
                generateForm(entete);
    
                document.getElementById('TaxeEntity').innerHTML = entity.name;
                // Mettre à jour les informations de l'entité dans les éléments HTML
                let elements = document.getElementsByClassName('services');
                for (let i = 0; i < elements.length; i++) {
                    elements[i].innerHTML = entity.front_name;
                }
    
               
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
            if(field.name !== "Email"){
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
                            <label class="form-label text-dark">${field.name} </label>
                            <input type="${field.type_input}" class="form-control" name="${ slugify(field.name)}" placeholder="${field.name}"  required ${validationAttributes}  />
                        </div>
                    </div>`;         
            }
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

