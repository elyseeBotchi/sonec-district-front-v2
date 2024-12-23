$(document).ready(function() { 
    
    findAll();
 
    function findAll() { 
        //const Paiement_uuid = /* Assurez-vous que Paiement_uuid est défini correctement ici */;
        
        fetch(`/panel/customer/service/taxe/find_one/${Element_uuid}/${Entity_uuid}`)
            .then(response => { 
                if (!response.ok) {
                    throw new Error('Une erreur est survenue lors de la récupération des données');
                }
                return response.json();
            })
            .then(data => {  
               
                console.log(data);
                if (!data.data) {
                    throw new Error('Données manquantes ou incorrectes dans la réponse');
                }
    
                const results = data.data;
                const entete = results.entete || [];
                const pay_element = results.pay_element || {};
                const factures = results.factures || {};
                const entity = results.entity || {};
    console.log(results)
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
    

              //  console.log(factures) 
              //AJOUTE LES DONNEES


            // Génération des lignes du tableau pour les factures
            let history_render = ""; 
            if (Array.isArray(factures) && factures.length > 0) {
                factures.forEach(facture => {
                    history_render += `
                    <tr>
                        <td>${new Date(facture.updated_at).toLocaleString() || 'N/A'}</td>
                        <td>${entity.name || 'N/A'}</td>
                        <td>${facture.reference || 'N/A'}</td>
                        <td>${facture.amount || 'N/A'} FCFA</td>
                        <td>${facture.operateur_uuid || 'N/A'}</td>
                        <td>${translateStatus(facture.state) || 'N/A'}</td>
                    </tr>`;
                });
            } else {
               // history_render += "<tr><td colspan='7'>Aucun paiement retrouvé.</td></tr>";
            }

            // Injection du contenu HTML dans le tableau
            document.getElementById('history_render').innerHTML = history_render;

            // Initialisation de DataTables après le rendu du tableau
            $('#history_container').DataTable();
            })
            .catch(error => {
                console.error('Erreur:', error);
                // Afficher un message utilisateur, par exemple un toast ou une alerte
               // alert('Une erreur est survenue lors de la récupération des données.');
            });
    }
    
    
    function generateForm(entete) {
        let formHtml = '';
    
        entete.forEach(field => { 
            formHtml += `
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">${field.name} </label>
                    <input type="${field.type_input}" class="form-control btn-outline-secondary" name="${ slugify(field.name)}" />
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
        case 'pending':
            return '<span class="badge badge-info">En attente </span>';
        case 'progress':
            return '<span class="badge badge-dark">En cours </span>';
        case 'success':
            return '<span class="badge badge-success">Réussi </span>';
        case 'enable':
            return '<span class="badge badge-success">Actif</span>';
        case 'desable':
            return '<span class="badge badge-danger">Inactif </span>';
        case '1':
            return '<span class="badge badge-success">Actif </span>';
        case '0':
                return '<span class="badge badge-danger">Inactif </span>';
        default:
            return periodicity; // Si la périodicité n'est pas reconnue, on renvoie la valeur telle quelle
    }
}