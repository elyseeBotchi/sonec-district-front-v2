$(document).ready(function() {
  //  findStatus('today','all');
    findStatistique();

    const intervalId = setInterval(findStatistique, 20000);
    intervalId

    /**
     * findStatistique() est rappelée automatiquement toutes les ~20s
     * (rafraîchissement des cartes "du jour") : l'état de l'onglet
     * "Par période" (graphique, page affichée, granularité choisie,
     * gestionnaires de clic) doit donc être déclaré ICI, une seule fois,
     * plutôt qu'à l'intérieur de findStatistique() — sinon chaque
     * rafraîchissement recréait un nouveau graphique par-dessus l'ancien
     * et réattachait de nouveaux gestionnaires de clic sur les mêmes
     * boutons (plusieurs clics fantômes cumulés à chaque clic réel).
     */
    let periodeChartInstance = null;
    let periodeListenersBound = false;
    const periodeState = { granularity: 'jour', page: 1, pageSize: 6, dailyRows: [] };

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
                        // Données réelles, jour par jour (aucune donnée inventée :
                        // Mensuel/Annuel/Progression sont des agrégats calculés côté
                        // client à partir de ces mêmes valeurs). Stockées dans
                        // periodeState (persistant, déclaré hors de findStatistique)
                        // pour survivre aux rafraîchissements automatiques.
                        const recap = stat_penalite.recap_par_jour || {};
                        periodeState.dailyRows = Object.keys(recap).sort().map(function (date) {
                            const it = recap[date] || {};
                            const cartes = Number(it.total_cartes || 0);
                            const montantCartes = Number(it.montant_cartes_total || 0);
                            const kid = Number(it.penalty_kidnapping_amount_total || 0);
                            const pound = Number(it.penalty_pound_amount_total || 0);
                            return {
                                key: date,
                                cartes: cartes,
                                montantCartes: montantCartes,
                                kid: kid,
                                pound: pound,
                                total: montantCartes + kid + pound,
                            };
                        });

                        const MOIS_FR = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
                        const fmtXOF = function (n) { return Number(n || 0).toLocaleString('fr-FR', { style: 'currency', currency: 'XOF' }); };

                        function formatLabel(key, granularity) {
                            if (granularity === 'mois') {
                                const parts = key.split('-');
                                return MOIS_FR[parseInt(parts[1], 10) - 1] + ' ' + parts[0];
                            }
                            return key;
                        }

                        function aggregate(rows, granularity) {
                            if (granularity === 'jour') {
                                return rows.slice();
                            }
                            const buckets = {};
                            const order = [];
                            rows.forEach(function (row) {
                                const bucketKey = granularity === 'mois' ? row.key.slice(0, 7) : row.key.slice(0, 4);
                                if (!buckets[bucketKey]) {
                                    buckets[bucketKey] = { key: bucketKey, cartes: 0, montantCartes: 0, kid: 0, pound: 0, total: 0 };
                                    order.push(bucketKey);
                                }
                                buckets[bucketKey].cartes += row.cartes;
                                buckets[bucketKey].montantCartes += row.montantCartes;
                                buckets[bucketKey].kid += row.kid;
                                buckets[bucketKey].pound += row.pound;
                                buckets[bucketKey].total += row.total;
                            });
                            order.sort();
                            return order.map(function (k) { return buckets[k]; });
                        }

                        function renderChart(rows) {
                            const chartContainer = document.querySelector("#periodeChart");
                            if (!chartContainer) {
                                return;
                            }
                            const categories = rows.map(function (r) { return formatLabel(r.key, periodeState.granularity); });
                            const values = rows.map(function (r) { return r.kid + r.pound; });

                            const options = {
                                series: [{ name: "Pénalités", data: values }],
                                chart: { height: 350, type: 'bar', toolbar: { show: false } },
                                plotOptions: { bar: { borderRadius: 6, columnWidth: '50%' } },
                                dataLabels: { enabled: false },
                                stroke: { width: 0 },
                                grid: { borderColor: '#ececf1' },
                                xaxis: { categories: categories, labels: { rotate: -45 } },
                                yaxis: { title: { text: "Pénalités (FCFA)" } },
                                fill: { colors: ['#ff7a1a'] },
                                colors: ['#ff7a1a'],
                            };

                            // periodeChartInstance est persistant : chaque
                            // rafraîchissement détruit bien l'instance précédente
                            // au lieu d'en empiler une nouvelle par-dessus.
                            if (periodeChartInstance) {
                                periodeChartInstance.destroy();
                            }
                            periodeChartInstance = new ApexCharts(chartContainer, options);
                            periodeChartInstance.render();
                        }

                        function trendBadge(rows, index) {
                            const previous = rows[index - 1];
                            if (!previous || previous.total <= 0) {
                                return '<span class="v2-trend v2-trend--flat">—</span>';
                            }
                            const pct = ((rows[index].total - previous.total) / previous.total) * 100;
                            if (Math.abs(pct) < 0.1) {
                                return '<span class="v2-trend v2-trend--flat">0%</span>';
                            }
                            const up = pct > 0;
                            const arrow = up
                                ? '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>'
                                : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg>';
                            return '<span class="v2-trend ' + (up ? 'v2-trend--up' : 'v2-trend--down') + '">' + arrow + Math.abs(pct).toFixed(1) + '%</span>';
                        }

                        function renderTable(rows) {
                            const tbody = document.getElementById('render_periode');
                            if (!tbody) {
                                console.error("Élément avec l'ID 'render_periode' introuvable dans le DOM.");
                                return;
                            }

                            if (!rows.length) {
                                tbody.innerHTML = "<tr><td colspan='7' class='is-muted' style='text-align:center;padding:28px 12px;'>Aucune donnée disponible</td></tr>";
                                return;
                            }

                            const visibleCount = Math.min(rows.length, periodeState.page * periodeState.pageSize);
                            let html = '';
                            for (let i = 0; i < visibleCount; i++) {
                                const row = rows[i];
                                html += '<tr>' +
                                    '<td><strong>' + formatLabel(row.key, periodeState.granularity) + '</strong></td>' +
                                    '<td class="is-muted">' + row.cartes + '</td>' +
                                    '<td class="is-numeric">' + fmtXOF(row.montantCartes) + '</td>' +
                                    '<td class="is-numeric">' + fmtXOF(row.kid) + '</td>' +
                                    '<td class="is-numeric">' + fmtXOF(row.pound) + '</td>' +
                                    '<td class="is-numeric"><strong>' + fmtXOF(row.total) + '</strong></td>' +
                                    '<td class="is-numeric">' + trendBadge(rows, i) + '</td>' +
                                    '</tr>';
                            }
                            tbody.innerHTML = html;

                            const moreBtn = document.getElementById('v2-periode-more');
                            const paginationLabel = document.getElementById('v2-periode-pagination');
                            const totalPages = Math.max(1, Math.ceil(rows.length / periodeState.pageSize));

                            if (moreBtn) {
                                moreBtn.style.display = periodeState.page >= totalPages ? 'none' : '';
                            }
                            if (paginationLabel) {
                                paginationLabel.textContent = 'Page ' + Math.min(periodeState.page, totalPages) + ' sur ' + totalPages;
                            }
                        }

                        function renderRange(rows) {
                            const rangeLabel = document.getElementById('v2-periode-range');
                            if (!rangeLabel || !rows.length) {
                                return;
                            }
                            const first = formatLabel(rows[0].key, periodeState.granularity);
                            const last = formatLabel(rows[rows.length - 1].key, periodeState.granularity);
                            rangeLabel.textContent = (first === last) ? first : (first + ' — ' + last);
                        }

                        function refreshPeriode() {
                            const rows = aggregate(periodeState.dailyRows, periodeState.granularity);
                            renderChart(rows);
                            renderTable(rows);
                            renderRange(rows);
                        }

                        // N'attacher les gestionnaires de clic qu'une seule fois
                        // (voir commentaire équivalent dans statistique_detaille.js).
                        if (!periodeListenersBound) {
                            periodeListenersBound = true;

                            document.querySelectorAll('#v2-periode-tabs .v2-tabs__item').forEach(function (tab) {
                                tab.addEventListener('click', function () {
                                    document.querySelectorAll('#v2-periode-tabs .v2-tabs__item').forEach(function (t) {
                                        t.classList.remove('is-active');
                                    });
                                    tab.classList.add('is-active');
                                    periodeState.granularity = tab.getAttribute('data-granularity');
                                    periodeState.page = 1;
                                    refreshPeriode();
                                });
                            });

                            const moreBtnEl = document.getElementById('v2-periode-more');
                            if (moreBtnEl) {
                                moreBtnEl.addEventListener('click', function () {
                                    periodeState.page += 1;
                                    refreshPeriode();
                                });
                            }

                            const exportBtn = document.getElementById('v2-periode-export');
                            if (exportBtn) {
                                exportBtn.addEventListener('click', function () {
                                    window.print();
                                });
                            }
                        }

                        refreshPeriode();
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