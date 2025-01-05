

$(document).ready(function() {
  //  findStatus('today','all');
    findStatistique();

    function findStatistique() {
        fetch(`/panel/statistique/data/count/${Entity_uuid}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue lors de la récupération des statistiques.');
                }
                return response.json();
            })
            .then(data => {
                const results = data.data;
                console.log("Résultats reçus :", results);

               var permissions = {
                validation_pending: canPermission('tableau_de_bord_voir_les_validations_en_attentes'),
                nb_total_jour: canPermission('tableau_de_bord_voir_les_paiements_du_jour'),
                graphe_devolution: canPermission('tableau_de_bord_voir_le_graphe_devolution'),
                total_rdv_jour: canPermission('tableau_de_bord_voir_mes_statistiques_de_validation'),
            };
            
            if(permissions.validation_pending){
                document.getElementById('validation_pending').innerHTML = results.validation_pending || 0;
            }  

            if(permissions.nb_total_jour){
                document.getElementById('nb_total_jour').innerHTML = results.nb_total_jour || 0
            }

            if(permissions.total_rdv_jour){
                document.getElementById('rdv_recu_jour').innerHTML = results.mes_rdv_jour || 0
            }

            if(permissions.total_rdv_jour){
                document.getElementById('total_rdv_jour').innerHTML = results.total_rdv_jour || 0
            }


        })
            .catch(error => {
                console.error('Erreur lors de la récupération des statistiques :', error);
                /* Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Impossible de récupérer les statistiques. Veuillez réessayer.',
                }); */
            });
    }
    


    document.querySelectorAll('.Load_paiement').forEach(item => {
        item.addEventListener('click', event => {
            // Enlever la classe highlight de tous les éléments
            document.querySelectorAll('.Load_paiement').forEach(element => {
                element.classList.remove('highlight');
            });

            // Ajouter la classe highlight à l'élément cliqué
            item.classList.add('highlight');

            // Appeler la fonction findStatus avec le statut de l'élément cliqué
            const status = item.getAttribute('data-status');
            const pay = item.getAttribute('data-pay');
            findStatus(status,pay);
        });
    });


});
