$(document).ready(function() {
  //  findStatus('today','all');
    findStatistique();

    const intervalId = setInterval(findStatistique, 20000);
    intervalId
    
    
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
                // Données pour le camembert
                const labels = [];
                const dataValues = [];
              
                
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

                        Object.keys(stat_penalite.recap_par_jour).forEach(date => {
                            const { penalty_total, total_cartes, montant_cartes_total, district_share_total, partner_share_total } = stat_penalite.recap_par_jour[date]; // Extraction des valeurs

                            const penalty_total_amount = parseFloat(penalty_total || 0).toLocaleString('fr-FR', {
                                style: 'currency',
                                currency: 'XOF',
                            });

                            const montant_cartes_total_amount = parseFloat(montant_cartes_total || 0).toLocaleString('fr-FR', {
                                style: 'currency',
                                currency: 'XOF',
                            }); 

                            const global_total_amount = parseFloat(penalty_total + montant_cartes_total || 0).toLocaleString('fr-FR', {
                                style: 'currency',
                                currency: 'XOF',
                            });

                            render_periode += `
                                <tr>
                                    <td>${date || ''}</td>
                                    <td>${total_cartes || '0'}</td>
                                    <td>${penalty_total_amount || '0'}</td>
                                    <td>${montant_cartes_total_amount || '0'}</td>                                   
                                    <td>${global_total_amount || '0'}</td>                                   
                                </tr>`;
                            
                            montantTotalPeriode += parseFloat(penalty_total_amount || 0);

                            // Préparer les données pour le camembert
                            labels.push(date);
                            dataValues.push(parseFloat(penalty_total_amount || 0));
                        });

                    
                        const MontantTotal_P = parseFloat(montantTotalPeriode).toLocaleString('fr-FR', {
                            style: 'currency',
                            currency: 'XOF',
                        });
                    
                        document.getElementById('montant_total_periode').innerHTML = MontantTotal_P;
                    
                        
                        const categories = Object.keys(stat_penalite.recap_par_jour);
                        const values = categories.map(jour => parseFloat(stat_penalite.recap_par_jour[jour].penalty_total));

                    
                        var options = {
                            series: [{
                                name: "Pénalités",
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
                                    text: "Pénalités",
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