$(document).ready(function() {
    findAll();
    findRubriques();

    function findAll() {
        fetch(`/panel/services/cheque/data/${cheque_uuid}/${Entity_uuid}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue lors de la récupération des données');
                }
                return response.json();
            })
            .then(data => { 
                if (!data || !data.entete || !data.data || !data.entity || !data.cheque) {
                    throw new Error('Données manquantes ou incorrectes dans la réponse');
                }
    
                const { entete, data: results, entity, cheque } = data;
    
                let statusBadge = '';
                switch (cheque.status) {
                    case 'init':
                        statusBadge = `<span class="badge rounded-pill badge-secondary">Brouillon</span>`;
                        break;
                    case 'enable':
                        statusBadge = `<span class="badge badge-pill badge-warning">En attente de cotation</span>`;
                        break;
                    case 'validate':
                            statusBadge = `<span class="badge badge-pill badge-success">Validé</span>`;
                        break;
                    case 'pending':
                            statusBadge = `<span class="badge badge-pill badge-info">En cours d'encaissement</span>`;
                        break;
                    case 'cotation':
                        statusBadge = `<span class="badge badge-pill badge-info">Cotation validé</span>`;
                        break;
                    case 'disable':
                        statusBadge = `<span class="badge rounded-pill badge-warning">Suspendu</span>`;
                        break;
                        
                    case 'fail':
                        statusBadge = `<span class="badge rounded-pill badge-danger">Rejeté</span>`;
                        break;
                    default:
                        statusBadge = `<span class="badge badge-pill badge-light">Inconnu</span>`;
                }

                // Mise à jour des informations du chèque
                const fields = {
                    'libelle-cotation': cheque.libelle,
                    'entreprise-cotation': cheque.nom_du_proprietaire,
                    'reference-cotation': cheque.reference,
                    'contribuable-cotation': cheque.contribuable,
                    'nbre_vehicule-cotation': cheque.nombre_vehicule,
                    'nbre_vehicule_enregistrer-cotation': cheque.nombre_vehicule_enregistre,
                    //'telephone-cotation': cheque.telephone,
                    'statut-cotation': statusBadge,
                };
    
           

                Object.keys(fields).forEach(id => {
                    document.getElementById(id).innerHTML = fields[id] || '';
                });
                
                /* */ var permissions = {
                    valider_le_cheque: canPermission('cheques_valider_un_cheque'),
                    valider_la_cotation: canPermission('cheques_valider_une_cotation'),
                    proceder_au_paiement: canPermission('cheques_proceder_au_paiement_par_cheque'),
                    telecharger_la_facture: canPermission('cheques_telecharger_la_facture'),
                    imprimer_la_carte: canPermission('entites_telecharger_la_carte'), 
                    voir_les_infos_du_cheque: canPermission('cheques_voir_les_informations_du_cheque'), 
                };


                if(cheque.status === "pending" || cheque.status === "validate" || cheque.status === "fail" && permissions.voir_les_infos_du_cheque){
                    document.getElementById("numero-cheque").innerHTML = cheque.numero_cheque || '' ;
                    document.getElementById("banque-cheque").innerHTML = cheque.banque_emettrice || '' ;
                    document.getElementById("date-emission").innerHTML = cheque.date_emission || '' ;
                    document.getElementById("date-encaissement").innerHTML = cheque.date_encaissement || '' ;
                    const montant_cheque = parseFloat(cheque.montant_cheque).toLocaleString('fr-FR', {
                        style: 'currency',
                        currency: 'XOF',
                    });

                    document.getElementById("montant-cheque").innerHTML = montant_cheque || '' ;
                    document.getElementById("titulaire-compte").innerHTML = cheque.nom_du_proprietaire || '' ;
                    

                    let elements = document.getElementsByClassName("info-cheque");
                    for (let i = 0; i < elements.length; i++) {
                        elements[i].style.display = "block";
                    }

                }else{
                    let elements = document.getElementsByClassName("info-cheque");
                    for (let i = 0; i < elements.length; i++) {
                        elements[i].style.display = "none";
                    }

                }


                if(cheque.status ==="init"){
                    

                    if(cheque.status ==='init' && cheque.nombre_vehicule <= results.length){
                        let button = `<a href="/panel/services/cheque/submit/cotation/${cheque.uuid}" 
                                        caption = "<h3>VOUS ÊTES SUR LE POINT DE SOUMETTRE VOTRE DEMANDE DE COTATION . <br> VOULEZ VOUS CONTUNIER ? </h3>"
                                        title="Soumettre ma demande de cotation" 
                                        class="btn btn-sm btn-info sendDeleteLink"> 
                                        Soumettre pour cotation </a> `;
    
                       // document.getElementById('submit-cotation').innerHTML = button;
                    }
                    
                    if(cheque.nombre_vehicule >= results.length){
                        diffVehicule = cheque.nombre_vehicule - results.length;
                        document.getElementById('alert-message').innerHTML = '<h3 class="alert alert-warning col-md-12" role="alert">Vous devez ajouter au moins '+ diffVehicule +' véhicule(s) avant soumission de votre demande de cotation </h3>';    
                    } 
                }
                else{


                    /* document.getElementById('add-cotation').innerHTML = ""; */
                    document.getElementById('submit-cotation').innerHTML = "";
                    document.getElementById('alert-message').innerHTML = '';    
                    if(cheque.status =="enable" &&  permissions.valider_la_cotation){

                        let buttonAddCotation = `<a href="#" class="btn btn-rounded btn-outline-primary" data-toggle="modal" data-target="#customer-edit_add-modal">
                            <i class="fas fa-plus"></i> Ajouter un véhicule
                        </a>`;

                       // document.getElementById('add-cotation').innerHTML = buttonAddCotation;

                        let buttonDownoald = `<a href="/panel/services/cheque/valider/cotation/${cheque.uuid}/${Entity_uuid}" data-uuid="${cheque.uuid}" 
                        caption = "<h3>VOUS ÊTES SUR LE POINT DE VALIDER LA DEMANDE DE COTATION . <br> VOULEZ VOUS CONTUNIER ? </h3>"
                        title="Valider la demande de cotation" 
                        class="btn btn-success sendDeleteLink"> 
                        Valider la cotation </a> `;
                        document.getElementById('submit-cotation').innerHTML = buttonDownoald;
                    }else{
                        if(cheque.status =="cotation" || cheque.status =="pending"  || cheque.status === "fail" && permissions.telecharger_la_facture){
                                let buttonDownoald = `<a href="/panel/services/cheque/facture/cotation/${cheque.uuid}/${Entity_uuid}" data-uuid="${cheque.uuid}" 
                                title="Télécharger la facture" 
                                class="btn btn-success"> 
                                Télécharger la facture </a>`;
                                document.getElementById('submit-cotation').innerHTML = buttonDownoald;
                        }

                        if(cheque.status =="cotation" || cheque.status =="pending"  || cheque.status === "fail" && permissions.proceder_au_paiement){
                            let buttonPay = ` &nbsp;  &nbsp;  &nbsp; &nbsp;<a href="#" data-toggle="modal" data-target="#payElement-modal" 
                                            data-uuid="${cheque.uuid}" 
                                            data-name="${cheque.libelle}" 
                                            title="Procéder au paiement" 
                                            class="btn btn-warning">
                                            PROCEDER AU PAIEMENT </a> &nbsp;`;
                            document.getElementById('pay-cotation').innerHTML = buttonPay;

                        }

                        if(cheque.status =="pending" && permissions.valider_le_cheque){
                           
                            let buttonConfirmPay = ` &nbsp;  &nbsp;  &nbsp; &nbsp;<a href="#" data-toggle="modal" data-target="#confirmElement-modal" 
                            data-uuid="${cheque.uuid}" 
                            data-name="${cheque.libelle}" 
                            title="confirmer le paiement" 
                            class="btn btn-sm btn-success">
                            CONFIRMER LE PAIEMENT </a> &nbsp;`;
                            document.getElementById('confirm-cotation').innerHTML = buttonConfirmPay;

                            let buttonRejetPay = `&nbsp;&nbsp;&nbsp;<a href="/panel/services/cheque/annuler/cheque/${cheque.uuid}/${Entity_uuid}"
                            data-uuid="${cheque.uuid}" 
                            title="Annuler le paiement" 
                            caption = "<h3>VOUS ÊTES SUR LE POINT DE REJETER LE PAIEMENT . <br> VOULEZ VOUS CONTUNIER ? </h3>"
                            class="btn btn-sm btn-danger float-right sendDeleteLink">
                            ANNULER LE PAIEMENT </a> &nbsp;`;
                            document.getElementById('rejeter-cotation').innerHTML = buttonRejetPay;
                        }
                        
                    }
                }

                // Génération du formulaire
                generateForm(entete, cheque);
    
                // Réinitialisation de la DataTable si elle existe déjà
                const table = $('#datatable-custom');
                if ($.fn.DataTable.isDataTable(table)) {
                    table.DataTable().destroy();
                }
    
                // Initialisation de la nouvelle DataTable
                table.DataTable({
                    data: results,
                    columns: [
                        { data: 'nom_du_proprietaire' },
                        { data: 'numero_de_la_carte_grise' },
                        { data: 'numero_dimmatriculation' },
                        { data: 'rubrique_name' },
                        {
                            data: 'amount',
                            render: function (data, type, row) {
                              return  data.toLocaleString('fr-FR') + ' F CFA'
                            }
                        },
                        {
                            data: 'cheques_entity_state',
                            render: function (data) {
                                let statusBadge = '';
                                switch (data) {
                                    case 'init':
                                        statusBadge = `<span class="badge rounded-pill badge-secondary">Brouillon</span>`;
                                        break;
                                    case 'enable':
                                        statusBadge = `<span class="badge badge-pill badge-warning">En attente </span>`;
                                        break;
                                    case 'validate':
                                        statusBadge = `<span class="badge badge-pill badge-success">Validé</span>`;
                                        break;
                                    case 'disable':
                                        statusBadge = `<span class="badge rounded-pill badge-warning">Suspendu</span>`;
                                        break;
                                    default:
                                        statusBadge = `<span class="badge badge-pill badge-light">Inconnu</span>`;
                                }
                                return statusBadge;
                            }
                        },
                        {
                            data: 'uuid',
                            render: function (data, type, row) {
                                let actions = '';
                                if (row.cheques_entity_state !== 'validate' && cheque.status === 'enable' && permissions.imprimer_la_carte ) {
                                    actions += `<a href="#" data-toggle="modal" data-target="#updateElement-modal" 
                                        data-uuid="${row.element_uuid}" 
                                        data-facturation_uuid="${row.facturation_line_uuid}" 
                                        data-name="${row.numero_dimmatriculation}" 
                                        title="Modifier le véhicule ${row.numero_dimmatriculation}" 
                                        class="btn btn-sm btn-outline-warning updateElement">
                                        <i class="fa fa-edit"></i></a> &nbsp;`;
                
                                    actions += `<a href="/panel/services/cheque/valider/ligne/cotation/${row.cheques_entity_uuid}/${Entity_uuid}" 
                                        data-uuid="${row.cheques_entity_uuid}" 
                                        caption="<h3>VOUS ÊTES SUR LE POINT DE VALIDER CE VÉHICULE. CONTINUER ?</h3>" 
                                        title="Valider le véhicule" 
                                        class="btn btn-sm btn-outline-success sendDeleteLink"> 
                                        Valider </a>`;
                                }else{
                                    if(cheque.status !=='validate' && cheque.status !=='pending' && row.cheques_entity_state === 'validate'){
                                        actions += `<a href="/panel/services/cheque/reinitialiser/ligne/cotation/${row.cheques_entity_uuid}/${Entity_uuid}" 
                                        data-uuid="${row.cheques_entity_uuid}" 
                                        caption="<h3>VOUS ÊTES SUR LE POINT DE REINITIALISER CE VÉHICULE. CONTINUER ?</h3>" 
                                        title="Réinitialiser le véhicule" 
                                        class="btn btn-sm btn-outline-warning sendDeleteLink"> 
                                        reinitialiser </a>`;                                     
                                    }
                                }

                                if(cheque.status ==='validate' && permissions.imprimer_la_carte){
                                   actions += `<a href="/landing/services/facturation/taxe/data/generate/carte/${row.cheques_entity_uuid}" class="btn btn-rounded btn-sm btn-outline-success">Imprimer</a>`;
                                }
                                return actions;
                            }
                        }
                    ],
                    paging: false,
                    searching: true,
                    info: false,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/fr-FR.json'
                    },
                    footerCallback: function (row, data, start, end, display) {
                        let api = this.api();
                
                        // Calcul du total de la colonne 'amount'
                        let total = api
                            .column(4, { page: 'current' }) // Index de la colonne 'amount'
                            .data()
                            .reduce((a, b) => {
                                return (parseFloat(a) || 0) + (parseFloat(b) || 0);
                            }, 0);
                
                        // Affichage du total dans le tfoot
                        $(api.column(4).footer()).html(total.toLocaleString('fr-FR') + ' F CFA');
                    }
                });
                
            })
            .catch(error => {
                console.error('Erreur:', error);
                //alert('Une erreur est survenue lors de la récupération des données.');
            });
    }
    
    function generateForm(entete,pay_element) {
        let formHtml = '';
      
        entete.forEach(field => {
            let validationAttributes = '';
            let required = "required";

            const slugifiedName = slugify(field.name);
            const payElementValue = pay_element[slugifiedName] || ''; // Récupère la valeur correspondante dans pay_element
        
            // Ajout de règles spécifiques pour chaque type de champ
            if (field.type_input === 'text') {
                //
                if(slugify(field.name)==="numero_dimmatriculation" || slugify(field.name)==="numro_dimmatriculation"){
                    //validationAttributes = ' pattern="^([0-9]{1,4}[A-Z]{2}[0-9]{2})|([A-Z]{2}[0-9]{1,4}[A-Z]{2})$"';
                    //validationAttributes = 'pattern="^([0-9]{1,4}[A-Z]{2}[0-9]{2})|([A-Z]{2}[0-9]{1,4}[A-Z]{2})|([0-9]{5}[A-Z]{2}CI[0-9]{2})$"';
                    //validationAttributes = 'pattern="^([0-9]{1,4}[A-Z]{2}[0-9]{2})|([A-Z]{2}[0-9]{1,4}[A-Z]{2})|([0-9]{5}[A-Z]{2}CI[0-9]{2})|([0-9]{2,10}[A-Z]{2}CI[0-9]{2})|([A-Z]{2}-[0-9]{1,4}-[A-Z]{2}-[0-9]{2})|([A-Z]{2}-[0-9]{1,4}-[A-Z]{2})|(CH[A-Z]{1}[0-9]{4,5})|(P[0-9]{6,8})$"';
                  

                    pattern="^([0-9]{1,4}[A-Z]{2}[0-9]{2})|([A-Z]{2}[0-9]{1,4}[A-Z]{2})|([0-9]{5}[A-Z]{2}CI[0-9]{2})|([0-9]{2,10}[A-Z]{2}CI[0-9]{2})|([A-Z]{2}-[0-9]{1,4}-[A-Z]{2}-[0-9]{2})|([A-Z]{2}-[0-9]{1,4}-[A-Z]{2})|(CH[A-Z]?[0-9]{4,10})|(P[0-9]{6,8})|(CHP[0-9]{7})|(R[0-9]{7})|([0-9]{4}\|[0-9]{8}[A-Z]{2}CI[0-9]{2})|(CH[0-9]{4,10})|([A-Z0-9]{15,20})$"
                    validationAttributes += ' onkeydown="return !(event.key === \' \')"';

                    validationAttributes += ' title="Le numéro d\'immatriculation doit être sous le format 1234AB01, 12345WWCI01 ou AB1234CD"';
                }                
                else if(slugify(field.name)==="numero_de_la_carte_grise" || slugify(field.name)==="numro_de_la_carte_grise"){
                    //validationAttributes = ' pattern="^[A-Z]{2}[0-9]{6}$|^[0-9]{6}[A-Z]{2}$|^[A-Z]{2}-[0-9]{4}-[A-Z]{2}$"';
                    validationAttributes = ' pattern="^[A-Z]{2}(?[0-9]{6,10})$|^(?[0-9]{6,8}[A-Z]{2}$)|^[A-Z]{2}-[0-9]{4}-[A-Z]{2}$"';
                    validationAttributes += ' onkeydown="return !(event.key === \' \')"';   
                    required = ""
                    validationAttributes += ' title="Le numéro de la carte grise doit être sous le format AB123456, 123456AB, ou encore AB-1234-CD"';
                }
                else{
                    validationAttributes = ' minlength="3" maxlength="50"';
                }

                         // Forcer la saisie en majuscules
                         validationAttributes += ' style="text-transform:uppercase;" oninput="this.value = this.value.toUpperCase();"';
                 
                
            } else if (field.type_input === 'email') {
                //validationAttributes = 'pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,4}$"';
            } else if (field.type_input === 'tel') {
                // Regex pour les numéros de téléphone en Côte d'Ivoire (format 10 chiffres, commence par 01, 05, 07, etc.)
                validationAttributes = ' pattern="^(0[1-9]|25)[0-9]{8}$" maxlength="10" title="Le numéro de téléphone doit commencer par 01, 02, 03, ..., ou 25 et contenir exactement 10 chiffres."';
                validationAttributes += ' title="Le numéro de téléphone doit contenir exactement 10 chiffres."';
            }
    

            if(field.type_input !== 'email' && field.type_input !== 'tel'  && slugify(field.name) !== 'nom_du_proprietaire'){
                if(required ===""){
                    formHtml += `
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">${field.name}  ${required} </label>
                            <input type="${field.type_input}" class="form-control" placeholder="${field.name}" name="${slugify(field.name)}" style="height: 40px;" ${required} ${validationAttributes} />
                        </div>
                    </div>`;
                }
                else{
                    formHtml += `
                     <div class="col-md-6">
                        <div class="form-group">
                             <label class="form-label">${field.name} <code>*</code>   </label>
                            <input type="${field.type_input}" class="form-control" placeholder="${field.name}" name="${slugify(field.name)}" style="height: 40px;" ${required} ${validationAttributes} />
                        </div>
                    </div>`;
                }
             
            }else{

                formHtml += `
                    <input type="hidden" class="form-control" value="${payElementValue}" placeholder="${field.name}" name="${ slugify(field.name)}" ${required} ${validationAttributes} />`;
            }

            
        });
    
        // Insérer le formulaire généré dans un conteneur existant
        document.getElementById('form-container').innerHTML = formHtml;
    }

    function generateFormUpdate(entete, pay_element) {
        let formHtml = '';
    
        entete.forEach(field => {
            let validationAttributes = '';
            let required = "required";
            const slugifiedName = slugify(field.name);
            const payElementValue = pay_element[slugifiedName] || ''; // Valeur par défaut
    
            // Ajout des règles de validation spécifiques
            if (field.type_input === 'text') {
                if (["numero_dimmatriculation", "numro_dimmatriculation"].includes(slugifiedName)) {
                    validationAttributes = `
                        pattern="^([0-9]{1,4}[A-Z]{2}[0-9]{2})|([A-Z]{2}[0-9]{1,4}[A-Z]{2})|([0-9]{5}[A-Z]{2}CI[0-9]{2})|([0-9]{2,10}[A-Z]{2}CI[0-9]{2})|([A-Z]{2}-[0-9]{1,4}-[A-Z]{2}-[0-9]{2})|([A-Z]{2}-[0-9]{1,4}-[A-Z]{2})|(CH[A-Z]{1}[0-9]{4,5})|(P[0-9]{6,8})$"
                        title="Le numéro d'immatriculation doit être au format 1234AB01, 12345WWCI01, AB1234CD, ou similaire."
                    `;
                } 
                else if (["numero_de_la_carte_grise", "numro_de_la_carte_grise"].includes(slugifiedName)) {
                    validationAttributes = `
                        pattern="^([A-Z]{2}[0-9]{6,8})$|^([0-9]{6,8}[A-Z]{2})$|^[A-Z]{2}-[0-9]{4}-[A-Z]{2}$"
                        title="Le numéro de la carte grise doit être au format AB123456, 123456AB, ou AB-1234-CD."
                    `;
                    required = "";
                } 
                else {
                    validationAttributes = ' minlength="3" maxlength="50"';
                }
            } 
            else if (field.type_input === 'email') {
                validationAttributes = `
                    pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,4}$"
                    title="Veuillez entrer une adresse e-mail valide (ex : exemple@domaine.com)."
                `;
            } 
            else if (field.type_input === 'tel') {
                validationAttributes = `
                    pattern="^(0[1-9]|25)[0-9]{8}$" 
                    maxlength="10" 
                    title="Le numéro de téléphone doit commencer par 01, 02, ..., ou 25 et contenir exactement 10 chiffres."
                `;
            }
    
            if(field.type_input !== 'email' && field.type_input !== 'tel'){  //&& slugify(field.name) !== 'nom_du_proprietaire'
                if(required ===""){
                
                    formHtml += `
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">${field.name}</label>
                            <input type="${field.type_input}" 
                                    class="form-control" 
                                    value="${payElementValue}" 
                                    placeholder="${field.name}" 
                                    name="${slugifiedName}"  
                                    ${required} 
                                    ${validationAttributes} />
                        </div>
                    </div>`;
                }
                else{
                    formHtml += `
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">${field.name} <code>*</code> </label>
                            <input type="${field.type_input}" 
                                    class="form-control" 
                                    value="${payElementValue}" 
                                    placeholder="${field.name}" 
                                    name="${slugifiedName}"  
                                    ${required} 
                                    ${validationAttributes} />
                        </div>
                    </div>`;
                }
            }else{
                formHtml += `
                    <input type="hidden" class="form-control" value="${payElementValue}" placeholder="${field.name}" name="${ slugify(field.name)}" ${required} ${validationAttributes} />`;
            }
        });
    
        // Insertion du formulaire généré dans le conteneur
        document.getElementById('form-container-update').innerHTML = formHtml;
        
           // console.log(data.data); // Affiche les données reçues
           
        //console.log(pay_element)
        findRubriquesUpdate(pay_element['facturation_uuid'])

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
       
    $('#container').on('click', '.updateElement', function(e) {
        e.preventDefault();
        const uuid = this.getAttribute('data-uuid');
        const facturation_uuid = this.getAttribute('data-facturation_uuid');

        //const name = this.getAttribute('data-name');
    
        //alert(facturation_uuid);
        document.getElementById('update-uuid').value = uuid;
    
        // Faire une requête fetch
        fetch(`/panel/customer/service/taxe/find_one/${uuid}/${Entity_uuid}`, {
            method: 'GET', // Ou 'POST' selon votre besoin
            headers: {
                'Content-Type': 'application/json',
                // Ajoutez d'autres en-têtes si nécessaire, comme l'authentification
            }
        })
        .then(response => response.json())
        .then(data => {
           // console.log(data.data); // Affiche les données reçues
            const entete = data.data.entete;
            const pay_element = data.data.pay_element;
           pay_element.facturation_uuid = facturation_uuid || '';
            //console.log(pay_element)
            generateFormUpdate(entete,pay_element)
        })
        .catch(error => {
           // console.error('Erreur:', error);
        });
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

                    const closePayModalButton = document.querySelector('.closePayModal');
                    if (closePayModalButton) {
                        closePayModalButton.click();
                    }

                    const closeConfirmModalButton = document.querySelector('.closeConfirmModal');
                    if (closeConfirmModalButton) {
                        closeConfirmModalButton.click();
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

                    const closePayModalButton = document.querySelector('.closePayModal');
                    if (closePayModalButton) {
                        closePayModalButton.click();
                    }

                    
                    const closeConfirmModalButton = document.querySelector('.closeConfirmModal');
                    if (closeConfirmModalButton) {
                        closeConfirmModalButton.click();
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
// Suppression d'un élément
$('#container').on('click', '.sendDeleteLink', function(e) {
    e.preventDefault();

    var action = $(this).attr('href');
    var caption = $(this).attr('caption') || 'Vous êtes sur le point d\'effectuer un changement';

    if (!action) {
        console.error('Aucune URL d\'action fournie pour la suppression.');
        sendError('Action non valide. Veuillez réessayer.');
        return;
    }

    Swal.fire({
        icon: 'warning',
        title: 'Attention !',
        html: caption,
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'OUI, CONTINUER',
        denyButtonText: 'NON, FERMER',
    }).then((result) => {
        if (result.isConfirmed) {
            if (typeof loader === 'function') loader();

            $.get(action)
                .done(function(data) {
                    if (typeof loader === 'function') loader('hide');

                    if (data.type === 'success') {
                        findAll();
                        sendSuccess(data.message,data.urlback);
                    } else {
                        toastr.error(data.message || 'Une erreur est survenue.', 'Erreur');
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    if (typeof loader === 'function') loader('hide');
                   // console.error('Erreur AJAX:', textStatus, errorThrown);
                    toastr.error(errorThrown, 'Erreur');                });
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
           // console.error('Erreur lors de la vérification du statut du paiement:', error);
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
                //document.getElementById('tarif_line').innerHTML = tarif_line;
    
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
    
    function createOptionElement(value, text, amount = '', disabled = false) {
        const option = document.createElement('option');
        option.value = value;
        option.textContent = text;
        if (amount) option.setAttribute('data-amount', amount);
        if (disabled) option.disabled = true;
        return option;
    }
    

    
    
    function findRubriquesUpdate(facturation_uuid) {
        fetch(`/landing/services/rubrique/findOneConfig/${Entity_uuid}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            }) 
            .then(data => {
                document.getElementById('montant_payUpdate').value = '';
                const results = data.data;
                const rubriqueSelect = document.getElementById('rubriqueUpdate');
                let tarif_line = "";
                rubriqueSelect.innerHTML = '';
    
                // Ajouter une option vide
                const defaultOption = document.createElement('option');
                defaultOption.textContent = 'Type de véhicule';
                defaultOption.value = facturation_uuid;
                rubriqueSelect.appendChild(defaultOption);
    
                let selectedFound = false; // Vérifier si on a trouvé l'élément à sélectionner
    
                if (results.rubrique && results.rubrique.length > 0) {
                    results.rubrique.forEach(rubrique => {
                        if (rubrique.rubrique_option.length > 0) {
                            const optgroup = document.createElement('optgroup');
                            optgroup.label = rubrique.name;
                            tarif_line += `<tr><td>${rubrique.name}</td>`;
    
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
    
                                        // Sélection automatique
                                        if (facturation.uuid === facturation_uuid) {
                                           
                                            optionElement.selected = true;
                                            selectedFound = true;
                                        }
    
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
    
                                // Sélection automatique
                                if (facturation.uuid === facturation_uuid) {
                                    facturationOption.selected = true;
                                    selectedFound = true;
                                }
    
                                tarif_line += `<tr><td>${rubrique.name}</td><td>${formattedAmount}</td></tr>`;
                                rubriqueSelect.appendChild(facturationOption);
                            });
                        }
                    });
                } else {
                    const noRubriqueOption = document.createElement('option');
                    noRubriqueOption.textContent = 'Aucune rubrique disponible';
                    noRubriqueOption.disabled = true;
                    rubriqueSelect.appendChild(noRubriqueOption);
                }
    
                document.getElementById('submitBtnUpdate').style.display = 'block';
    
                // Définir automatiquement les montants si une sélection a été trouvée
                if (selectedFound) {
                    updateMontant();
                }
    
                // Gestionnaire d'événement pour la mise à jour du montant
                rubriqueSelect.addEventListener('change', updateMontant);
    
                function updateMontant() {
                    const selectedOption = rubriqueSelect.options[rubriqueSelect.selectedIndex];
                    const selectedAmount = selectedOption?.getAttribute('data-amount') || '';
                    const formattedAmount = selectedAmount
                        ? parseFloat(selectedAmount).toLocaleString('fr-FR', {
                              style: 'currency',
                              currency: 'XOF',
                          })
                        : '';
                    document.getElementById('montant_payUpdate').value = formattedAmount;
    
                    const selectedLieuRDV = selectedOption?.getAttribute('data-lieu_rendez_vous') || '';
                    const selectedLieuRdvuuid = selectedOption?.getAttribute('data-lieu_rendez_vous_uuid') || '';
                    document.getElementById('lieu_rdvUpdate').value = selectedLieuRDV;
                    document.getElementById('list_rdvUpdate').value = selectedLieuRdvuuid;
                }
            })
            .catch(error => {
                console.error('Erreur :', error);
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

