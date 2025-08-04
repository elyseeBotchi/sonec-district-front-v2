$(document).ready(function() {

   findAll('pending');
   recap();

 function findAll(statut) {

    var libelle_status = "";
    if (statut === "validate") {
        libelle_status = "ENCAISSES";
    } else if (statut === "fail") {
        libelle_status = "REJETES";
    } else if (statut === "pending") {
        libelle_status = "EN COURS D'ENCAISSEMENT";
    } else if (statut === "cotation") {
        libelle_status = "PREVISIONNELS";
    }

    document.getElementById('titre_liste').innerHTML = 
        "<i class='fa fa-spinner fa-spin'></i> LISTE DES CHEQUES " + libelle_status + " EN COURS DE CHARGEMENT ...";
    
    fetch(`/panel/services/cheque/liste/findAll/${statut}/${Entity_uuid}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Une erreur est survenue lors de la récupération des données');
            }
            return response.json();
        })
        .then(data => {
            if (!data || !data.data) {
                throw new Error('Données manquantes ou incorrectes dans la réponse');
            }

            const results = data.data;
            let tableData = [];

            results.forEach(result => {
                let statusBadge = '';
                switch (result.status) {
                    case 'init':
                        statusBadge = `<span class="badge rounded-pill badge-secondary">Brouillon</span>`;
                        break;
                    case 'enable':
                        statusBadge = `<span class="badge badge-pill badge-warning">En attente de cotation</span>`;
                        break;
                    case 'pending':
                        statusBadge = `<span class="badge badge-pill badge-warning">En cours d'encaissement</span>`;
                        break;
                    case 'cotation':
                        statusBadge = `<span class="badge badge-pill badge-warning">En attente de paiement</span>`;
                        break;
                    case 'validate':
                        statusBadge = `<span class="badge badge-pill badge-success">Validé</span>`;
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
                   // console.log(AuthConnect)
                    let actions = `<a href="/panel/services/cheque/show/${result.uuid}/${Entity_uuid}" class="btn btn-sm btn-primary"><i class='fa fa-eye'></i></a>`;

                    let montant_cheque = 0;
                  if(statut ==="cotation"){
                     montant_cheque = parseFloat((result.montant_du ?? 0)).toLocaleString('fr-FR', {
                        style: 'currency',
                        currency: 'XOF',
                    });
                  }else{
                         montant_cheque = parseFloat((result.montant_cheque ?? 0)).toLocaleString('fr-FR', {
                            style: 'currency',
                            currency: 'XOF',
                        });
                  }


                tableData.push([
                    result.nom_du_proprietaire || '',
                    result.numero_cheque || '',
                    result.reference || '',
                    result.banque_emettrice || '',
                    result.date_emission || '',
                    result.date_encaissement || '',
                    montant_cheque || '',
                    statusBadge,
                    actions
                ]);
            });

            // Détruit le DataTable s'il existe déjà
            if ($.fn.DataTable.isDataTable("#dataTable")) {
                $("#dataTable").DataTable().destroy();
            }

            document.getElementById('titre_liste').innerHTML = "LISTE DES CHEQUES " + libelle_status;
        
            // Injecte les nouvelles données dans la table
            $('#dataTable').DataTable({
                data: tableData,
                columns: [
                    { title: "Entreprise" },
                    { title: "Numéro du chèque" },
                    { title: "Réference de la cotation" },
                    { title: "Banque émettrice" },
                    { title: "Date d'émission" },
                    { title: "Date d'encaissement" },
                    { title: "Montant" },
                    { title: "Statut" },
                    { title: "Action" }
                ],
                pageLength: 10,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/fr-FR.json"
                }
            });

        })
        .catch(error => {
            console.error('Erreur:', error);
            $('#render-html').html('<tr><td colspan="7">Aucune donnée disponible</td></tr>');
        });
}


function recap() {
    fetch(`/panel/services/cheque/recap/${Entity_uuid}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Une erreur est survenue lors de la récupération des données');
            }
            return response.json();
        })
        .then(data => {
            //console.log(data)
            if (!data || !data.data) {
                throw new Error('Données manquantes ou incorrectes dans la réponse');
            }

            const results = data.data;
            let cheque_encaisse_nbre = results.cheque_encaisse_nbre;
            let cheque_depose_nbre = results.cheque_depose_nbre;
            let cheque_annule_nbre = results.cheque_annule_nbre;
            let cheque_previsionnel_nbre = results.cheque_previsionnel_nbre || 0;

            
            const cheque_encaisse = parseFloat((results?.cheque_encaisse ?? 0)).toLocaleString('fr-FR', {
                style: 'currency',
                currency: 'XOF',
            });
            document.getElementById('cheque_encaisse').innerHTML = cheque_encaisse;
            document.getElementById('cheque_encaisse_nbre').innerHTML = cheque_encaisse_nbre;

            
            const cheque_depose = parseFloat((results?.cheque_depose ?? 0)).toLocaleString('fr-FR', {
                style: 'currency',
                currency: 'XOF',
            });            
            document.getElementById('cheque_depose').innerHTML = cheque_depose;
            document.getElementById('cheque_depose_nbre').innerHTML = cheque_depose_nbre;
            
            const cheque_annule = parseFloat((results?.cheque_annule ?? 0)).toLocaleString('fr-FR', {
                style: 'currency',
                currency: 'XOF',
            }); 
            document.getElementById('cheque_annule').innerHTML = cheque_annule;
            document.getElementById('cheque_annule_nbre').innerHTML = cheque_annule_nbre;

            
            const cheque_previsionnel = parseFloat((results?.cheque_previsionnel ?? 0)).toLocaleString('fr-FR', {
                style: 'currency',
                currency: 'XOF',
            }); 
            document.getElementById('cheque_previsionnel').innerHTML = cheque_previsionnel;
            document.getElementById('cheque_previsionnel_nbre').innerHTML = cheque_previsionnel_nbre;
            

        })
        .catch(error => {
            console.error('Erreur:', error);
            $('#render-html').html('<tr><td colspan="7">Aucune donnée disponible</td></tr>');
        });
}


document.querySelectorAll('.Load_cheque').forEach(item => {
    item.addEventListener('click', event => {
        // Enlever la classe highlight de tous les éléments
        document.querySelectorAll('.Load_cheque').forEach(element => {
            element.classList.remove('highlight');
        });

        item.classList.add('highlight');

        const status = item.getAttribute('data-status');
        findAll(status);
    });
});

});



