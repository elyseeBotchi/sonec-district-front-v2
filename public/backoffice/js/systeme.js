$(document).ready(function() {

    findAll();
    findRubriques();

    function findAll(){

        fetch(`/panel/systemes/findAll`)
            .then(response => {
                if(!response.ok){
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                const results = data.data;
               // console.log(data);
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
                        { data: 'start' },
                        { data: 'end' },
                        { data: 'rate' },
                        { data: 'total_cumul' },
                        {
                            data: 'facturation',
                            render: function(data, type, row) {
                                if(data ==="" || data === null){
                                    return `tous `;  
                                }else{
                                    return data;
                                }
                               
                            }
                        },
                        {
                            data: 'status',
                            render: function(data, type, row) {
                                if(data ===1){
                                    return `<span class="badge badge-pill badge-success">Actif</span>`;  
                                }else{
                                    return `<span class="badge badge-pill badge-danger">Inactif</span>`;;
                                }
                               
                            }
                        },
                        {
                            data: 'status',
                            render: function(data, type, row) {
                                if(data ===1){
                                    let buttonAction = `
                                        <a href="/panel/systemes/${data}/edit" title="Modification de l'élement ${row.name}" class="btn btn-outline-warning btn-icon waves-effect waves-light material-shadow-none sendEditModuleLink">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    `;
                                    
                                    buttonAction += `&nbsp;&nbsp;&nbsp;
                                    <a href="/panel/systemes/${data}/delete"
                                        data-uuid="${row.uuid}" 
                                        title="Annuler le paiement" 
                                        caption = "VOUS ÊTES SUR LE POINT DE DESACTIVER CETTE REGLE . VOULEZ VOUS CONTUNIER ?"
                                        class="btn btn-outline-danger btn-icon waves-effect waves-light material-shadow-none sendDeleteLink">
                                            <i class="fa fa-trash"></i>
                                    </a> &nbsp;`;

                                    return buttonAction;
                                }else{
                                    return ``;
                                }

                            }
                        },
                    ],
                    destroy: true,
                    responsive: true,
                    dom: '<"top"f>rt<"bottom"lp><"clear">'
                });
            })
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
                rubriqueSelect.innerHTML = ''; // Vider le contenu actuel du select
    
                // Ajouter l'option vide "Type de véhicule"
                const defaultOption = document.createElement('option');
                defaultOption.textContent = 'Type de véhicule';
                defaultOption.value = '';
                rubriqueSelect.appendChild(defaultOption);
    
                // Vérification des rubriques
                if (results.rubrique && results.rubrique.length > 0) {
                    results.rubrique.forEach(rubrique => {
                        if (rubrique.rubrique_option.length > 0) {
                            // Créer un groupe d'options pour chaque rubrique ayant des options
                            const optgroup = document.createElement('optgroup');
                            optgroup.label = rubrique.name;
    
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
    
                                        optgroup.appendChild(optionElement);
                                    });
                                } else {
                                    const optionElement = document.createElement('option');
                                    optionElement.textContent = `${option.option_name} (Pas de facturation disponible)`;
                                    optionElement.disabled = true;
    
                                    optgroup.appendChild(optionElement);
                                }
                            });
    
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

                                    rubriqueSelect.appendChild(facturationOption);
                                });
                            } else {
                                optionElement.textContent = `${rubrique.name} (Pas de facturation disponible)`;
                                optionElement.disabled = true;
    
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
                //document.getElementById('submitBtn').style.display = 'block';
    
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
                    
                   //const selectedLieuRDV = selectedOption?.getAttribute('data-lieu_rendez_vous') || '';
                   // const selectedLieuRdvuuid = selectedOption?.getAttribute('data-lieu_rendez_vous_uuid') || '';
                   // document.getElementById('lieu_rdv').value = selectedLieuRDV;
                   // document.getElementById('list_rdv').value = selectedLieuRdvuuid;


                });
            })
            .catch(error => {
                console.error('Erreur :', error);
            });
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

            var row = '<div class="row">';
            const result = data.data;

            row += `<div class="form-group col-md-6">
                        <label for="name" class="form-label">Date de début</label>
                        <input type="date" name="start" value="${result.start}" class="form-control" autofocus>
                    </div>`;

            row += `<div class="form-group col-md-6">
                        <label for="name" class="form-label">Date de fin</label>
                        <input type="date" name="end" value="${result.end}"  class="form-control" />
                    </div>`;

            row += `<div class="form-group col-md-6">
                        <label for="name" class="form-label">Ratio (%)</label>
                        <input type="number" name="rate" value="${result.rate}" class="form-control" max="100" required />
                    </div>`;

            row += `<div class="form-group col-md-6">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="number" name="total_cumul" value="${result.total_cumul}" class="form-control" required />
                    </div> `;

            row += ` 
                <div class="form-group col-md-6">
                    <label for="name" class="form-label">Type de véhicule</label>
                    <select name="facturation_uuid" class="form-control" id="rubriqueUpdate"></select>
                </div> `;
        
            row += ` 
                    <div class="form-group col-md-6">
                        <label for="name" class="form-label">Montant</label>
                        <input type="text" name="montant_pay" id="montant_payUpdate" class="form-control" readonly />
                    </div> `;
            row += `</div>`;
                
            
            findRubriquesUpdate(result.facturation_line_uuid)
            $('.updateModalBody').html(row);
            $(".sendModuleUpdateForm").attr("action", '/panel/settings/' + result.uuid + '/update');
            $('#updateModal').modal('show');
        });
    });


        
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
    
                                        optgroup.appendChild(optionElement);
                                    });
                                } else {
                                    const optionElement = document.createElement('option');
                                    optionElement.textContent = `${option.option_name} (Pas de facturation disponible)`;
                                    optionElement.disabled = true;
                                    optgroup.appendChild(optionElement);
                                }
                            });
    
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
    
                }
            })
            .catch(error => {
                console.error('Erreur :', error);
            });
    }



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
