$(document).ready(function() {
   // findAll();


 function findAll() {
    fetch(`/panel/services/cheque/liste/findAll/${Status}/${Entity_uuid}`)
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
                    console.log(AuthConnect)
                    let actions = `<a href="/panel/services/cheque/show/${result.uuid}/${Entity_uuid}" class="btn btn-sm btn-primary"><i class='fa fa-eye'></i></a>`;

                    if (AuthConnect.email === "admin@sonec.com" || AuthConnect.email === "beniespoir1@gmail.com") {
                        /* actions += `  <a href="/panel/services/cheque/autogenerate/${result.uuid}" 
                                        data-uuid="${result.uuid}" 
                                        caption="VOUS ÊTES SUR LE POINT DE GENERER LA LISTE DES VÉHICULES. CONTINUER ?" 
                                        title="Générer la liste des véhicules" 
                                        class="btn btn-sm btn-outline-warning sendDeleteLink"> 
                                        Auto générer 
                                    </a>`; */
                    }

                tableData.push([
                    result.nom_du_proprietaire || '',
                    result.libelle || '',
                    result.reference || '',
                    result.contribuable || '',
                    Math.ceil(result.nombre_vehicule) || '',
                    result.nombre_vehicule_enregistre || 0,
                    statusBadge,
                    actions
                ]);
            });

            // Détruit le DataTable s'il existe déjà
            if ($.fn.DataTable.isDataTable("#dataTable")) {
                $("#dataTable").DataTable().destroy();
            }

            // Injecte les nouvelles données dans la table
            $('#dataTable').DataTable({
                data: tableData,
                columns: [
                    { title: "Nom du propriétaire" },
                    { title: "Libellé" },
                    { title: "Référence" },
                    { title: "Contribuable" },
                    { title: "Nombre de véhicules à déclaré" },
                    { title: "Nombre de véhicules enregistré" },
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


});



