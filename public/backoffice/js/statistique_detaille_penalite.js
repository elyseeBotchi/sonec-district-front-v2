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
              
/*                 
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
                        const totalPenaliteEnlevementParMois = {};
                        const totalPenaliteFourrierreParMois = {};
                        const totalPayeParMois = {};


                        //console.log(stat_penalite);
                        // En-têtes
                        dates.forEach(date => {
                            const th = document.createElement("th");
                            th.textContent = date;
                            th.colSpan = 4; // Nombre, Montant, Pénalité, Payé"Pénalité fourrière",
                            headerRow1.appendChild(th);

                            ["Nombre", "Montant", "Pénalité Enlevement","Pénalité fourrière", "Total Payé"].forEach(label => {
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
                } */
                                
                if (permissions.statistique_partenaires_voir_les_statistiques_par_mois) {
                    if (type_stat === "rubrique" && type_sous_stat === "mois") {
                        const tableauStats = document.getElementById("tableauStats");
                        const headerRow1 = document.getElementById("headerRow1");
                        const headerRow2 = document.getElementById("headerRow2");
                        let headerRow3 = document.getElementById("headerRow3");
                        const tableBody  = document.getElementById("tableBody");

                        // Crée le 3e rang d'entêtes si absent
                        if (!headerRow3) {
                        const thead = headerRow1.parentElement;
                        headerRow3 = document.createElement("tr");
                        headerRow3.id = "headerRow3";
                        thead.appendChild(headerRow3);
                        }

                        // Reset
                        headerRow1.innerHTML = "<th rowspan='3'>Rubrique</th>";
                        headerRow2.innerHTML = "";
                        headerRow3.innerHTML = "";
                        tableBody.innerHTML  = "";

                        const dates = Object.keys(stat_penalite.global_par_mois);
                        const rubriques = {};

                        // Totaux par mois
                        const totalParMois        = {};
                        const totalMontantParMois = {};
                        const totalKidParMois     = {}; // penalty_kidnapping_amount
                        const totalPoundParMois   = {}; // penalty_pound_amount
                        const totalPayeParMois    = {};

                        // En-têtes sur 3 niveaux
                        dates.forEach(date => {
                        // Rang 1 : le mois/année regroupe 5 sous-colonnes
                        const thDate = document.createElement("th");
                        thDate.textContent = date;
                        thDate.colSpan = 5; // Nombre, Montant, [Pénalité: Enlèvement + Fourrière], Total Payé
                        headerRow1.appendChild(thDate);

                        // Rang 2 : Nombre, Montant, Pénalité (colspan 2), Total Payé
                        const thNb = document.createElement("th");
                        thNb.textContent = "Nombre";
                        thNb.rowSpan = 2;
                        headerRow2.appendChild(thNb);

                        const thMont = document.createElement("th");
                        thMont.textContent = "Montant de la taxe";
                        thMont.rowSpan = 2;
                        headerRow2.appendChild(thMont);

                        const thPen = document.createElement("th");
                        thPen.textContent = "Pénalité";
                        thPen.colSpan = 2; // Enlèvement + Fourrière
                        headerRow2.appendChild(thPen);

                        const thPay = document.createElement("th");
                        thPay.textContent = "Total Payé";
                        thPay.rowSpan = 2;
                        headerRow2.appendChild(thPay);

                        // Rang 3 : sous Pénalité
                        const thKid = document.createElement("th");
                        thKid.textContent = "Enlèvement";
                        headerRow3.appendChild(thKid);

                        const thPound = document.createElement("th");
                        thPound.textContent = "Fourrière";
                        headerRow3.appendChild(thPound);
                        });

                        // Données
                        dates.forEach(date => {
                        const dataMois = stat_penalite.global_par_mois[date];

                        Object.keys(dataMois).forEach(rubriqueKey => {
                            const item = dataMois[rubriqueKey];
                            const cleanKey = rubriqueKey.trim();

                            if (!rubriques[cleanKey]) {
                            rubriques[cleanKey] = {
                                rubrique_name: cleanKey,
                                data: {},
                                total_lignes: 0,
                                total_montant: 0,
                                total_kid: 0,
                                total_pound: 0,
                                total_paye: 0
                            };
                            }

                            const lignes     = Number(item.total_lignes || 0);
                            const montant    = Number(item.total_montant || 0);
                            const kid        = Number(item.penalty_kidnapping_amount || 0);
                            const pound      = Number(item.penalty_pound_amount || 0);
                            const total_paye = Number(item.amount_total_paid || 0);

                            rubriques[cleanKey].data[date] = { lignes, montant, kid, pound, total_paye };

                            rubriques[cleanKey].total_lignes += lignes;
                            rubriques[cleanKey].total_montant += montant;
                            rubriques[cleanKey].total_kid    += kid;
                            rubriques[cleanKey].total_pound  += pound;
                            rubriques[cleanKey].total_paye   += total_paye;

                            totalParMois[date]        = (totalParMois[date] || 0) + lignes;
                            totalMontantParMois[date] = (totalMontantParMois[date] || 0) + montant;
                            totalKidParMois[date]     = (totalKidParMois[date] || 0) + kid;
                            totalPoundParMois[date]   = (totalPoundParMois[date] || 0) + pound;
                            totalPayeParMois[date]    = (totalPayeParMois[date] || 0) + total_paye;
                        });
                        });

                        // Lignes par rubrique
                        Object.keys(rubriques).forEach(key => {
                        const row = document.createElement("tr");
                        const cellRubrique = document.createElement("td");
                        cellRubrique.textContent = rubriques[key].rubrique_name;
                        row.appendChild(cellRubrique);

                        dates.forEach(date => {
                            const data = rubriques[key].data[date] || { lignes: 0, montant: 0, kid: 0, pound: 0, total_paye: 0 };

                            const cellLignes = document.createElement("td");
                            cellLignes.textContent = data.lignes;
                            cellLignes.style.fontWeight = "bold";
                            row.appendChild(cellLignes);

                            const cellMontant = document.createElement("td");
                            cellMontant.textContent = data.montant.toLocaleString('fr-FR') + " F";
                            cellMontant.style.fontWeight = "bold";
                            row.appendChild(cellMontant);

                            const cellKid = document.createElement("td");
                            cellKid.textContent = data.kid.toLocaleString('fr-FR') + " F";
                            cellKid.style.fontWeight = "bold";
                            row.appendChild(cellKid);

                            const cellPound = document.createElement("td");
                            cellPound.textContent = data.pound.toLocaleString('fr-FR') + " F";
                            cellPound.style.fontWeight = "bold";
                            row.appendChild(cellPound);

                            const cellPaye = document.createElement("td");
                            cellPaye.textContent = data.total_paye.toLocaleString('fr-FR') + " F";
                            cellPaye.style.fontWeight = "bold";
                            row.appendChild(cellPaye);
                        });

                        tableBody.appendChild(row);
                        });

                        // Ligne des totaux
                        const totalRow = document.createElement("tr");
                        const totalCell = document.createElement("td");
                        totalCell.textContent = "TOTAL GÉNÉRAL";
                        totalCell.style.fontWeight = "bold";
                        totalCell.style.fontSize = "16px";
                        totalRow.appendChild(totalCell);

                        dates.forEach(date => {
                        const cellLignes = document.createElement("td");
                        cellLignes.textContent = (totalParMois[date] || 0);
                        cellLignes.style.fontWeight = "bold";
                        cellLignes.style.fontSize = "16px";
                        totalRow.appendChild(cellLignes);

                        const cellMontant = document.createElement("td");
                        cellMontant.textContent = (totalMontantParMois[date] || 0).toLocaleString('fr-FR') + " F";
                        cellMontant.style.fontWeight = "bold";
                        cellMontant.style.fontSize = "16px";
                        totalRow.appendChild(cellMontant);

                        const cellKid = document.createElement("td");
                        cellKid.textContent = (totalKidParMois[date] || 0).toLocaleString('fr-FR') + " F";
                        cellKid.style.fontWeight = "bold";
                        cellKid.style.fontSize = "16px";
                        totalRow.appendChild(cellKid);

                        const cellPound = document.createElement("td");
                        cellPound.textContent = (totalPoundParMois[date] || 0).toLocaleString('fr-FR') + " F";
                        cellPound.style.fontWeight = "bold";
                        cellPound.style.fontSize = "16px";
                        totalRow.appendChild(cellPound);

                        const cellPaye = document.createElement("td");
                        cellPaye.textContent = (totalPayeParMois[date] || 0).toLocaleString('fr-FR') + " F";
                        cellPaye.style.fontWeight = "bold";
                        cellPaye.style.fontSize = "16px";
                        totalRow.appendChild(cellPaye);
                        });

                        tableBody.appendChild(totalRow);
                    }
                }

                if (permissions.statistique_partenaires_voir_les_statistiques_global) {
                    if (type_stat === "rubrique" && type_sous_stat === "tous") {
                        let totalLine = 0;
                        let totalAmount = 0;
                        let totalKid = 0;     // penalty_kidnapping_amount
                        let totalPound = 0;   // penalty_pound_amount
                        let totalPaid = 0;

                        const global_par_rubrique = stat_penalite.global_par_rubrique || [];
                        const tbody = document.getElementById('par_facturation-global');

                        // Sécurise le tableau et l'en-tête
                        if (!tbody) {
                        console.error("Élément avec l'ID 'par_facturation-global' introuvable.");
                        return;
                        }
                        const table = tbody.closest('table');
                        if (!table) {
                        console.error("Impossible de trouver la balise <table> parente.");
                        return;
                        }
                        // (Ré)initialise THEAD avec 2 rangs
                        let thead = table.querySelector('thead');
                        if (!thead) {
                        thead = document.createElement('thead');
                        table.prepend(thead);
                        }
                        thead.innerHTML = `
                        <tr id="globalHeaderRow1">
                            <th rowspan="2">Rubrique</th>
                            <th rowspan="2">Nombre</th>
                            <th rowspan="2">Montant</th>
                            <th colspan="2">Pénalité</th>
                            <th rowspan="2">Total Payé</th>
                        </tr>
                        <tr id="globalHeaderRow2">
                            <th>Enlèvement</th>
                            <th>Fourrière</th>
                        </tr>
                        `;

                        // Reset body
                        tbody.innerHTML = '';

                        if (global_par_rubrique.length === 0) {
                        // Aucune donnée
                        const tr = document.createElement('tr');
                        tr.innerHTML = `<td colspan="6">Aucune donnée disponible</td>`;
                        tbody.appendChild(tr);
                        return;
                        }

                        // (Optionnel) pour tes graphes existants
                        const labels = [];
                        const dataValues = [];

                        // Lignes
                        global_par_rubrique.forEach(item => {
                        const rubriqueName = item.rubrique_name || '';
                        const optionName = item.option_name ? ` - ${item.option_name}` : '';
                        const totalLignes = Number(item.total_lignes || 0);
                        const totalMontant = Number(item.total_montant || 0);
                        const kid = Number(item.penalty_kidnapping_amount || 0);
                        const pound = Number(item.penalty_pound_amount || 0);
                        const paid = Number(item.amount_total_paid || 0);

                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${rubriqueName}${optionName}</td>
                            <td>${totalLignes}</td>
                            <td>${totalMontant.toLocaleString('fr-FR', { style: 'currency', currency: 'XOF' })}</td>
                            <td>${kid.toLocaleString('fr-FR', { style: 'currency', currency: 'XOF' })}</td>
                            <td>${pound.toLocaleString('fr-FR', { style: 'currency', currency: 'XOF' })}</td>
                            <td>${paid.toLocaleString('fr-FR', { style: 'currency', currency: 'XOF' })}</td>
                        `;
                        tbody.appendChild(tr);

                        // Graphes (inchangé)
                        labels.push(`${rubriqueName}${optionName}`);
                        dataValues.push(totalMontant);

                        // Totaux
                        totalLine  += totalLignes;
                        totalAmount+= totalMontant;
                        totalKid   += kid;
                        totalPound += pound;
                        totalPaid  += paid;
                        });

                        // Ligne TOTAL
                        const totalTr = document.createElement('tr');
                        totalTr.innerHTML = `
                        <td><strong>Total</strong></td>
                        <td><strong>${totalLine}</strong></td>
                        <td><strong>${totalAmount.toLocaleString('fr-FR', { style: 'currency', currency: 'XOF' })}</strong></td>
                        <td><strong>${totalKid.toLocaleString('fr-FR', { style: 'currency', currency: 'XOF' })}</strong></td>
                        <td><strong>${totalPound.toLocaleString('fr-FR', { style: 'currency', currency: 'XOF' })}</strong></td>
                        <td><strong>${totalPaid.toLocaleString('fr-FR', { style: 'currency', currency: 'XOF' })}</strong></td>
                        `;
                        tbody.appendChild(totalTr);

                        // 👉 Si tu utilises labels/dataValues pour un graphe, ils sont prêts
                        // updateYourChart(labels, dataValues);
                    }
                }

                if (permissions.statistique_partenaires_voir_les_statistiques_par_periode) {
                    if (type_stat === "periode") {
                        let totalLine = 0;
                        let totalMontant = 0;
                        let totalKid = 0;     // penalty_kidnapping_amount_total
                        let totalPound = 0;   // penalty_pound_amount_total
                        let totalGlobal = 0;

                        const labels = [];
                        const dataValues = [];

                        const tbody = document.getElementById('render_periode');
                        if (!tbody) {
                        console.error("Élément avec l'ID 'render_periode' introuvable dans le DOM.");
                        return;
                        }
                        const table = tbody.closest('table');
                        if (!table) {
                        console.error("Impossible de trouver la balise <table> parente.");
                        return;
                        }

                        // (Ré)initialise THEAD avec 2 rangs
                        let thead = table.querySelector('thead');
                        if (!thead) {
                        thead = document.createElement('thead');
                        table.prepend(thead);
                        }
                        thead.innerHTML = `
                        <tr id="periodeHeaderRow1">
                            <th rowspan="2">Date</th>
                            <th rowspan="2">Nombre</th>
                            <th rowspan="2">Montant de la carte</th>
                            <th colspan="2">Pénalité</th>
                            <th rowspan="2">Total Général</th>
                        </tr>
                        <tr id="periodeHeaderRow2">
                            <th>Enlèvement</th>
                            <th>Fourrière</th>
                        </tr>
                        `;

                        // Reset body
                        tbody.innerHTML = '';

                        const fmtXOF = (n) => Number(n || 0).toLocaleString('fr-FR', { style: 'currency', currency: 'XOF' });

                        const recap = stat_penalite.recap_par_jour || {};
                        // Tri des dates (optionnel)
                        const dates = Object.keys(recap).sort();

                        dates.forEach(date => {
                        const it = recap[date] || {};
                        const total_cartes = Number(it.total_cartes || 0);
                        const montant_cartes_total = Number(it.montant_cartes_total || 0);
                        const penalty_total = Number(it.penalty_total || 0); // = kid + pound
                        const kid = Number(it.penalty_kidnapping_amount_total || 0);
                        const pound = Number(it.penalty_pound_amount_total || 0);
                        const global_total = penalty_total + montant_cartes_total;

                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${date || ''}</td>
                            <td>${total_cartes}</td>
                            <td>${fmtXOF(montant_cartes_total)}</td>
                            <td>${fmtXOF(kid)}</td>
                            <td>${fmtXOF(pound)}</td>
                            <td>${fmtXOF(global_total)}</td>
                        `;
                        tbody.appendChild(tr);

                        totalLine   += total_cartes;
                        totalMontant+= montant_cartes_total;
                        totalKid    += kid;
                        totalPound  += pound;
                        totalGlobal += global_total;

                        // Données pour le graphique (évolution des pénalités)
                        labels.push(date);
                        dataValues.push(penalty_total);
                        });

                        // Montant total des pénalités sur la période (affichage)
                        const montantTotalPeriode = dates.reduce((acc, d) => acc + Number((recap[d] || {}).penalty_total || 0), 0);
                        const eltTotal = document.getElementById('montant_total_periode');
                        if (eltTotal) eltTotal.textContent = fmtXOF(montantTotalPeriode);

                        // Graphique (ApexCharts)
                        const options = {
                        series: [{ name: "Pénalités", data: dataValues }],
                        chart: { height: 350, type: 'bar' },
                        plotOptions: { bar: { borderRadius: 10, columnWidth: '50%' } },
                        dataLabels: { enabled: false },
                        stroke: { width: 0 },
                        grid: { row: { colors: ['#fff', '#f2f2f2'] } },
                        xaxis: { labels: { rotate: -45 }, categories: labels, tickPlacement: 'on' },
                        yaxis: { title: { text: "Pénalités" } },
                        fill: { colors: ['#008FFB'] }
                        };
                        const chart = new ApexCharts(document.querySelector("#periodeChart"), options);
                        chart.render();

                        // (Optionnel) Ligne des totaux au bas du tableau
                        const totalTr = document.createElement('tr');
                        totalTr.innerHTML = `
                        <td><strong>Total</strong></td>
                        <td><strong>${totalLine}</strong></td>
                        <td><strong>${fmtXOF(totalMontant)}</strong></td>
                        <td><strong>${fmtXOF(totalKid)}</strong></td>
                        <td><strong>${fmtXOF(totalPound)}</strong></td>
                        <td><strong>${fmtXOF(totalGlobal)}</strong></td>
                        `;
                        tbody.appendChild(totalTr);

                        // Gestion du clic sur les boutons "Voir Détails" (si présents dans ce tableau)
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