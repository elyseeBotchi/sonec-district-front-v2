

$(document).ready(function() {
    findStatus('today','all');
    findStatistique();


    function findStatus(status,paymode) {
       var libelle_status = ""
       var libelle_paymode =""

        if (status === "today") {
            libelle_status = "DU JOUR"
        }
        else if(status === "all"){
            libelle_status = ""
        }

        if (paymode === "all") {
            libelle_paymode = ""
        }
        else{
            libelle_paymode = paymode
        }



        
        document.getElementById('titre_liste').innerHTML= " <i class='fa fa-spinner fa-spin'></i> LISTE DES PAIEMENTS"+ libelle_status+ " "+libelle_paymode+" EN COURS DE CHARGEMENT ..."

        fetch(`/panel/statistique/findStatus/data/${status}/${paymode}/${Entity_uuid}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
               // 'Authorization':'Bearer '+swagger_API_KEY
            },
           // body: JSON.stringify({ admin_uuid : admin_uuid,status:status })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                const results = data.data;
                console.log(data);
                document.getElementById('titre_liste').innerHTML= "LISTE DES PAIEMENTS " + libelle_status + " "+libelle_paymode

                // Vérifie si le tableau a déjà été initialisé
                if ($.fn.DataTable && $.fn.DataTable.isDataTable('#datatable-custom')) {
                    // Détruire l'instance existante
                    $('#datatable-custom').DataTable().destroy();
                }

                $('#datatable-custom').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/2.0.2/i18n/fr-FR.json',
                    },
                    data: results,
                    columns: [
                        {
                            data: 'nom',
                            render: function(data, type, row) {
                                return ` ${data}  ${row.prenoms}`;
                            }
                        },
                        { data: 'telephone' },
                        { data: 'montant' },
                        { data: 'reference' },
                        { data: 'paymode' },
                        { data: 'transaction_id' },
                        {
                            data: 'state',
                            render: function(data, type, row) {
                                if(data ==="paid"){
                                    return `
                                    <span class="badge bg-success-subtle text-success"> Payé</span>
                                `;
                                }
                                else if(data ==="fail"){
                                    return `
                                    <span class="badge bg-danger-subtle text-danger"> Rejété </span>
                                `;
                                }else{
                                    return `
                                    <span class="badge bg-warning-subtle text-warning"> En attente </span>
                                `;
                                }

                            }
                        },
                        {
                            data: 'updated_at',
                            render: function(data, type, row) {
                                return `${data}`;
                            }
                        },
                    ]
                });

                // Ajouter un événement pour le bouton Détail
                $('#datatable-custom').on('click', '.btn-detail', function() {
                    const uuid = $(this).data('uuid');
                    console.log(uuid)
                    fetchCandidatDetail(uuid);
                });
            });
    }


    
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
    
                const ligne_facturations = results.ligne_facturations || [];
                let par_facturation = ""; // Utilisez let pour permettre la concaténation
    
                
                const montant_total_jour = parseFloat(results.montant_total_jour).toLocaleString('fr-FR', {
                    style: 'currency',
                    currency: 'XOF',
                });

                document.getElementById('montant_total_jour').innerHTML = montant_total_jour || '';
                document.getElementById('nb_total_jour').innerHTML = results.nb_total_jour || 0;

                const total_paiement = parseFloat(results.total_paiement).toLocaleString('fr-FR', {
                    style: 'currency',
                    currency: 'XOF',
                });

                document.getElementById('total_paiement').innerHTML = total_paiement || '';
                document.getElementById('nb_total').innerHTML = results.total_paiement_nbre || '';
    
                

                // Données pour le camembert
                const labels = [];
                const dataValues = [];
    
                const wave_montant = parseFloat(results.wave_montant).toLocaleString('fr-FR', {
                    style: 'currency',
                    currency: 'XOF',
                });

                 document.getElementById('wave_montant').innerHTML = wave_montant || '';
                document.getElementById('wave_nb').innerHTML = results.wave_nb || '';
    
                
                const orange_montant = parseFloat(results.orange_montant).toLocaleString('fr-FR', {
                    style: 'currency',
                    currency: 'XOF',
                });
                

                document.getElementById('orange_montant').innerHTML = orange_montant || '';
                document.getElementById('orange_nb').innerHTML = results.orange_nb || '';
 
                
                const mtn_montant = parseFloat(results.mtn_montant).toLocaleString('fr-FR', {
                    style: 'currency',
                    currency: 'XOF',
                });

                document.getElementById('mtn_montant').innerHTML = mtn_montant || '';
                document.getElementById('mtn_nb').innerHTML = results.mtn_nb || ''; 

                
                const moov_montant = parseFloat(results.moov_montant).toLocaleString('fr-FR', {
                    style: 'currency',
                    currency: 'XOF',
                });
                document.getElementById('moov_montant').innerHTML = moov_montant || '';
                document.getElementById('moov_nb').innerHTML = results.moov_nb || ''; 

                
                const tresor_montant = parseFloat(results.tresor_montant).toLocaleString('fr-FR', {
                    style: 'currency',
                    currency: 'XOF',
                });

                document.getElementById('tresor_montant').innerHTML = tresor_montant || '';
                document.getElementById('tresor_nb').innerHTML = results.tresor_nb || ''; 

                     // Données pour le camembert
                const labelsOperateurs = ['Wave','Orange', 'MTN','Moov','Trésor'];
                const dataValuesOperateurs = [
                    parseFloat(results.wave_montant || 0),
                    parseFloat(results.orange_montant || 0),
                    parseFloat(results.mtn_montant || 0),
                    parseFloat(results.moov_montant || 0),
                    parseFloat(results.tresor_montant || 0)
                ];

            
                // Générer le camembert
                const ctxOperateur = document.getElementById('OperateursChart').getContext('2d');
                new Chart(ctxOperateur, {
                    type: 'pie', // Type de graphique
                    data: {
                       labels: labelsOperateurs,
                        datasets: [{
                            label: 'Montant total par rubrique',
                            data: dataValuesOperateurs,
                            backgroundColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: false,
                        plugins: {
                            legend: {
                                display: false, // Masque la légende
                                position: 'left', // Place la légende à droite
                                labels: {
                                    align: 'end', // Aligne le texte des éléments de la légende à droite
                                    usePointStyle: true, // Affiche un point coloré au lieu d'un carré
                                    padding: 20 // Ajoute un espacement entre les éléments
                                }
                            },
                            title: {
                                display: true,
                                text: 'Répartition des montants par rubrique'
                            }
                        }
                    }
                });

                ligne_facturations.forEach(item => {
                            
                const total_amount = parseFloat(item.total_amount).toLocaleString('fr-FR', {
                    style: 'currency',
                    currency: 'XOF',
                });
                    par_facturation += `
                        <tr>
                            <td>${item.rubrique_name || ''} ${item.option_name || ''}</td>
                            <td>${item.line_count || '0'}</td>
                            <td>${total_amount || '0'}</td>
                        </tr>`;
                    
                    // Préparer les données pour le camembert
                    labels.push(`${item.rubrique_name || ''} ${item.option_name || ''}`);
                    dataValues.push(item.total_amount || 0);
                });
    
                // Mise à jour du tableau HTML
                const tableBody = document.getElementById('par_facturation');
                if (tableBody) {
                    tableBody.innerHTML = par_facturation;
                } else {
                    console.error("Élément avec l'ID 'par_facturation' introuvable dans le DOM.");
                }
    
                // Générer le camembert
                const ctx = document.getElementById('facturationChart').getContext('2d');
                new Chart(ctx, {
                    type: 'pie', // Type de graphique
                    data: {
                       labels: labels,
                        datasets: [{
                            label: 'Montant total par rubrique',
                            data: dataValues,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: false,
                        plugins: {
                            legend: {
                                display: false, // Masque la légende
                                position: 'left', // Place la légende à droite
                                labels: {
                                    align: 'end', // Aligne le texte des éléments de la légende à droite
                                    usePointStyle: true, // Affiche un point coloré au lieu d'un carré
                                    padding: 20 // Ajoute un espacement entre les éléments
                                }
                            },
                            title: {
                                display: true,
                                text: 'Répartition des montants par rubrique'
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des statistiques :', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Impossible de récupérer les statistiques. Veuillez réessayer.',
                });
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
