

$(document).ready(function() {
  //  findStatus('today','all');
    findStatistique();
    // Exécuter findStatistique toutes les 60 000 millisecondes (1 minute)
   // setInterval(findStatistique, 20000);
    //setInterval(findStatus('today','all'), 25000);

    //const intervalId = setInterval(findStatistique, 20000);
    //intervalId
    //setInterval(() => findStatistique(), 20000)

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

      
        var permissions = {
            montant_total_jour: canPermission('statistique_voir_le_montant_total_par_jour'),
            total_paiement: canPermission('statistique_voir_le_total_des_paiements'),
            par_paiement: canPermission('statistique_voir_les_statistiques_par_paiement'),
            par_paiement_detaille: canPermission('statistique_voir_les_statistiques_par_paiement_detaille'),
            par_operateur: canPermission('statistique_voir_les_statistiques_par_operateur'),
            par_rubrique: canPermission('statistique_voir_les_statistiques_par_rubrique'),
            par_periode: canPermission('statistique_voir_les_statistiques_par_periode'),
            par_rdv: canPermission('statistique_voir_les_statistiques_par_rendez_vous'),
        };
        
        if(permissions.par_paiement && permissions.par_paiement_detaille){
            if (type_stat === "paiement") {
                // Met à jour le titre avec un indicateur de chargement
                document.getElementById('titre_liste').innerHTML = `
                    <i class='fa fa-spinner fa-spin'></i> LISTE DES PAIEMENTS ${libelle_status} ${libelle_paymode} EN COURS DE CHARGEMENT ...
                `;
            
                // Effectue une requête pour récupérer les données de paiement
                fetch(`/panel/statistique/findStatus/data/${status}/${paymode}/${Entity_uuid}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Une erreur est survenue');
                    }
                    return response.json();
                })
                .then(data => {
                    const results = data.data;
                    const categories = Object.keys(data.chartsData);
                    const values = Object.values(data.chartsData);
            
                    // Configuration du graphe
                    const options = {
                        series: [{
                            name: "Nombre de paiement",
                            data: values,
                        }],
                        annotations: {
                            points: [{
                                x: 'Dates',
                                seriesIndex: 0,
                                label: {
                                    borderColor: '#775DD0',
                                    offsetY: 0,
                                    style: {
                                        color: '#fff',
                                        background: '#775DD0',
                                    },
                                    text: 'Évolution des paiements',
                                },
                            }]
                        },
                        chart: {
                            height: 350,
                            type: 'bar',
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 10,
                                columnWidth: '50%',
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            width: 0
                        },
                        grid: {
                            row: {
                                colors: ['#fff', '#f2f2f2']
                            }
                        },
                        xaxis: {
                            labels: {
                                rotate: -45
                            },
                            categories: categories,
                            tickPlacement: 'on',
                        },
                        yaxis: {
                            title: {
                                text: "Nombre de paiement",
                            },
                        },
                        fill: {
                            colors: ['#008FFB'],
                        }
                    };
            
                    // Détruit le graphe existant pour éviter les doublons
                    const chartContainer = document.querySelector("#chartPaiement");
                    if (chartContainer._chartInstance) {
                        chartContainer._chartInstance.destroy();
                    }
                    const chart = new ApexCharts(chartContainer, options);
                    chartContainer._chartInstance = chart;
                    chart.render();


            
                    // Met à jour le titre
                    document.getElementById('titre_liste').innerHTML = `
                        LISTE DES PAIEMENTS ${libelle_status} ${libelle_paymode}
                    `;
            
                    // Réinitialise le tableau si déjà initialisé
                    if ($.fn.DataTable && $.fn.DataTable.isDataTable('#datatable-custom')) {
                        $('#datatable-custom').DataTable().destroy();
                    }
            
                    // Initialise le tableau avec les nouvelles données
                    $('#datatable-custom').DataTable({
                        language: {
                            url: '//cdn.datatables.net/plug-ins/2.0.2/i18n/fr-FR.json',
                        },
                        data: results,
                        columns: [
                            { data: 'nom_du_proprietaire' },
                            { data: 'numero_de_la_carte_grise' },
                            { data: 'numero_dimmatriculation' },
                            { data: 'telephone' },
                            //{ data: 'amount' },
                            { data: 'reference' },
                            { data: 'operateur_uuid' },
                            { data: 'transaction_id' },
                            {
                                data: 'paiement_state',
                                render: (data) => {
                                    const statusClasses = {
                                        paid: "badge bg-success-subtle text-success",
                                        fail: "badge bg-danger-subtle text-danger",
                                        default: "badge bg-warning-subtle text-warning"
                                    };
                                    return `
                                        <span class="${statusClasses[data] || statusClasses.default}">
                                            ${data === "paid" ? "Payé" : data === "fail" ? "Rejeté" : data}
                                        </span>
                                    `;
                                }
                            },
                            { data: 'created_at' }
                        ]
                    });
            
                    // Gère le clic sur les boutons détail
                    $('#datatable-custom').on('click', '.btn-detail', function() {
                        const uuid = $(this).data('uuid');
                        fetchCandidatDetail(uuid);
                    });
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données :', error);
                });
            }
            
        
        }

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
                const stat = results.cheque_stats;
                const stat_mobile = results.stats;
                const stat_penalty = results.penalty_stats;
     
      
                let total_paiement_cheque = stat.total_paiement_cheque || 0;
                let nb_total_cheque = stat.total_cheque || 0;
                let total_carte_valide = (stat.total_carte_valide || 0);
                let cumul_paiements = total_paiement_cheque + (stat_mobile.montant_global || 0) + (stat_penalty.montant_cartes_total || 0); // + stat_penalty.district_share_total || 0

               var permissions = {
                //montant_total_jour: canPermission('statistique_voir_le_montant_total_par_jour'),
                //montant_total_jour_global: canPermission('statistique_voir_le_montant_total_par_jour_global'),

                //total_paiement: canPermission('statistique_voir_le_total_des_paiements'),
                //total_paiement_global: canPermission('statistique_voir_le_total_des_paiements_global'),

                //par_paiement: canPermission('statistique_voir_les_statistiques_par_paiement'),
                //par_paiement_detaille: canPermission('statistique_voir_les_statistiques_par_paiement_detaille'),
                
                //par_operateur: canPermission('statistique_voir_les_statistiques_par_operateur'),
                //par_rubrique: canPermission('statistique_voir_les_statistiques_par_rubrique'),
                //par_rubrique_global: canPermission('statistique_voir_les_statistiques_par_rubrique_global'),
                //par_periode: canPermission('statistique_voir_les_statistiques_par_periode'),
                //par_rdv: canPermission('statistique_voir_les_statistiques_par_rendez_vous'),
                par_validation_jour: canPermission('statistique_voir_les_statistiques_par_validations_par_jour'),
                agent_validateur: canPermission('statistique_voir_les_statistiques_par_agent_validateur'),
                

                statistique_partenaires_voir_les_statistiques_global: canPermission('statistique_partenaires_voir_les_statistiques_global_par_rubrique'),
                statistique_partenaires_voir_les_statistiques_par_mois: canPermission('statistique_partenaires_voir_les_statistiques_par_mois_par_rubrique'),
                statistique_partenaires_voir_les_statistiques_par_jour: canPermission('statistique_partenaires_voir_les_statistiques_par_jour_par_rubrique'),
                statistique_partenaires_voir_les_statistiques_par_periode:canPermission('statistique_partenaires_voir_les_statistiques_par_periode'),
                statistique_partenaires_voir_les_statistiques_graphique_par_paiement_journalier: canPermission('statistique_partenaires_voir_les_statistiques_graphique_par_paiement_journalier'),
                statistique_partenaires_voir_les_statistiques_graphique_par_paiement_mensuel:canPermission('statistique_partenaires_voir_les_statistiques_graphique_par_paiement_mensuel'),
                statistique_partenaires_voir_le_montant_total_par_jour:canPermission('statistique_partenaires_voir_le_montant_total_par_jour'),
                statistique_partenaires_voir_le_montant_total:canPermission('statistique_partenaires_voir_le_montant_total')

            };
            const today = new Date().toISOString().split('T')[0];
            if(type_stat === "paiement"){
                if(permissions.statistique_partenaires_voir_le_montant_total_par_jour){
                    

                    const montant_total_jour = parseFloat((stat_mobile.par_jour?.[today]?.montant_total ?? 0)).toLocaleString('fr-FR', {
                        style: 'currency',
                        currency: 'XOF',
                    });
                    

                    document.getElementById('montant_total_jour').innerHTML = montant_total_jour || '';
                    document.getElementById('nb_total_jour').innerHTML = stat_mobile.par_jour?.[today]?.nombre_lignes || 0;
                }

                /* ######################################################### */
                if(permissions.statistique_partenaires_voir_le_montant_total_par_jour){

                    const total_paiement_cheque_j = parseFloat((stat.total_paiement_cheque_journalier ?? 0)).toLocaleString('fr-FR', {
                        style: 'currency',
                        currency: 'XOF',
                    });
                    

                    document.getElementById('total_paiement_cheque_j').innerHTML = total_paiement_cheque_j || '';
                    //document.getElementById('nb_total_cheque_j').innerHTML = nb_total_cheque_j || 0;
                    document.getElementById('nb_total_carte_j').innerHTML = stat.total_carte_valide_journalier || 0;

                    const today = new Date().toISOString().split('T')[0];  
                    const somme_paiement_jour = (stat.total_paiement_cheque_journalier ?? 0)+(stat_mobile.par_jour?.[today]?.montant_total ?? 0)                 
                    const cumul_paiements_jour = parseFloat(somme_paiement_jour).toLocaleString('fr-FR', {//+stat_penalty.recap_par_jour?.[today]?.district_share_total || 0
                        style: 'currency',
                        currency: 'XOF',
                    });

                    document.getElementById('cumul_paiements_jour').innerHTML = cumul_paiements_jour || 0;
                    document.getElementById('cumul_nbre_paiements_jour').innerHTML = (stat.total_carte_valide_journalier || 0)+(stat_mobile.par_jour?.[today]?.nombre_lignes || 0) + (stat_penalty.recap_par_jour?.[today]?.total_cartes || 0);

                    const total_penalite_j = stat_penalty.recap_par_jour?.[today]?.district_share_total || 0;
                    const nb_total_penalite_j = stat_penalty.recap_par_jour?.[today]?.total_cartes || 0;
                    
                    document.getElementById('nb_total_penalite_j').innerHTML = nb_total_penalite_j || 0;
                    document.getElementById('total_penalite_j').innerHTML = total_penalite_j || 0;
                    
                } 

                              
                if(permissions.statistique_partenaires_voir_le_montant_total){
                    const total_paiement_chequeF = parseFloat(total_paiement_cheque).toLocaleString('fr-FR', {
                        style: 'currency',
                        currency: 'XOF',
                    });

                    document.getElementById('total_paiement_cheque').innerHTML = total_paiement_chequeF || '';
                    document.getElementById('nb_total_cheque').innerHTML = nb_total_cheque || '';
                    document.getElementById('total_carte_valide_cheque').innerHTML = total_carte_valide || '';


                    
                    const cumul_paiement_chequeF = parseFloat(cumul_paiements).toLocaleString('fr-FR', {
                        style: 'currency',
                        currency: 'XOF',
                    });

                    document.getElementById('cumul_paiements').innerHTML = cumul_paiement_chequeF || '';
                    document.getElementById('cumul_carte_valide').innerHTML = (stat_mobile.nombre_lignes_global || 0) + total_carte_valide + (stat_penalty.nbre_global_penalite || 0);
                    
                } 

                /* ######################################################### */
              
                if(permissions.statistique_partenaires_voir_le_montant_total){
                    const total_paiement = parseFloat((stat_mobile.montant_global || 0) + (stat_penalty.montant_cartes_total || 0)).toLocaleString('fr-FR', {
                        style: 'currency',
                        currency: 'XOF',
                    });

                    document.getElementById('total_paiement').innerHTML = total_paiement || '';
                    document.getElementById('nb_total').innerHTML = (stat_mobile.nombre_lignes_global || 0) + (stat_penalty.nbre_global_penalite || 0);
                }
                
                     
                if(permissions.statistique_partenaires_voir_le_montant_total){
                    const total_paiement_penalite = parseFloat(stat_penalty.district_share_total || 0).toLocaleString('fr-FR', {
                        style: 'currency',
                        currency: 'XOF',
                    });

                    document.getElementById('total_paiement_penalite').innerHTML = total_paiement_penalite || '';
                    document.getElementById('total_nbre_penalite').innerHTML = stat_penalty.nbre_global_penalite || 0;
                }
                
                if(permissions.statistique_partenaires_voir_les_statistiques_graphique_par_paiement_mensuel){
                    /* ######################################################################### */
                    /* ######################################################################### */

                    const labels_mois = Object.keys(stat.par_mois); // Liste des mois

                    const amounts_mois_cheque = labels_mois.map(mois => parseFloat(stat.par_mois[mois].montant_total));
                    const amounts_mois_mobile = labels_mois.map(mois => 
                        stat_mobile.par_mois[mois] ? parseFloat(stat_mobile.par_mois[mois].montant_total) : 0
                    );
                    const amounts_mois_total = labels_mois.map((mois, index) => amounts_mois_cheque[index] + amounts_mois_mobile[index]);
                    
                    // 📌 Vérification des données récupérées
                    //console.log("Mois:", labels_mois);
                    //console.log("Montants Chèques:", amounts_mois_cheque);
                    //console.log("Montants Mobile:", amounts_mois_mobile);
                    //console.log("Montants Cumulés:", amounts_mois_total);
                    
                    const canvas = document.getElementById('chartPaiementMois');
                    
                    if (!canvas) {
                        console.error("Erreur : L'élément canvas avec l'ID 'chartPaiementMois' n'existe pas.");
                        return;
                    }
                    
                    const ctx = canvas.getContext('2d');
                    
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels_mois,
                            datasets: [
                                {
                                    label: 'Paiements par Chèque',
                                    data: amounts_mois_cheque,
                                    backgroundColor: 'rgba(54, 162, 235, 0.6)', // Bleu
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Paiements Mobiles',
                                    data: amounts_mois_mobile,
                                    backgroundColor: 'rgba(255, 99, 132, 0.6)', // Rouge
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Cumul des Paiements',
                                    data: amounts_mois_total,
                                    backgroundColor: 'rgba(75, 192, 192, 0.6)', // Vert
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                    





                        // 📌 Récupérer dynamiquement les données par mois
                        /* const labels_mois = Object.keys(stat.par_mois); // Liste des mois
                        const amounts_mois = labels_mois.map(mois => parseFloat(stat.par_mois[mois].montant_total));
                        
                        const labels_mois_mobile = Object.keys(stat_mobile.par_mois); // Liste des mois
                        const amounts_mois_mobile = labels_mois_mobile.map(mois => parseFloat(stat_mobile.par_mois[mois].montant_total));

                        // 📌 Vérification des données récupérées
                       // console.log("Mois:", labels_mois);
                       // console.log("Montants par Mois:", amounts_mois);

                        
                        const canvas = document.getElementById('chartPaiementMois');
                    
                        if (!canvas) {
                           // console.error("Erreur : L'élément canvas avec l'ID 'chartPaiementMois' n'existe pas.");
                            return;
                        }
                    
                        const ctx = canvas.getContext('2d');
                    
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels_mois,
                                datasets: [{
                                    label: 'Montant total des paiements',
                                    data: amounts_mois,
                                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        }); */
                }  
                    

                if(permissions.statistique_partenaires_voir_les_statistiques_graphique_par_paiement_journalier){
                    if (type_stat === "paiement") {     
                        // const results = stats.par_jour;
                        const categories = Object.keys(stat.par_jour);
                        const values = categories.map(jour => parseFloat(stat.par_jour[jour].nombre_lignes));
                        const valuesAmount = categories.map(jour => parseFloat(stat.par_jour[jour].montant_total));

                        // const values = Object.values(stats.par_mois);
                
                        // Configuration du graphe
                        const options = {
                            series: [{
                                name: "Nombre de paiement",
                                data: values,
                            }],
                            annotations: {
                                points: [{
                                    x: 'Dates',
                                    seriesIndex: 0,
                                    label: {
                                        borderColor: '#775DD0',
                                        offsetY: 0,
                                        style: {
                                            color: '#fff',
                                            background: '#775DD0',
                                        },
                                        text: 'Évolution des paiements',
                                    },
                                }]
                            },
                            chart: {
                                height: 350,
                                type: 'bar',
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: 10,
                                    columnWidth: '50%',
                                }
                            },
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                width: 0
                            },
                            grid: {
                                row: {
                                    colors: ['#fff', '#f2f2f2']
                                }
                            },
                            xaxis: {
                                labels: {
                                    rotate: -45
                                },
                                categories: categories,
                                tickPlacement: 'on',
                            },
                            yaxis: {
                                title: {
                                    text: "Nombre de paiement",
                                },
                            },
                            fill: {
                                colors: ['#008FFB'],
                            }
                        };
                
                        // Détruit le graphe existant pour éviter les doublons
                        const chartContainer = document.querySelector("#chartPaiement");
                        if (chartContainer._chartInstance) {
                            chartContainer._chartInstance.destroy();
                        }
                        const chart = new ApexCharts(chartContainer, options);
                        chartContainer._chartInstance = chart;
                        chart.render();
                    }
                }
                    /* ######################################################################### */
                    /* ######################################################################### */

               
                
                
            }  
     




             
           /*  if(permissions.montant_total_jour_global){
                if (type_stat === "paiement") {
                const montant_total_jour_global = parseFloat(results.montant_total_jour_global).toLocaleString('fr-FR', {
                    style: 'currency',
                    currency: 'XOF',
                });

                document.getElementById('montant_total_jour_global').innerHTML = montant_total_jour_global || '';
                document.getElementById('nb_total_jour_global').innerHTML = results.nb_total_jour_global || 0;
                }
            } */

           /*  if(permissions.total_paiement_global){
                if (type_stat === "paiement") {
                    const total_paiement_global = parseFloat(results.total_paiement_global).toLocaleString('fr-FR', {
                        style: 'currency',
                        currency: 'XOF',
                    });

                    document.getElementById('total_paiement_global').innerHTML = total_paiement_global || '';
                    document.getElementById('nb_total_global').innerHTML = results.total_paiement_nbre_global || '';
                }
            }  */ 
                
                // Données pour le camembert
                const labels = [];
                const dataValues = [];
              

                if(permissions.statistique_partenaires_voir_les_statistiques_par_jour){
                    if (type_stat === "rubrique" && type_sous_stat ==="jour") {
                        const today = new Date().toISOString().split('T')[0];
                        let totalLine = 0;
                        let totalAmount = 0;
                        let par_facturation = ""; // S'assurer que cette variable est bien initialisée
                        const rubrique_lines = stat.par_jour?.[today]?.details;
                    
                        if (Array.isArray(rubrique_lines) && rubrique_lines.length > 0) {
                            rubrique_lines.forEach(item => {
                                const total_amount = parseFloat(item.montant_total_ligne || 0).toLocaleString('fr-FR', {
                                    style: 'currency',
                                    currency: 'XOF',
                                });
                    
                                par_facturation += `
                                    <tr>
                                        <td>${item.rubrique_name || ''} ${item.option_name || ''}</td>
                                        <td>${parseInt(item.nombre_lignes || '0', 10)}</td>
                                        <td>${total_amount}</td>
                                    </tr>`;
                    
                                // Préparer les données pour le camembert
                                labels.push(`${item.rubrique_name || ''} ${item.option_name || ''}`);
                                dataValues.push(parseFloat(item.montant_total_ligne) || 0);
                    
                                totalLine += parseInt(item.nombre_lignes || '0', 10);
                                totalAmount += parseFloat(item.montant_total_ligne) || 0;
                            });
                    
                            // Ajout de la ligne total
                            par_facturation += `
                            <tr>
                                <td><strong>Total</strong></td>
                                <td><strong>${totalLine}</strong></td>
                                <td><strong>${totalAmount.toLocaleString('fr-FR', { style: 'currency', currency: 'XOF' })}</strong></td>
                            </tr>`;
                        }
                    
                        // Mise à jour du tableau HTML
                        const tableBody = document.getElementById('par_facturation');
                        if (tableBody) {
                            tableBody.innerHTML = par_facturation;
                        } else {
                            console.error("Élément avec l'ID 'par_facturation' introuvable dans le DOM.");
                        }
                    
                        // Générer des couleurs dynamiquement
                        const generateColors = (count, alpha = 0.2) => {
                            return Array.from({ length: count }, () => {
                                const r = Math.floor(Math.random() * 256);
                                const g = Math.floor(Math.random() * 256);
                                const b = Math.floor(Math.random() * 256);
                                return `rgba(${r}, ${g}, ${b}, ${alpha})`;
                            });
                        };
                    
                        // Générer les couleurs pour le graphique
                        const backgroundColors = generateColors(labels.length, 0.2);
                        const borderColors = generateColors(labels.length, 1);
                    
                        // Générer le camembert
                        const ctx = document.getElementById('facturationChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'pie', // Type de graphique
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Montant total par rubrique',
                                    data: dataValues,
                                    backgroundColor: backgroundColors, // Couleurs dynamiques pour l'arrière-plan
                                    borderColor: borderColors, // Couleurs dynamiques pour les bordures
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: false,
                                plugins: {
                                    legend: {
                                        display: true, // Afficher la légende
                                        position: 'right',
                                        labels: {
                                            align: 'end',
                                            usePointStyle: true,
                                            padding: 20
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: 'Répartition des montants par rubrique'
                                    }
                                }
                            }
                        });
                    }   
                }   
                
                if(permissions.statistique_partenaires_voir_les_statistiques_par_mois){
                   
                            /* if (type_stat === "rubrique" && type_sous_stat === "mois") {
                                const tableauStats = document.getElementById("tableauStats");
                                const headerRow1 = document.getElementById("headerRow1"); // Première ligne d'en-tête
                                const headerRow2 = document.getElementById("headerRow2"); // Deuxième ligne d'en-tête
                                const tableBody = document.getElementById("tableBody");
                            
                                // Vider les anciennes données du tableau
                                headerRow1.innerHTML = "<th rowspan='2'>Rubrique</th>"; // En-tête principale
                                headerRow2.innerHTML = ""; // Deuxième ligne des sous-en-têtes
                                tableBody.innerHTML = ""; // Corps du tableau
                            
                                // Récupérer toutes les dates uniques
                                let dates = Object.keys(stat.global_par_mois);
                            
                                // Ajouter les en-têtes des mois (fusionnés)
                                dates.forEach(date => {
                                    let thMois = document.createElement("th");
                                    thMois.textContent = date;
                                    thMois.colSpan = 2; // Fusionner deux colonnes (Nombre et Montant)
                                    headerRow1.appendChild(thMois);
                            
                                    // Ajouter les sous-colonnes "Nombre" et "Montant"
                                    let thNombre = document.createElement("th");
                                    thNombre.textContent = "Nombre";
                                    headerRow2.appendChild(thNombre);
                            
                                    let thMontant = document.createElement("th");
                                    thMontant.textContent = "Montant";
                                    headerRow2.appendChild(thMontant);
                                });
                            
                               
                                // Préparer un objet pour organiser les données par rubrique
                                let rubriques = {};
                                let totalParMois = {}; // Stocker les totaux par mois
                                let totalMontantParMois = {}; // Stocker les montants totaux par mois
                            
                                // Remplir l'objet rubriques avec les données
                                 dates.forEach(date => {
                                    stat.global_par_mois[date].forEach(item => {
                                        let key = `${item.rubrique_name} - ${item.option_name || ''}`;
                                        if (!rubriques[key]) {
                                            rubriques[key] = { 
                                                "rubrique_name": item.rubrique_name, 
                                                "data": {}, 
                                                "total_lignes": 0, 
                                                "total_montant": 0 
                                            };
                                        }
                                        rubriques[key].data[date] = {
                                            lignes: item.total_lignes,
                                            montant: item.total_montant
                                        };
                                        rubriques[key].total_lignes += item.total_lignes; // Total par rubrique
                                        rubriques[key].total_montant += item.total_montant; // Montant total par rubrique
                            
                                        // Ajouter au total général par mois
                                        totalParMois[date] = (totalParMois[date] || 0) + item.total_lignes;
                                        totalMontantParMois[date] = (totalMontantParMois[date] || 0) + item.total_montant;
                                    });
                                }); 
                            
                              
                                
                                // Générer le tableau en fonction des rubriques
                                Object.keys(rubriques).forEach(key => {
                                    let row = document.createElement("tr");
                            
                                    // Colonnes Rubrique et Option
                                    let cellRubrique = document.createElement("td");
                                    cellRubrique.textContent = rubriques[key].rubrique_name;
                                    row.appendChild(cellRubrique);
                            
                                    // Ajouter les valeurs par date (Nombre et Montant côte à côte)
                                    dates.forEach(date => {
                                        let data = rubriques[key].data[date] || { lignes: 0, montant: 0 };
                            
                                        let cellLignes = document.createElement("td");
                                        cellLignes.textContent = data.lignes;
                                        cellLignes.style.fontWeight = "bold";

                                        row.appendChild(cellLignes);
                            
                                        let cellMontant = document.createElement("td");
                                        cellMontant.textContent = data.montant.toLocaleString() + " F";
                                    });
                            
                                    // Ajouter la colonne "Total Nombre" par rubrique
                                    let cellTotalLignes = document.createElement("td");
                                    cellTotalLignes.textContent = rubriques[key].total_lignes;
                                    cellTotalLignes.style.fontWeight = "bold";
                                    row.appendChild(cellTotalLignes);
                            
                                    // Ajouter la colonne "Montant Total" par rubrique
                                    let cellMontantTotal = document.createElement("td");
                                    cellMontantTotal.textContent = rubriques[key].total_montant.toLocaleString() + " F";
                                    cellMontantTotal.style.fontWeight = "bold";
                            
                                    tableBody.appendChild(row);
                                });
                            
                                // Ajouter la ligne "Total Général" en bas du tableau
                                let totalRow = document.createElement("tr");
                                let totalLabelCell = document.createElement("td");
                                totalLabelCell.textContent = "TOTAL GENERAL";
                                totalLabelCell.style.fontWeight = "bold";
                                totalLabelCell.style.fontSize = "16px";
                                totalRow.appendChild(totalLabelCell);
                            
                                // Ajouter les valeurs des totaux par mois
                                dates.forEach(date => {
                                    let totalCellLignes = document.createElement("td");
                                    totalCellLignes.textContent = totalParMois[date] || 0;
                                    totalCellLignes.style.fontWeight = "bold";
                                    totalCellLignes.style.fontSize = "16px";
                            
                                    let totalCellMontant = document.createElement("td");
                                    totalCellMontant.textContent = totalMontantParMois[date].toLocaleString() + " F";
                                    totalCellMontant.style.fontWeight = "bold";
                                    totalCellMontant.style.fontSize = "16px";
                                });
                            
                                // Ajouter les totaux globaux
                                let totalLignesGlobal = Object.values(totalParMois).reduce((a, b) => a + b, 0);
                                let totalMontantGlobal = Object.values(totalMontantParMois).reduce((a, b) => a + b, 0);
                            
                                let totalLignesCell = document.createElement("td");
                                totalLignesCell.textContent = totalLignesGlobal;
                                totalLignesCell.style.fontWeight = "bold";
                                totalLignesCell.style.fontSize = "16px";
                                totalRow.appendChild(totalLignesCell);
                            
                                let totalMontantCell = document.createElement("td");
                                totalMontantCell.textContent = totalMontantGlobal.toLocaleString() + " F";
                                totalMontantCell.style.fontWeight = "bold";
                                totalMontantCell.style.fontSize = "16px";
                                totalRow.appendChild(totalMontantCell);
                            
                                tableBody.appendChild(totalRow);
                            } */
                       
                    
                    if (type_stat === "rubrique" && type_sous_stat === "mois") {
                        const tableauStats = document.getElementById("tableauStats");
                        const headerRow1 = document.getElementById("headerRow1"); // Première ligne d'en-tête
                        const headerRow2 = document.getElementById("headerRow2"); // Deuxième ligne d'en-tête
                        const tableBody = document.getElementById("tableBody");
                    
                        // Vider les anciennes données du tableau
                        headerRow1.innerHTML = "<th rowspan='2'>Rubrique</th>"; // En-tête principale
                        headerRow2.innerHTML = ""; // Deuxième ligne des sous-en-têtes
                        tableBody.innerHTML = ""; // Corps du tableau
                    
                        // Récupérer toutes les dates uniques
                        let dates = Object.keys(stat.global_par_mois);
                    
                        // Ajouter les en-têtes des mois (fusionnés)
                        dates.forEach(date => {
                            let thMois = document.createElement("th");
                            thMois.textContent = date;
                            thMois.colSpan = 2; // Fusionner deux colonnes (Nombre et Montant)
                            headerRow1.appendChild(thMois);
                    
                            // Ajouter les sous-colonnes "Nombre" et "Montant"
                            let thNombre = document.createElement("th");
                            thNombre.textContent = "Nombre";
                            headerRow2.appendChild(thNombre);
                    
                            let thMontant = document.createElement("th");
                            thMontant.textContent = "Montant";
                            headerRow2.appendChild(thMontant);
                        });
                    
                        // Préparer un objet pour organiser les données par rubrique
                        let rubriques = {};
                        let totalParMois = {}; // Stocker les totaux par mois
                        let totalMontantParMois = {}; // Stocker les montants totaux par mois
                    
                        // Remplir l'objet rubriques avec les données
                        dates.forEach(date => {
                            stat.global_par_mois[date].forEach(item => {
                                let key = `${item.rubrique_name} - ${item.option_name || ''}`;
                                if (!rubriques[key]) {
                                    rubriques[key] = { 
                                        "rubrique_name": item.rubrique_name, 
                                        "data": {}, 
                                        "total_lignes": 0, 
                                        "total_montant": 0 
                                    };
                                }
                                rubriques[key].data[date] = {
                                    lignes: item.total_lignes,
                                    montant: item.total_montant
                                };
                                rubriques[key].total_lignes += item.total_lignes; // Total par rubrique
                                rubriques[key].total_montant += item.total_montant; // Montant total par rubrique
                    
                                // Ajouter au total général par mois
                                totalParMois[date] = (totalParMois[date] || 0) + item.total_lignes;
                                totalMontantParMois[date] = (totalMontantParMois[date] || 0) + item.total_montant;
                            });
                        });
                    
                        // Générer le tableau en fonction des rubriques
                        Object.keys(rubriques).forEach(key => {
                            let row = document.createElement("tr");
                    
                            // Colonnes Rubrique et Option
                            let cellRubrique = document.createElement("td");
                            cellRubrique.textContent = rubriques[key].rubrique_name;
                            row.appendChild(cellRubrique);
                    
                            // Ajouter les valeurs par date (Nombre et Montant côte à côte)
                            dates.forEach(date => {
                                let data = rubriques[key].data[date] || { lignes: 0, montant: 0 };
                    
                                // Colonne "Nombre"
                                let cellLignes = document.createElement("td");
                                cellLignes.textContent = data.lignes;
                                cellLignes.style.fontWeight = "bold";
                                row.appendChild(cellLignes);
                    
                                // Colonne "Montant"
                                let cellMontant = document.createElement("td");
                                cellMontant.textContent = data.montant.toLocaleString() + " F"; // Formatage du montant
                                cellMontant.style.fontWeight = "bold";
                                row.appendChild(cellMontant); // Ajouter la cellule à la ligne
                            });
                    
                            // Supprimer les colonnes "Total Nombre" et "Montant Total" par rubrique
                            // Ces lignes ont été supprimées pour retirer le dernier bloc à droite
                            tableBody.appendChild(row);
                        });
                    
                        // Ajouter la ligne "Total Général" en bas du tableau
                        let totalRow = document.createElement("tr");
                        let totalLabelCell = document.createElement("td");
                        totalLabelCell.textContent = "TOTAL GENERAL";
                        totalLabelCell.style.fontWeight = "bold";
                        totalLabelCell.style.fontSize = "16px";
                        totalRow.appendChild(totalLabelCell);
                    
                        // Ajouter les valeurs des totaux par mois
                        dates.forEach(date => {
                            let totalCellLignes = document.createElement("td");
                            totalCellLignes.textContent = totalParMois[date] || 0;
                            totalCellLignes.style.fontWeight = "bold";
                            totalCellLignes.style.fontSize = "16px";
                            totalRow.appendChild(totalCellLignes);
                    
                            let totalCellMontant = document.createElement("td");
                            totalCellMontant.textContent = (totalMontantParMois[date] || 0).toLocaleString() + " F"; // Formatage du montant
                            totalCellMontant.style.fontWeight = "bold";
                            totalCellMontant.style.fontSize = "16px";
                            totalRow.appendChild(totalCellMontant);
                        });
                    
                       
                        tableBody.appendChild(totalRow);
                    }
                }
             
                
                if(permissions.statistique_partenaires_voir_les_statistiques_global){
                        if (type_stat === "rubrique"  && type_sous_stat ==="tous") {
                            let totalLine = 0;
                            let totalAmount = 0;
                            let par_facturation = ""; // Initialisation de la variable pour stocker les lignes du tableau
                            const global_par_rubrique = stat.global_par_rubrique;
                        
                            if (global_par_rubrique.length > 0) {
                                global_par_rubrique.forEach(item => {
                                   
                                    // Extraction et formatage des valeurs
                                    const rubriqueName = item.rubrique_name || '';
                                    const optionName = item.option_name ? ` - ${item.option_name}` : '';
                                    const totalLignes = parseInt(item.total_lignes || '0', 10);
                                    const totalMontant = parseFloat(item.total_montant || 0);
                        
                                    // Formatage monétaire
                                    const totalAmountFormatted = totalMontant.toLocaleString('fr-FR', {
                                        style: 'currency',
                                        currency: 'XOF',
                                    });
                        
                                    // Ajout d'une ligne dans le tableau HTML
                                    par_facturation += `
                                        <tr>
                                            <td>${rubriqueName}${optionName}</td>
                                            <td>${totalLignes}</td>
                                            <td>${totalAmountFormatted}</td>
                                        </tr>`;
                        
                                    // Ajout des données pour le graphique
                                    labels.push(`${rubriqueName}${optionName}`);
                                    dataValues.push(totalMontant);
                        
                                    // Mise à jour des totaux globaux
                                    totalLine += totalLignes;
                                    totalAmount += totalMontant;
                                });
                        
                                // Ajout de la ligne "Total" à la fin du tableau
                                par_facturation += `
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td><strong>${totalLine}</strong></td>
                                    <td><strong>${totalAmount.toLocaleString('fr-FR', { style: 'currency', currency: 'XOF' })}</strong></td>
                                </tr>`;
                            } else {
                                par_facturation = "<tr><td colspan='3'>Aucune donnée disponible</td></tr>";
                            }
                        
                            // Mise à jour du tableau HTML
                            const tableBody = document.getElementById('par_facturation-global');
                            if (tableBody) {
                                tableBody.innerHTML = par_facturation;
                            } else {
                                console.error("Élément avec l'ID 'par_facturation' introuvable dans le DOM.");
                            }
                        }
                
                }  


                if(permissions.statistique_partenaires_voir_les_statistiques_par_periode){
                    if(type_stat === "periode"){
                        let render_periode = '';
                        let montantTotalPeriode = 0;
                        const labels = [];
                        const dataValues = [];

                        Object.keys(stat.par_jour).forEach(date => {
                            const { nombre_lignes, montant_total } = stat.par_jour[date]; // Extraction des valeurs

                            const total_amount = parseFloat(montant_total || 0).toLocaleString('fr-FR', {
                                style: 'currency',
                                currency: 'XOF',
                            });

                            render_periode += `
                                <tr>
                                    <td>${date || ''}</td>
                                    <td>${nombre_lignes || '0'}</td>
                                    <td>${total_amount || '0'}</td>                                   
                                </tr>`;
                            
                            montantTotalPeriode += parseFloat(montant_total || 0);

                            // Préparer les données pour le camembert
                            labels.push(date);
                            dataValues.push(parseFloat(montant_total || 0));
                        });

                    
                        const MontantTotal_P = parseFloat(montantTotalPeriode).toLocaleString('fr-FR', {
                            style: 'currency',
                            currency: 'XOF',
                        });
                    
                        document.getElementById('montant_total_periode').innerHTML = MontantTotal_P;
                    
                        
                        const categories = Object.keys(stat.par_jour);
                       // const values = categories.map(jour => parseFloat(stat.par_jour[jour].nombre_lignes));
                        const values = categories.map(jour => parseFloat(stat.par_jour[jour].montant_total));

                    
                        var options = {
                            series: [{
                                name: "Nombre de paiement",
                                data: values
                            }],
                            annotations: {
                                points: [{
                                    x: 'Dates',
                                    seriesIndex: 0,
                                    label: {
                                        borderColor: '#775DD0',
                                        offsetY: 0,
                                        style: {
                                            color: '#fff',
                                            background: '#775DD0',
                                        },
                                        text: 'Evolution des paiements',
                                    }
                                }]
                            },
                            chart: {
                                height: 350,
                                type: 'bar',
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: 10,
                                    columnWidth: '50%',
                                }
                            },
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                width: 0
                            },
                            grid: {
                                row: {
                                    colors: ['#fff', '#f2f2f2']
                                }
                            },
                            xaxis: {
                                labels: {
                                    rotate: -45
                                },
                                categories: categories,
                                tickPlacement: 'on'
                            },
                            yaxis: {
                                title: {
                                    text: "Nombre de paiement",
                                },
                            },
                            fill: {
                                colors: ['#008FFB'], // Remplacez par la couleur désirée
                            }
                        };
                    
                        var chart = new ApexCharts(document.querySelector("#periodeChart"), options);
                        chart.render();
                    
                        // Mise à jour du tableau HTML
                        const tableBody = document.getElementById('render_periode');
                        if (tableBody) {
                            tableBody.innerHTML = render_periode;
                        } else {
                            console.error("Élément avec l'ID 'render_periode' introuvable dans le DOM.");
                        }



                            // Gestion du clic sur les boutons "Voir Détails"
                            document.querySelectorAll('.btn-details').forEach(button => {
                                button.addEventListener('click', function () {
                                    const selectedDate = this.getAttribute('data-date');
                                    afficherDetailsLignesDuJour(selectedDate);
                                });
                            });
                    }
                }

                if(permissions.par_validation_jour){
                    if(type_stat === "validation_jour"){
                        validationJ();
                    }
                    
                }

                if(permissions.agent_validateur){
                    if(type_stat === "agent_validateur"){
                        par_validateur();
                    }
                }


                
                    

                

            })
            .catch(error => {
                console.error('Erreur lors de la récupération des statistiques :', error);
               /*  Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Impossible de récupérer les statistiques. Veuillez réessayer.',
                }); */
            });
    }
   

    function afficherDetailsLignesDuJour(date) {
        alert("Affichage des détails pour la date : " + date);
        // Tu peux ici récupérer plus d'infos via une requête AJAX ou afficher un modal.
    }


    function multipleDeMille(amount) {
        amount = Math.ceil(amount);
        let result = Math.ceil(amount / 1000) * 1000;
    
        // Assurer que la moitié du résultat est aussi un multiple de 1000
        if ((result / 2) % 1000 !== 0) {
            result -= 1000; // Ajustement vers le bas
        }
    
        return result;
    }

    function rdvToday(){
        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0');
        const month = String(today.getMonth() + 1).padStart(2, '0'); // Les mois commencent à 0
        const year = today.getFullYear();
        const date_rdv = `${year}-${month}-${day}`; // Format : "04-01-2025"

        // Effectuer une requête fetch pour récupérer les rendez-vous de cette date
             fetch(`/panel/statistique/data/rendezvous/${Entity_uuid}/${date_rdv}`)
             .then(response => response.json())
             .then(data => {
                     document.getElementById('titre_rdv').innerHTML= "LISTE DES RENDEZ VOUS DU " + date_rdv 
                     // Vérifie si le tableau a déjà été initialisé
                     if ($.fn.DataTable && $.fn.DataTable.isDataTable('#datatable-rdv')) {
                         // Détruire l'instance existante
                         $('#datatable-rdv').DataTable().destroy();
                     }

                     $('#datatable-rdv').DataTable({
                         language: {
                             url: '//cdn.datatables.net/plug-ins/2.0.2/i18n/fr-FR.json',
                         },
                         data: data.data,
                         columns: [
                             { data: 'date_rdv' },
                             {
                                 data: 'nom_du_proprietaire',
                                 render: function(data, type, row) {
                                     return ` ${data}`;
                                 }
                             },                        {
                                 data: 'numero_de_la_carte_grise',
                                 render: function(data, type, row) {
                                     return ` ${data}`;
                                 }
                             },                        {
                                 data: 'numero_dimmatriculation',
                                 render: function(data, type, row) {
                                     return ` ${data}`;
                                 }
                             },
                             { data: 'telephone' },
                             { data: 'amount' },
                             { data: 'reference' },
                             { data: 'operateur_uuid' },
                             { data: 'transaction_id' },
                             {
                                 data: 'state',
                                 render: function(data, type, row) {
                                     if(data ==="validate"){
                                         return `
                                         <span class="badge bg-success-subtle text-success"> Validé</span>
                                     `;
                                     }
                                     else if(data ==="fail"){
                                         return `
                                         <span class="badge bg-danger-subtle text-danger"> Rejété </span>
                                     `;
                                     }
                                     else if(data ==="enable"){
                                         return `
                                         <span class="badge bg-danger-subtle text-danger"> En attente de validation </span>
                                     `;
                                     }else{
                                         return `
                                         <span class="badge bg-warning-subtle text-warning"> ${data} </span>
                                     `;
                                     }

                                 }
                             },
                             {
                                 data: 'created_at',
                                 render: function(data, type, row) {
                                     return `${data}`;
                                 }
                             },
                         ]
                     });
                     
             })
             .catch(error => console.error('Erreur lors de la récupération des rendez-vous:', error));
    
    }

    function validationJ(day = 'all') {
        // Effectuer une requête fetch pour récupérer les données de validation
        fetch(`/panel/statistique/data/validation_j/${Entity_uuid}/${day}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erreur HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (!data || !data.data) {
                    throw new Error('Les données reçues sont invalides.');
                }
    
//console.log(data);
    
                // Préparer les catégories (dates) et les valeurs (nombre de validations)
                const categories = data.data.map(item => item.date_validation || 'Non spécifiée');
                const values = data.data.map(item => item.nombre || 0);
    
                // Configuration du graphique ApexCharts
                const options = {
                    series: [{
                        name: "Nombre de validation",
                        data: values,
                    }],
                    annotations: {
                        points: [{
                            x: 'Dates',
                            seriesIndex: 0,
                            label: {
                                borderColor: '#775DD0',
                                offsetY: 0,
                                style: {
                                    color: '#fff',
                                    background: '#775DD0',
                                },
                                text: 'Évolution des validations',
                            }
                        }]
                    },
                    chart: {
                        height: 350,
                        type: 'bar',
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 10,
                            columnWidth: '50%',
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: 0
                    },
                    grid: {
                        row: {
                            colors: ['#fff', '#f2f2f2']
                        }
                    },
                    xaxis: {
                        labels: {
                            rotate: -45
                        },
                        categories: categories,
                        tickPlacement: 'on'
                    },
                    yaxis: {
                        title: {
                            text: "Nombre de validation",
                        },
                    },
                    fill: {
                        colors: ['#008FFB'], // Couleur du graphique
                    }
                };
    
                // Initialiser ou mettre à jour le graphique
                const chartContainer = document.querySelector("#chartvalidationJ");
                if (chartContainer) {
                    chartContainer.innerHTML = ''; // Nettoyer le conteneur avant de recréer le graphique
                    const chart = new ApexCharts(chartContainer, options);
                    chart.render();
                } else {
                    console.error("Le conteneur #chartvalidationJ est introuvable.");
                }
    
                // Mettre à jour le titre
                const titreValidation = document.getElementById('titre_validation_jour');
                if (titreValidation) {
                    titreValidation.innerHTML = "HISTORIQUE DES VALIDATIONS PAR JOUR";
                }
    
                // Vérifier et réinitialiser le tableau si nécessaire
                if ($.fn.DataTable && $.fn.DataTable.isDataTable('#datatable-validationJ')) {
                    $('#datatable-validationJ').DataTable().destroy();
                }
    
                // Initialiser le tableau DataTables
                $('#datatable-validationJ').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/2.0.2/i18n/fr-FR.json',
                    },
                    data: data.data,
                    columns: [
                        { data: 'date_validation', title: 'Date de Validation' },
                        { data: 'nombre', title: 'Nombre de Validations' },
                    ],
                });
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des données:', error);
            });
    }
    

    function par_validateur() {
        // Effectuer une requête fetch pour récupérer les données de validation
        fetch(`/panel/statistique/data/validateur/${Entity_uuid}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erreur HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (!data || !data.data) {
                    throw new Error('Les données reçues sont invalides.');
                }
    
                //console.log(data);
    
                // Préparer les catégories (dates) et les valeurs (nombre de validations)
                const categories = data.data.map(item => item.firstname +' '+ item.lastname);
                const values = data.data.map(item => Math.ceil(item.nombre) || 0);
    
                // Configuration du graphique ApexCharts
                const options = {
                    series: [{
                        name: "Nombre de validation",
                        data: values,
                    }],
                    annotations: {
                        points: [{
                            x: 'Dates',
                            seriesIndex: 0,
                            label: {
                                borderColor: '#775DD0',
                                offsetY: 0,
                                style: {
                                    color: '#fff',
                                    background: '#775DD0',
                                },
                                text: 'Évolution des validations',
                            }
                        }]
                    },
                    chart: {
                        height: 350,
                        type: 'bar',
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 10,
                            columnWidth: '50%',
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: 0
                    },
                    grid: {
                        row: {
                            colors: ['#fff', '#f2f2f2']
                        }
                    },
                    xaxis: {
                        labels: {
                            rotate: -45
                        },
                        categories: categories,
                        tickPlacement: 'on'
                    },
                    yaxis: {
                        title: {
                            text: "Nombre de validation",
                        },
                    },
                    fill: {
                        colors: ['#008FFB'], // Couleur du graphique
                    }
                };
    
                // Initialiser ou mettre à jour le graphique
                const chartContainer = document.querySelector("#chartvalidateur");
                if (chartContainer) {
                    chartContainer.innerHTML = ''; // Nettoyer le conteneur avant de recréer le graphique
                    const chart = new ApexCharts(chartContainer, options);
                    chart.render();
                } else {
                    console.error("Le conteneur #chartvalidateur est introuvable.");
                }
    
                /* ############################################################# */

                // Configuration du graphique en camembert ApexCharts
                    const pieOptions = {
                        series: values, // Les valeurs des données
                        chart: {
                            height: 350,
                            type: 'pie', // Type Pie Chart
                        },
                        labels: categories, // Les catégories (dates)
                        colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0'], // Couleurs des parts
                        legend: {
                            position: 'bottom' // Position de la légende
                        },
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                chart: {
                                    width: 300
                                },
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }]
                    };

                    // Initialiser ou mettre à jour le graphique en camembert
                    const pieChartContainer = document.querySelector("#chartCamembert");
                    if (pieChartContainer) {
                        pieChartContainer.innerHTML = ''; // Nettoyer le conteneur avant de recréer le graphique
                        const pieChart = new ApexCharts(pieChartContainer, pieOptions);
                        pieChart.render();
                    } else {
                        console.error("Le conteneur #chartCamembert est introuvable.");
                    }

                /* ############################################################# */
                // Mettre à jour le titre
                const titreValidation = document.getElementById('titre_validateur');
                if (titreValidation) {
                    titreValidation.innerHTML = "HISTIORIQUE DES VALIDATIONS PAR AGENT";
                }
    
                // Vérifier et réinitialiser le tableau si nécessaire
                if ($.fn.DataTable && $.fn.DataTable.isDataTable('#datatable-validateur')) {
                    $('#datatable-validateur').DataTable().destroy();
                }
    
                // Initialiser le tableau DataTables
                $('#datatable-validateur').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/2.0.2/i18n/fr-FR.json',
                    },
                    data: data.data,
                    columns: [
                        {
                            data: 'firstname',title: 'Auteur de la Validation',
                            render: function(data, type, row) {
                                return ` ${data} ${row.lastname}`;
                            }
                        },
                        {
                            data: 'nombre',title: 'Nombre de Validations',
                            render: function(data, type, row) {
                                return ` ${Math.ceil(data)} `;
                            }
                        },
                    ],
                });
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des données:', error);
            });
    }
    

    document.querySelectorAll('.Load_paiement__').forEach(item => {
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


document.addEventListener("DOMContentLoaded", function () {
    const card = document.querySelector(".Load_paiement");
    const totalPaiement = document.getElementById("total_paiement_global");
    const nbTotalGlobal = document.getElementById("nb_total_global");

    if (card) {
        card.addEventListener("click", function () {
            
            // Basculer l'affichage des éléments
            totalPaiement.style.display = totalPaiement.style.display === "none" ? "inline" : "none";
            nbTotalGlobal.style.display = nbTotalGlobal.style.display === "none" ? "inline" : "none";
        });
    }
});