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
                var permissions = {
                    historique_paiement: canPermission('entites_voir_lhistorique_des_paiements_dune_entite'),
                    recu_de_paiement: canPermission('entites_telecharger_le_recu_de_paiement'),
                    telecharger_la_carte: canPermission('entites_telecharger_la_carte'),
                };

                //console.log(data);
                if (!data.data) {
                    throw new Error('Données manquantes ou incorrectes dans la réponse');
                }
    
                const results = data.data;
                const entete = results.entete || [];
                const pay_element = results.pay_element || {};
                const factures = results.factures || {};
                const entity = results.entity || {};
                //console.log(pay_element)
               // console.log(results)
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

                
                html_render += `
                <tr> 
                    <td><h3>Taxe payé </h3></td> 
                    <td><h3> ${pay_element['rubrique_name'] || ''} ${pay_element['rubrique_option_name'] || ''} </h3></td> 
                </tr>`;

                html_render += `
                <tr> 
                    <td> <h3> Montant payé </h3></td> 
                    <td><h3> ${pay_element['amount'] || ''} Francs CFA </h3></td> 
                </tr>`;

                if (Array.isArray(entete) && entete.length > 0) {
                    entete.forEach(element => {
                        const slugifiedName = slugify(element.name);
                        const payElementValue = pay_element[slugifiedName] || ''; // Récupère la valeur correspondante dans pay_element
                        if(slugifiedName !="email" && slugifiedName !="telephone"){
                            html_render += `
                            <tr> 
                                <td> <h3> ${element.name} </h3></td> 
                                <td><h3> ${payElementValue} </h3></td> 
                            </tr>`;  
                        }
 

                    });


                    
                    /* html_render += `
                    <tr> 
                        <td>Mode de paiement</td> 
                        <td> ${pay_element['mode_paiement'] || ''} </td> 
                    </tr>`;

                    html_render += `
                    <tr> 
                        <td>ID Transaction </td> 
                        <td> ${pay_element['transaction_id'] || ''} </td> 
                    </tr>`; */

                    html_render += `
                    <tr> 
                        <td> <h3> Référence paiement </h3> </td> 
                        <td> <h3> ${pay_element['reference'] || ''} </h3> </td> 
                    </tr>`;

                    function formatDate(dateString) {
                        const date = new Date(dateString);
                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0'); // Les mois commencent à 0
                        const year = date.getFullYear();
                        return `${day} - ${month} - ${year}`;
                    }

                    var date_actuelle = new Date().toISOString().split('T')[0]; // Date actuelle au format YYYY-MM-DD

                    const dateDebutFormatted = formatDate(pay_element['date_debut']);
                    const dateFinFormatted = formatDate(pay_element['date_fin']);

                    if (pay_element['date_fin'] > date_actuelle) {
                        html_render += `
                        <tr> 
                            <td> <h3> Période </h3> </td> 
                            <td> 
                               <h3>  <span class="badge badge-pill badge-success">${dateDebutFormatted}</span> au <span class="badge badge-pill badge-success">${dateFinFormatted}</span> </h3> 
                            </td> 
                        </tr>`;
                    } else if (pay_element['date_fin'] < date_actuelle) {
                        html_render += `
                        <tr> 
                            <td><h3> Période </h3> </td> 
                            <td> 
                              <h3>   <span class="badge badge-pill badge-danger">${dateDebutFormatted}</span> au <span class="badge badge-pill badge-danger">${dateFinFormatted}</span>    </h3>                        
                            </td> 
                        </tr>`;
                    }
                    else {
                        html_render += `
                        <tr> 
                            <td><h3> Période </h3> </td> 
                            <td> 
                               <h3>  <span class="badge badge-pill badge-danger">Aucun paiement valide</span></h3> 
                            </td> 
                        </tr>`;
                    }

                    if(pay_element['state'] ==="enable"){
                        html_render += `
                        <tr> 
                            <td> <h3> Statut </h3> </td> 
                            <td>
                              <h3>  <span class="adge bg-warning font-12 text-white font-weight-medium badge-pill "> En attente </span> </h3> 
                            </td> 
                        </tr>`;

                        

                        if(pay_element['transaction_id'] !=="" && pay_element['transaction_id'] !==undefined  && pay_element['transaction_id'] !==null ){
                            document.getElementById('validation-info').style.display = "block";
                        }
                        
                    } else if(pay_element['state'] ==="validate"){
                        html_render += `
                        <tr> 
                            <td><h3> Statut </h3> </td> 
                            <td> <h3> <span class="adge bg-success font-12 text-white font-weight-medium badge-pill"> Validé </span> </h3> </td> 
                        </tr>`;

                        html_render += `
                        <tr> 
                            <td> <h3> Validé par </h3> </td> 
                            <td> <h3>  ${pay_element['validate_firstname']  || ''} ${pay_element['validate_lastname']  || ''} le ${new Date(pay_element['validate_at']).toLocaleString()} </h3> </td> 
                        </tr>`;

                        //document.getElementById('validation-info').innerHTML =  `<a href="/landing/services/facturation/taxe/data/generate/carte/${pay_element['paiement_uuid']}" class="btn btn-rounded btn-outline-success col-sm-3">Télécharger la carte de stationnement</a>`;

                         if(permissions.telecharger_la_carte){
                            document.getElementById('validation-info').innerHTML =  `<a href="/landing/services/facturation/taxe/data/generate/carte/${pay_element['paiement_uuid']}" class="btn btn-rounded btn-outline-success">Imprimer la quittance de stationnement</a>`;
                            document.getElementById('validation-info').style.display = "block";
                        }else{
                            document.getElementById('validation-info').innerHTML =  ``;

                        } 
                       
                    }else{
                        html_render += `
                        <tr> 
                            <td><h3> Statut </h3> </td> 
                            <td>
                              <h3> <span class="adge bg-danger font-12 text-white font-weight-medium badge-pill "> Rejeté </span> </h3> 
                            </td> 
                        </tr>`;

                        html_render += `
                        <tr> 
                            <td> <h3> Validé par </h3> </td> 
                            <td> <h3> ${pay_element['validate_firstname']  || ''} ${pay_element['validate_lastname']  || ''} le ${new Date(pay_element['validate_at']).toLocaleString()} </h3> </td> 
                        </tr>`;

                        document.getElementById('validation-info').style.display = "none";  
                    }
                    

                } else {
                    html_render = "<tr><td colspan='2'>Aucune donnée disponible pour l'entête</td></tr>";
                }
    
                document.getElementById('html_render').innerHTML = html_render;
    

              //  console.log(factures) 
              //AJOUTE LES DONNEES
  
          
                    
                if(permissions.historique_paiement){
                    // Génération des lignes du tableau pour les factures
                    let history_render = ""; 

                    if (Array.isArray(factures) && factures.length > 0) {
                        factures.forEach(facture => {
                            // Vérifier si les permissions sont disponibles
                            let receiptLink = "";
                            if (permissions && permissions.recu_de_paiement) {
                                receiptLink = (facture.state === "success") 
                                    ? `<a href="/landing/services/facturation/taxe/data/generate/file/${facture.uuid}" class="btn btn-link">Télécharger le reçu</a>` 
                                    : 'REMBOURSER';
                            }
                    
                            // Générer le contenu pour chaque facture
                            history_render += `
                            <tr>
                                <td>${facture.updated_at ? new Date(facture.updated_at).toLocaleString() : 'N/A'}</td>
                                <td>${entity.name || 'N/A'}</td>
                                <td>${facture.reference || 'N/A'}</td>
                                <td>${facture.amount ? `${facture.amount} FCFA` : 'N/A'}</td>
                                <td>${facture.operateur_uuid || 'N/A'}</td>
                                <td>${translateStatus(facture.state) || 'N/A'}</td>
                                <td>${receiptLink}</td>
                            </tr>`;
                        });
                    }
                    
                    else {
                    // history_render += "<tr><td colspan='7'>Aucun paiement retrouvé.</td></tr>";
                    }

                    // Injection du contenu HTML dans le tableau
                    document.getElementById('history_render').innerHTML = history_render;

                    // Initialisation de DataTables après le rendu du tableau
                    $('#history_container').DataTable();
                }
            })
    
            .catch(error => {
                //console.error('Erreur:', error);
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

        $('.validate-info').on('click', function(e){
            e.preventDefault();

            var action = $(this).attr('url');
            var caption = $(this).attr('caption');

            //alert(action)
            Swal.fire({
                icon : 'warning',
                text : 'Attention !',
                title: caption ? caption : 'Vous êtes sur le point d\'effectuer un changement',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: `OUI, CONTINUER`,
                denyButtonText: `ANNULER`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {

                    loader();

                    $.get(action, function(data){
                        loader('hide');
                        //console.log(data)
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
            case 'fail':
                return '<span class="badge badge-danger">Rejeté </span>';
            case '1':
                return '<span class="badge badge-success">Actif </span>';
            case '0':
                    return '<span class="badge badge-danger">Inactif </span>';
            default:
                return state; // Si la périodicité n'est pas reconnue, on renvoie la valeur telle quelle
        }
    }


