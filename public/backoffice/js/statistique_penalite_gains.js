$(document).ready(function() {
  //  findStatus('today','all');
    findStatistique();
    // Exécuter findStatistique toutes les 60 000 millisecondes (1 minute)
   // setInterval(findStatistique, 20000);
    //setInterval(findStatus('today','all'), 25000);

    //const intervalId = setInterval(findStatistique, 20000);
    //intervalId
    //setInterval(() => findStatistique(), 20000)

    function findStatistique() {
        fetch(`/panel/statistique/penalite/partenaire/${Entity_uuid}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        })
        .then(response => {
            if (!response.ok) throw new Error('Erreur lors de la récupération des statistiques.');
            return response.json();
        })
        .then(data => {
            const partenaires = data.data; console.log(partenaires)
            const tbody = document.querySelector('table tbody');
            tbody.innerHTML = "";

            let cumul_penalite = 0;
            let cumul_gain_partenaire = 0;
            let cumul_gain_district = 0;
            let cumul_montant_cartes = 0;
            let cumul_paiement = 0;
            let cumul_cartes = 0;

            const moisSet = new Set();
            const datasetMap = new Map(); // { partenaire => { mois => montant } }
            const districtMap = {}; // { mois => montant }

            partenaires.forEach(partner => {
                const pen = parseFloat(partner.penalty_amount_total || 0);
                const percent = parseFloat(partner.partner_percent || 0);
                const gainPartenaire = (pen * percent) / 100;
                const gainDistrict = pen - gainPartenaire;
                const montant_cartes = parseFloat(partner.total_montant || 0);
                const paiement = parseFloat(partner.amount_total_paid || 0);
                const cartes = parseInt(partner.total_lignes || 0);

                cumul_penalite += pen;
                cumul_gain_partenaire += gainPartenaire;
                cumul_gain_district += gainDistrict;
                cumul_montant_cartes += montant_cartes;
                cumul_paiement += paiement;
                cumul_cartes += cartes;

                // Affichage tableau
                tbody.insertAdjacentHTML('beforeend', `
                    <tr>
                        <td>${partner.partner_name}</td>
                        <td>${cartes}</td>
                        <td class="is-numeric">${montant_cartes.toLocaleString('fr-FR')} F</td>
                        <td class="is-numeric">${pen.toLocaleString('fr-FR')} F</td>
                        <td class="is-numeric">${percent} %</td>
                        <td class="is-numeric">${gainPartenaire.toLocaleString('fr-FR')} F</td>
                        <td class="is-numeric">${gainDistrict.toLocaleString('fr-FR')} F</td>

                    </tr>
                `); //<td>${paiement.toLocaleString('fr-FR')} F</td>

                // Graphe par mois
                const moisData = partner.global_par_mois || {};
                const gainsParMois = {};

                for (const mois in moisData) {
                    moisSet.add(mois);

                    let montantPenaliteMois = 0;
                    Object.values(moisData[mois]).forEach(r => {
                        montantPenaliteMois += parseFloat(r.penalty_amount_total || 0);
                    });

                    const gainP = montantPenaliteMois * percent / 100;
                    const gainD = montantPenaliteMois - gainP;

                    gainsParMois[mois] = gainP;
                    districtMap[mois] = (districtMap[mois] || 0) + gainD;
                }

                datasetMap.set(partner.partner_name, gainsParMois);
            });

            // Ligne cumul
            tbody.insertAdjacentHTML('beforeend', `
                <tr class="is-total">
                    <td><strong>Cumul</strong></td>
                    <td><strong>${cumul_cartes}</strong></td>
                    <td class="is-numeric"><strong>${cumul_montant_cartes.toLocaleString('fr-FR')} F</strong></td>
                    <td class="is-numeric"><strong>${cumul_penalite.toLocaleString('fr-FR')} F</strong></td>
                    <td class="is-numeric">-</td>
                    <td class="is-numeric"><strong>${cumul_gain_partenaire.toLocaleString('fr-FR')} F</strong></td>
                    <td class="is-numeric"><strong>${cumul_gain_district.toLocaleString('fr-FR')} F</strong></td>

                </tr>
            `); //<td><strong>${cumul_paiement.toLocaleString('fr-FR')} F</strong></td>

            // ==== CHART vertical par partenaire & mois ====
            const moisLabels = Array.from(moisSet).sort();
            const colors = [ '#2196f3', '#4caf50','#ff9800', '#9c27b0', '#00bcd4', '#795548', '#f44336', '#607d8b'];
            let colorIndex = 0;

            const datasets = [];

            for (const [label, moisData] of datasetMap.entries()) {
                const data = moisLabels.map(mois => moisData[mois] || 0);
                datasets.push({
                    label: label,
                    data: data,
                    backgroundColor: colors[colorIndex % colors.length],
                    borderWidth: 1
                });
                colorIndex++;
            }

            // District
            datasets.push({
                label: 'District',
                data: moisLabels.map(mois => districtMap[mois] || 0),
                backgroundColor: '#da7216',
                borderWidth: 1
            });

            const ctx = document.getElementById('chartPaiementMois').getContext('2d');
            if (window.chartPaiementMois instanceof Chart) {
                window.chartPaiementMois.destroy();
            }

            window.chartPaiementMois = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: moisLabels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: ctx => `${ctx.dataset.label}: ${ctx.raw.toLocaleString('fr-FR')} F`
                            }
                        },
                        title: {
                            display: true,
                            text: 'Parts partenaire et District - par mois'
                        },
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Mois'
                            }
                        },
                        y: {
                            stacked: false,
                            beginAtZero: true,
                            ticks: {
                                callback: value => value.toLocaleString('fr-FR') + ' F'
                            },
                            title: {
                                display: true,
                                text: 'Montant en FCFA'
                            }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des statistiques :', error);
        });
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