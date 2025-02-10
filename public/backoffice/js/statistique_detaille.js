

$(document).ready(function() {
    findStatus('today','all');
    findStatistique();
    // Exécuter findStatistique toutes les 60 000 millisecondes (1 minute)
   // setInterval(findStatistique, 20000);
    //setInterval(findStatus('today','all'), 25000);

   // const intervalId = setInterval(findStatistique, 60000);
    //intervalId
   // setInterval(() => findStatistique(), 20000)

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
                const stat = results.stats;
                //console.log("Résultats reçus :", results);
                console.log(stat);
               const chartMensuel = results.chart || [];  
               const rendezVous = results.rdv;
               // console.log("Résultats reçus :", results.rdv);

               const ligne_facturations = results.ligne_facturations || [];
               const ligne_facturations_global = results.ligne_facturations_global || [];
               let par_facturation = ""; // Utilisez let pour permettre la concaténation
               let par_facturation_global = ""; // Utilisez let pour permettre la concaténation
    
                const ligne_render_periode = results.ligne_render_periode || [];
                let render_periode ="";
                 
                
               var permissions = {
                montant_total_jour: canPermission('statistique_voir_le_montant_total_par_jour'),
                montant_total_jour_global: canPermission('statistique_voir_le_montant_total_par_jour_global'),

                total_paiement: canPermission('statistique_voir_le_total_des_paiements'),
                total_paiement_global: canPermission('statistique_voir_le_total_des_paiements_global'),

                par_paiement: canPermission('statistique_voir_les_statistiques_par_paiement'),
                par_paiement_detaille: canPermission('statistique_voir_les_statistiques_par_paiement_detaille'),
                
                par_operateur: canPermission('statistique_voir_les_statistiques_par_operateur'),
                par_rubrique: canPermission('statistique_voir_les_statistiques_par_rubrique'),
                par_rubrique_global: canPermission('statistique_voir_les_statistiques_par_rubrique_global'),
                par_periode: canPermission('statistique_voir_les_statistiques_par_periode'),
                par_rdv: canPermission('statistique_voir_les_statistiques_par_rendez_vous'),
                par_validation_jour: canPermission('statistique_voir_les_statistiques_par_validations_par_jour'),
                agent_validateur: canPermission('statistique_voir_les_statistiques_par_agent_validateur'),
                
            };
            
            if(permissions.montant_total_jour){
                if(type_stat === "paiement"){
                    const today = new Date().toISOString().split('T')[0];

                    const montant_total_jour = parseFloat((stat.par_jour?.[today]?.montant_total ?? 0)).toLocaleString('fr-FR', {
                        style: 'currency',
                        currency: 'XOF',
                    });
                    

                    document.getElementById('montant_total_jour').innerHTML = montant_total_jour || '';
                    document.getElementById('nb_total_jour').innerHTML = stat.par_jour?.[today]?.nombre_lignes || 0;
                }
              
            }  
     
            if(permissions.total_paiement ){
                if(type_stat === "paiement"){
                    const total_paiement = parseFloat(stat.montant_global).toLocaleString('fr-FR', {
                        style: 'currency',
                        currency: 'XOF',
                    });

                    document.getElementById('total_paiement').innerHTML = total_paiement || '';
                    document.getElementById('nb_total').innerHTML = stat.nombre_lignes_global || '';
                }

            } 



             
            if(permissions.montant_total_jour_global){
                const montant_total_jour_global = parseFloat(results.montant_total_jour_global).toLocaleString('fr-FR', {
                    style: 'currency',
                    currency: 'XOF',
                });

                document.getElementById('montant_total_jour_global').innerHTML = montant_total_jour_global || '';
                document.getElementById('nb_total_jour_global').innerHTML = results.nb_total_jour_global || 0;
            }

            if(permissions.total_paiement_global){
                const total_paiement_global = parseFloat(results.total_paiement_global).toLocaleString('fr-FR', {
                    style: 'currency',
                    currency: 'XOF',
                });

                document.getElementById('total_paiement_global').innerHTML = total_paiement_global || '';
                document.getElementById('nb_total_global').innerHTML = results.total_paiement_nbre_global || '';
            }  
                
                // Données pour le camembert
                const labels = [];
                const dataValues = [];
                if(permissions.par_operateur){
                    if(type_stat ==="operateur"){
                            

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
                            
                            var nb_total = results.tresor_nb + results.moov_nb + results.mtn_nb + results.orange_nb + results.wave_nb;
                            document.getElementById('nb_total').innerHTML = nb_total || ''; 

                            
                            var total_amount = parseFloat(results.wave_montant || 0)+
                            parseFloat(results.orange_montant || 0)+
                            parseFloat(results.mtn_montant || 0)+
                            parseFloat(results.moov_montant || 0)+
                            parseFloat(results.tresor_montant || 0);

                            const total_montant = parseFloat(total_amount).toLocaleString('fr-FR', {
                                style: 'currency',
                                currency: 'XOF',
                            });
                            document.getElementById('total_montant').innerHTML = total_montant || ''; 

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
                                            display: true, // Masque la légende
                                            position: 'right', // Place la légende à droite
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

                    }
                }

                if(permissions.par_rubrique){
                    if(type_stat === "rubrique"){
                        ligne_facturations.forEach(item => {
                                
                            const total_amount = parseFloat(item.total_amount).toLocaleString('fr-FR', {
                                style: 'currency',
                                currency: 'XOF',
                            });
                                par_facturation += `
                                    <tr>
                                        <td>${item.rubrique_name || ''} ${item.option_name || ''}</td>
                                        <td>${Math.ceil(item.line_count) || '0'}</td>
                                    </tr>`;
                                //<td>${total_amount || '0'}</td>Math.ceil(amount / 1000)
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
                                
                                        // Générer des couleurs dynamiquement
                            const generateColors = (count, alpha = 0.2) => {
                                const colors = [];
                                for (let i = 0; i < count; i++) {
                                    const r = Math.floor(Math.random() * 256);
                                    const g = Math.floor(Math.random() * 256);
                                    const b = Math.floor(Math.random() * 256);
                                    colors.push(`rgba(${r}, ${g}, ${b}, ${alpha})`);
                                }
                                return colors;
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
                                            display: true, // Masque la légende
                                            position: 'right', // Place la légende à gauche
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
            
                    }
                } 
             
                
                if(permissions.par_rubrique_global){
                    if(type_stat === "rubrique"){

                        /* ligne_facturations_global.forEach(item => {
                                
                            const total_amount = parseFloat(item.total_amount).toLocaleString('fr-FR', {
                                style: 'currency',
                                currency: 'XOF',
                            });
                            par_facturation_global += `
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
                            const tableBody = document.getElementById('par_facturation-global');
                            if (tableBody) {
                                tableBody.innerHTML = par_facturation_global;
                            } else {
                                console.error("Élément avec l'ID 'par_facturation' introuvable dans le DOM.");
                            }
                                
                                        // Générer des couleurs dynamiquement
                            const generateColors = (count, alpha = 0.2) => {
                                const colors = [];
                                for (let i = 0; i < count; i++) {
                                    const r = Math.floor(Math.random() * 256);
                                    const g = Math.floor(Math.random() * 256);
                                    const b = Math.floor(Math.random() * 256);
                                    colors.push(`rgba(${r}, ${g}, ${b}, ${alpha})`);
                                }
                                return colors;
                            };
            
                            // Générer les couleurs pour le graphique
                            const backgroundColors = generateColors(labels.length, 0.2);
                            const borderColors = generateColors(labels.length, 1);
            
                            // Générer le camembert
                            const ctx = document.getElementById('facturationChartGlobal').getContext('2d');
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
                                            display: true, // Masque la légende
                                            position: 'right', // Place la légende à gauche
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
                            }); */
            

                            // Initialisation des totaux
                            let totalCount = 0;
                            let totalAmount = 0;
                            let par_facturation_global = "";

                            ligne_facturations_global.forEach(item => {
                                const total_amount = parseFloat(item.total_amount).toLocaleString('fr-FR', {
                                    style: 'currency',
                                    currency: 'XOF',
                                });

                                totalCount += parseInt(item.line_count) || 0;
                                totalAmount += parseFloat(item.total_amount) || 0;

                                par_facturation_global += `
                                    <tr>
                                        <td>${item.rubrique_name || ''} ${item.option_name || ''}</td>
                                        <td>${item.line_count || '0'}</td>
                                        <td>${total_amount || '0'}</td>
                                    </tr>`;

                                // Préparer les données pour le camembert
                                labels.push(`${item.rubrique_name || ''} ${item.option_name || ''}`);
                                dataValues.push(item.total_amount || 0);
                            });

                            // Formatage du total montant
                            const totalAmountFormatted = totalAmount.toLocaleString('fr-FR', {
                                style: 'currency',
                                currency: 'XOF',
                            });

                            // Ajout du tfoot pour les totaux
                            par_facturation_global += `
                                <tfoot>
                                    <tr style="font-weight: bold;">
                                        <td>Total</td>
                                        <td>${totalCount}</td>
                                        <td>${totalAmountFormatted}</td>
                                    </tr>
                                </tfoot>`;

                            // Mise à jour du tableau HTML
                            const tableBody = document.getElementById('par_facturation-global');
                            if (tableBody) {
                                tableBody.innerHTML = par_facturation_global;
                            } else {
                                console.error("Élément avec l'ID 'par_facturation-global' introuvable dans le DOM.");
                            }

                            // Générer des couleurs dynamiques
                            const generateColors = (count, alpha = 0.2) => {
                                const colors = [];
                                for (let i = 0; i < count; i++) {
                                    const r = Math.floor(Math.random() * 256);
                                    const g = Math.floor(Math.random() * 256);
                                    const b = Math.floor(Math.random() * 256);
                                    colors.push(`rgba(${r}, ${g}, ${b}, ${alpha})`);
                                }
                                return colors;
                            };

                            // Générer les couleurs pour le graphique
                            const backgroundColors = generateColors(labels.length, 0.2);
                            const borderColors = generateColors(labels.length, 1);

                            // Générer le camembert
                            const ctx = document.getElementById('facturationChartGlobal').getContext('2d');
                            new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Montant total par rubrique',
                                        data: dataValues,
                                        backgroundColor: backgroundColors,
                                        borderColor: borderColors,
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: false,
                                    plugins: {
                                        legend: {
                                            display: true,
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

                if(permissions.par_rdv){

                    if(type_stat === "rdv"){
                        rdvToday();
                        // Récupérer l'élément <tbody> où les données seront injectées
                        var render_rdv = document.getElementById('rdv');

                        // Nettoyer le tableau avant d'ajouter les données
                        render_rdv.innerHTML = '';

                        // Parcourir le tableau et ajouter chaque ligne au tableau
                        rendezVous.forEach(item => {
                            // Créer une nouvelle ligne
                            var row = document.createElement('tr');
                            
                            // Ajouter les cellules pour chaque propriété
                            var dateCell = document.createElement('th');
                            dateCell.textContent = item.date_rdv;

                            var countCell = document.createElement('th');
                            countCell.textContent = item.total_prevu;

                            var effCell = document.createElement('th');
                            effCell.textContent = item.total_recu || 0;

                            var actionCell = document.createElement('th');
                            actionCell.innerHTML  = `<button class='btn btn-info show-rdv' data-rdv='${item.date_rdv}' > Voir la liste </button>`;

                            // Ajouter les cellules à la ligne
                            row.appendChild(dateCell);
                            row.appendChild(countCell);
                            row.appendChild(effCell);
                            row.appendChild(actionCell);
                            

                            // Ajouter la ligne au tableau
                            render_rdv.appendChild(row);
                        });
                        /* GRAPHE D'EVOLUTION */
                    // Filtrer les entrées valides
                    const validRendezVous = rendezVous.filter(item => item.date_rdv !== null);

                    // Créer les catégories et les données à partir des entrées valides
                    const categoriesrdv = validRendezVous.map(item => item.date_rdv); // Les dates
                    //const totalPrevu = validRendezVous.map(item => item.total_prevu || 0); // Par défaut 0 si non défini
                    const totalRecu = validRendezVous.map(item => item.total_recu || 0); // Par défaut 0 si non défini
                    
                    // Graphique ApexCharts
                   // console.log(categoriesrdv);
                    const options = {
                        series: [
                            /* { name: "Rendez-vous prévus", data: totalPrevu }, */
                            { name: "Rendez-vous reçus", data: totalRecu }
                        ],
                        chart: { height: 350, type: 'bar' },
                        plotOptions: {
                            bar: {
                                borderRadius: 5,
                                horizontal: false,
                                columnWidth: '45%' // Permet de donner de l'espace entre les barres
                            }
                        },
                        dataLabels: { enabled: false },
                        xaxis: {
                            categories: categoriesrdv,
                            title: { text: 'Dates' },
                            labels: {
                                rotate: -45, // Pour éviter l'écrasement des labels
                            },
                        },
                     /*    yaxis: { title: { text: 'Nombre de rendez-vous' } },
                        fill: { opacity: 1 },
                        legend: { position: 'top' },
                        colors: ['#008FFB', '#00E396'],
                        grid: {
                            row: {
                                colors: ['#fff', '#f2f2f2'] // Alternance de couleurs de fond
                            }
                        },
                        tooltip: {
                            shared: true, // Affiche un tooltip partagé
                            intersect: false,
                        }, */
                    };
                    
                    const chartRDV = new ApexCharts(document.querySelector("#rendezvousChart"), options);
                    chartRDV.render();
                    /* ACTION POUR AFFICHER LA LISTE */
                        document.getElementById('rdv').addEventListener('click', function(event) {
                            if (event.target.classList.contains('show-rdv')) {
                                var date_rdv = event.target.getAttribute('data-rdv');
                        
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
                        });
                    }
                }


                if(permissions.par_rdv){
                    if(type_stat === "periode"){
                        let montantTotalPeriode = 0;
                        let render_periode = '';  // Assurez-vous que cette variable est initialisée
                        let labels = [];  // Pour le camembert
                        let dataValues = [];  // Pour le camembert
                    
                        ligne_render_periode.forEach(item => {
                            const total_amount = parseFloat(multipleDeMille(item.total_amount)).toLocaleString('fr-FR', {
                                style: 'currency',
                                currency: 'XOF',
                            });
                    
                            render_periode += `
                                <tr>
                                    <td>${item.date_paiement || ''}</td>
                                    <td>${Math.ceil(item.nombre_paiement) || '0'}</td>
                                    <td>${total_amount || '0'}</td>
                                </tr>`;
                            
                            montantTotalPeriode += parseFloat(item.total_amount);
                    
                            // Préparer les données pour le camembert
                            labels.push(`${item.date_paiement || ''}`);
                            dataValues.push(parseFloat(multipleDeMille(item.total_amount)) || 0);
                        });
                    
                        const MontantTotal_P = parseFloat(montantTotalPeriode).toLocaleString('fr-FR', {
                            style: 'currency',
                            currency: 'XOF',
                        });
                    
                        document.getElementById('montant_total_periode').innerHTML = MontantTotal_P;
                    
                        // Préparer les données pour le graphique
                        const categories = labels;
                        const values = dataValues;
                    
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


                
                    

                if(type_stat === "paiement"){
                    /* ######################################################################### */
                    /* ######################################################################### */
                        // 📌 Récupérer dynamiquement les données par mois
                        const labels_mois = Object.keys(stat.par_mois); // Liste des mois
                        const amounts_mois = labels_mois.map(mois => parseFloat(stat.par_mois[mois].montant_total));

                        // 📌 Vérification des données récupérées
                       // console.log("Mois:", labels_mois);
                       // console.log("Montants par Mois:", amounts_mois);

                        
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
                        });
                    
                    

                        if(permissions.par_paiement && permissions.par_paiement_detaille){
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
