

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
               // console.log("Résultats reçus :", results);

               var permissions = {
                validation_pending: canPermission('tableau_de_bord_voir_les_validations_en_attentes'),
                nb_total_jour: canPermission('tableau_de_bord_voir_les_paiements_du_jour'),
                graphe_devolution: canPermission('tableau_de_bord_voir_le_graphe_devolution'),
            };
            
            if(permissions.validation_pending){
                document.getElementById('validation_pending').innerHTML = results.validation_pending || 0;
            }  

            if(permissions.nb_total_jour){
                document.getElementById('nb_total_jour').innerHTML = results.nb_total_jour || 0;

            }

            if(permissions.graphe_devolution){
                // Extraction des données pour les courbes
                const xValues = results.months || []; // Tableau des mois
                const yValues = results.valuesY || []; // Données pour la courbe Y
                const zValues = results.valuesZ || []; // Données pour la courbe Z
    
                // Vérification des données
                if (!xValues.length || !yValues.length || !zValues.length) {
                    console.error('Données insuffisantes pour tracer le graphique.');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Attention',
                        text: 'Les données récupérées sont incomplètes ou vides.',
                    });
                    return;
                }
    
                // Générer le graphique
                new Chart("myChart", {
                    type: "line",
                    data: {
                        labels: xValues, // Les mois
                        datasets: [
                            {
                                label: "Evolution par nombre de paiement",
                                fill: false,
                                lineTension: 0.1,
                                backgroundColor: "rgba(0,0,255,1.0)",
                                borderColor: "rgba(0,0,255,0.8)",
                                data: yValues, // Données pour la courbe Y
                            },
                            {
                                label: "Evolution par montant",
                                fill: false,
                                lineTension: 0.1,
                                backgroundColor: "rgba(255,0,0,1.0)",
                                borderColor: "rgba(255,0,0,0.8)",
                                data: zValues, // Données pour la courbe Z
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: "top",
                            },
                            title: {
                                display: true,
                                text: "Comparaison des données statistiques",
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                            },
                        },
                    },
                });
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
