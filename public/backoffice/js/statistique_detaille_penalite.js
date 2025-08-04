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
                const stat_penalite = results.penalty_stats;

                //console.log(stat_penalite);
                //)
      
                let total_paiement_cheque = stat.total_paiement_cheque || 0;
                let nb_total_cheque = stat.total_cheque || 0;
                let total_carte_valide = stat.total_carte_valide || 0;
                let cumul_paiements = total_paiement_cheque + (stat_mobile.montant_global || 0);

               var permissions = {
             
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
                    const cumul_paiements_jour = parseFloat((stat.total_paiement_cheque_journalier ?? 0)+(stat_mobile.par_jour?.[today]?.montant_total ?? 0)).toLocaleString('fr-FR', {
                        style: 'currency',
                        currency: 'XOF',
                    });

                    document.getElementById('cumul_paiements_jour').innerHTML = cumul_paiements_jour || 0;
                    document.getElementById('cumul_nbre_paiements_jour').innerHTML = (stat.total_carte_valide_journalier || 0)+stat_mobile.par_jour?.[today]?.nombre_lignes || 0;

                    
                } 

                              
                if(permissions.statistique_partenaires_voir_le_montant_total){
                    const total_paiement_chequeF = parseFloat(total_paiement_cheque).toLocaleString('fr-FR', {
                        style: 'currency',
                        currency: 'XOF',
                    });

                    //alert(total_paiement_cheque)

                    document.getElementById('total_paiement_cheque').innerHTML = total_paiement_chequeF || '';
                    document.getElementById('nb_total_cheque').innerHTML = nb_total_cheque || '';
                    document.getElementById('total_carte_valide_cheque').innerHTML = total_carte_valide || '';


                    
                    const cumul_paiement_chequeF = parseFloat(cumul_paiements).toLocaleString('fr-FR', {
                        style: 'currency',
                        currency: 'XOF',
                    });

                    document.getElementById('cumul_paiements').innerHTML = cumul_paiement_chequeF || '';
                    document.getElementById('cumul_carte_valide').innerHTML = (stat_mobile.nombre_lignes_global || 0) + total_carte_valide;
                    
                } 

                /* ######################################################### */
              
                if(permissions.statistique_partenaires_voir_le_montant_total){
                    const total_paiement = parseFloat(stat_mobile.montant_global).toLocaleString('fr-FR', {
                        style: 'currency',
                        currency: 'XOF',
                    });

                    document.getElementById('total_paiement').innerHTML = total_paiement || '';
                    document.getElementById('nb_total').innerHTML = stat_mobile.nombre_lignes_global || '';
                }  
                
                if(permissions.statistique_partenaires_voir_les_statistiques_graphique_par_paiement_mensuel){
                    /* ######################################################################### */
                    /* ######################################################################### */

                    const labels_mois = Object.keys(stat_penalite.par_mois); // Liste des mois

                    const amounts_mois_cheque = labels_mois.map(mois => parseFloat(stat.par_mois[mois].montant_total));
                    const amounts_mois_mobile = labels_mois.map(mois => 
                        stat_mobile.par_mois[mois] ? parseFloat(stat_mobile.par_mois[mois].montant_total) : 0
                    );
                    const amounts_mois_total = labels_mois.map((mois, index) => amounts_mois_cheque[index] + amounts_mois_mobile[index]);
                    
                    // 📌 Vérification des données récupérées
                    console.log("Mois:", labels_mois);
                    console.log("Montants Chèques:", amounts_mois_cheque);
                    console.log("Montants Mobile:", amounts_mois_mobile);
                    console.log("Montants Cumulés:", amounts_mois_total);
                    
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
                    if (type_stat === "rubrique" && type_sous_stat === "mois") {
                        const tableauStats = document.getElementById("tableauStats");
                        const headerRow1 = document.getElementById("headerRow1");
                        const headerRow2 = document.getElementById("headerRow2");
                        const tableBody = document.getElementById("tableBody");

                        // Réinitialiser le tableau
                        headerRow1.innerHTML = "<th rowspan='2'>Rubrique</th>";
                        headerRow2.innerHTML = "";
                        tableBody.innerHTML = "";

                        const dates = Object.keys(stat_penalite.global_par_mois);
                        const rubriques = {};
                        const totalParMois = {};
                        const totalMontantParMois = {};
                        const totalPenaliteParMois = {};
                        const totalPayeParMois = {};

                        // En-têtes
                        dates.forEach(date => {
                            const th = document.createElement("th");
                            th.textContent = date;
                            th.colSpan = 4; // Nombre, Montant, Pénalité, Payé
                            headerRow1.appendChild(th);

                            ["Nombre", "Montant", "Pénalité", "Total Payé"].forEach(label => {
                                const thSub = document.createElement("th");
                                thSub.textContent = label;
                                headerRow2.appendChild(thSub);
                            });
                        });

                        // Traitement des données
                        dates.forEach(date => {
                            const dataMois = stat_penalite.global_par_mois[date];

                            Object.keys(dataMois).forEach(rubriqueKey => {
                                const item = dataMois[rubriqueKey];
                                const cleanKey = rubriqueKey.trim();
                               // console.log(item)
                                //let cleanKey = `${item.rubrique_name}  ${item.option_name || ''}`;

                                if (!rubriques[cleanKey]) {
                                    rubriques[cleanKey] = {
                                        rubrique_name: cleanKey,
                                        data: {},
                                        total_lignes: 0,
                                        total_montant: 0,
                                        penalite: 0,
                                        total_paye: 0
                                    };
                                }

                                rubriques[cleanKey].data[date] = {
                                    lignes: item.total_lignes || 0,
                                    montant: item.total_montant || 0,
                                    penalite: item.penalty_amount_total || 0,
                                    total_paye: item.amount_total_paid || 0
                                };

                                rubriques[cleanKey].total_lignes += item.total_lignes || 0;
                                rubriques[cleanKey].total_montant += item.total_montant || 0;
                                rubriques[cleanKey].penalite += item.penalty_amount_total || 0;
                                rubriques[cleanKey].total_paye += item.amount_total_paid || 0;

                                totalParMois[date] = (totalParMois[date] || 0) + (item.total_lignes || 0);
                                totalMontantParMois[date] = (totalMontantParMois[date] || 0) + (item.total_montant || 0);
                                totalPenaliteParMois[date] = (totalPenaliteParMois[date] || 0) + (item.penalty_amount_total || 0);
                                totalPayeParMois[date] = (totalPayeParMois[date] || 0) + (item.amount_total_paid || 0);
                            });
                        });

                        // Construction des lignes du tableau
                        Object.keys(rubriques).forEach(key => {
                            const row = document.createElement("tr");
                            const cellRubrique = document.createElement("td");
                            cellRubrique.textContent = rubriques[key].rubrique_name;
                            row.appendChild(cellRubrique);

                            dates.forEach(date => {
                                const data = rubriques[key].data[date] || { lignes: 0, montant: 0, penalite: 0, total_paye: 0 };

                                const cellLignes = document.createElement("td");
                                cellLignes.textContent = data.lignes;
                                cellLignes.style.fontWeight = "bold";
                                row.appendChild(cellLignes);

                                const cellMontant = document.createElement("td");
                                cellMontant.textContent = data.montant.toLocaleString() + " F";
                                cellMontant.style.fontWeight = "bold";
                                row.appendChild(cellMontant);

                                const cellPenalite = document.createElement("td");
                                cellPenalite.textContent = data.penalite.toLocaleString() + " F";
                                cellPenalite.style.fontWeight = "bold";
                                row.appendChild(cellPenalite);

                                const cellPaye = document.createElement("td");
                                cellPaye.textContent = data.total_paye.toLocaleString() + " F";
                                cellPaye.style.fontWeight = "bold";
                                row.appendChild(cellPaye);
                            });

                            tableBody.appendChild(row);
                        });

                        // Ligne des totaux
                        const totalRow = document.createElement("tr");
                        const totalCell = document.createElement("td");
                        totalCell.textContent = "TOTAL GENERAL";
                        totalCell.style.fontWeight = "bold";
                        totalCell.style.fontSize = "16px";
                        totalRow.appendChild(totalCell);

                        dates.forEach(date => {
                            const cellLignes = document.createElement("td");
                            cellLignes.textContent = totalParMois[date] || 0;
                            cellLignes.style.fontWeight = "bold";
                            cellLignes.style.fontSize = "16px";
                            totalRow.appendChild(cellLignes);

                            const cellMontant = document.createElement("td");
                            cellMontant.textContent = (totalMontantParMois[date] || 0).toLocaleString() + " F";
                            cellMontant.style.fontWeight = "bold";
                            cellMontant.style.fontSize = "16px";
                            totalRow.appendChild(cellMontant);

                            const cellPenalite = document.createElement("td");
                            cellPenalite.textContent = (totalPenaliteParMois[date] || 0).toLocaleString() + " F";
                            cellPenalite.style.fontWeight = "bold";
                            cellPenalite.style.fontSize = "16px";
                            totalRow.appendChild(cellPenalite);

                            const cellPaye = document.createElement("td");
                            cellPaye.textContent = (totalPayeParMois[date] || 0).toLocaleString() + " F";
                            cellPaye.style.fontWeight = "bold";
                            cellPaye.style.fontSize = "16px";
                            totalRow.appendChild(cellPaye);
                        });

                        tableBody.appendChild(totalRow);
                    }
                }
                
                if(permissions.statistique_partenaires_voir_les_statistiques_global){
                    if (type_stat === "rubrique" && type_sous_stat === "tous") {
                        let totalLine = 0;
                        let totalAmount = 0;
                        let totalPenalite = 0;
                        let par_facturation = "";

                        const global_par_rubrique = stat_penalite.global_par_rubrique;
                        console.log(global_par_rubrique);

                        if (global_par_rubrique.length > 0) {
                            global_par_rubrique.forEach(item => {
                                const rubriqueName = item.rubrique_name || '';
                                const optionName = item.option_name ? ` - ${item.option_name}` : '';
                                const totalLignes = parseInt(item.total_lignes || '0', 10);
                                const totalMontant = parseFloat(item.total_montant || 0);
                                const penaliteMontant = parseFloat(item.penalty_amount_total || 0);

                                const totalAmountFormatted = totalMontant.toLocaleString('fr-FR', {
                                    style: 'currency',
                                    currency: 'XOF',
                                });

                                const penaliteFormatted = penaliteMontant.toLocaleString('fr-FR', {
                                    style: 'currency',
                                    currency: 'XOF',
                                });

                                par_facturation += `
                                    <tr>
                                        <td>${rubriqueName}${optionName}</td>
                                        <td>${totalLignes}</td>
                                        <td>${totalAmountFormatted}</td>
                                        <td>${penaliteFormatted}</td>
                                    </tr>`;

                                labels.push(`${rubriqueName}${optionName}`);
                                dataValues.push(totalMontant);

                                totalLine += totalLignes;
                                totalAmount += totalMontant;
                                totalPenalite += penaliteMontant;
                            });

                            par_facturation += `
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td><strong>${totalLine}</strong></td>
                                    <td><strong>${totalAmount.toLocaleString('fr-FR', { style: 'currency', currency: 'XOF' })}</strong></td>
                                    <td><strong>${totalPenalite.toLocaleString('fr-FR', { style: 'currency', currency: 'XOF' })}</strong></td>
                                </tr>`;
                        } else {
                            par_facturation = "<tr><td colspan='4'>Aucune donnée disponible</td></tr>";
                        }

                        const tableBody = document.getElementById('par_facturation-global');
                        if (tableBody) {
                            tableBody.innerHTML = par_facturation;
                        } else {
                            console.error("Élément avec l'ID 'par_facturation-global' introuvable dans le DOM.");
                        }
                    }
                }  


                if(permissions.statistique_partenaires_voir_les_statistiques_par_periode){
                    if(type_stat === "periode"){
                        let render_periode = '';
                        let montantTotalPeriode = 0;
                        const labels = [];
                        const dataValues = [];

                        Object.keys(stat_penalite.global_par_jour).forEach(date => {
                            const { nombre_lignes, penalty_amount_total, amount_total_paid } = stat_penalite.global_par_jour[date]; // Extraction des valeurs

                            const total_amount = parseFloat(penalty_amount_total || 0).toLocaleString('fr-FR', {
                                style: 'currency',
                                currency: 'XOF',
                            });

                            const global_total_paid = parseFloat(amount_total_paid || 0).toLocaleString('fr-FR', {
                                style: 'currency',
                                currency: 'XOF',
                            }); 
                            render_periode += `
                                <tr>
                                    <td>${date || ''}</td>
                                    <td>${nombre_lignes || '0'}</td>
                                    <td>${total_amount || '0'}</td>                                   
                                    <td>${global_total_paid || '0'}</td>                                   
                                </tr>`;
                            
                            montantTotalPeriode += parseFloat(penalty_amount_total || 0);

                            // Préparer les données pour le camembert
                            labels.push(date);
                            dataValues.push(parseFloat(penalty_amount_total || 0));
                        });

                    
                        const MontantTotal_P = parseFloat(montantTotalPeriode).toLocaleString('fr-FR', {
                            style: 'currency',
                            currency: 'XOF',
                        });
                    
                        document.getElementById('montant_total_periode').innerHTML = MontantTotal_P;
                    
                        
                        const categories = Object.keys(stat_penalite.global_par_jour);
                        const values = categories.map(jour => parseFloat(stat_penalite.global_par_jour[jour].penalty_amount_total));

                    
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