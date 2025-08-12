$(document).ready(function() {
    findStatistique();

    function findStatistique() {
        fetch(`/panel/statistique/penalite/gains/partenaire/data/${Entity_uuid}`, {
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
            const partenaire = data[0] || {};

            var permissions = {
                acteurs_tiers_voir_le_montant_total_par_jour: canPermission('acteurs_tiers_voir_le_montant_total_par_jour'), 
                acteurs_tiers_voir_le_montant_total: canPermission('acteurs_tiers_voir_le_montant_total'), 
                acteurs_tiers_voir_les_statistiques_graphique_par_paiement_mensuel: canPermission('acteurs_tiers_voir_les_statistiques_graphique_par_paiement_mensuel'),
                acteurs_tiers_voir_les_statistiques_graphique_par_paiement_journalier: canPermission('acteurs_tiers_voir_les_statistiques_graphique_par_paiement_journalier'),
                acteurs_tiers_voir_mes_gains_en_tant_que_acteur_tiers: canPermission('acteurs_tiers_voir_mes_gains_en_tant_que_acteur_tiers'),
            };

            if(permissions.acteurs_tiers_voir_mes_gains_en_tant_que_acteur_tiers){
                // === INFOS GLOBALES ===
                const totalPaiement = parseFloat(partenaire.partner_share_total || 0);
                const totalLignes = parseInt(partenaire.total_lignes || 0);

                if(permissions.acteurs_tiers_voir_le_montant_total){
                    document.getElementById("total_paiement").innerText = totalPaiement.toLocaleString('fr-FR') + ' F';
                    document.getElementById("nb_total").innerText = `${totalLignes} pénalités`;
                }

                // === INFOS DU JOUR ===
                const today = new Date().toISOString().split('T')[0]; // format: YYYY-MM-DD
                const globalParJour = partenaire.global_par_jour || {};
                const ligneJour = globalParJour[today];

                let montantJour = 0;
                let lignesJour = 0;

                if (ligneJour) {
                    for (const rubriqueKey in ligneJour) {
                        const r = ligneJour[rubriqueKey];
                        montantJour += parseFloat(r.partner_share || 0);
                        lignesJour += parseInt(r.total_lignes || 0);
                    }
                }

                if(permissions.acteurs_tiers_voir_le_montant_total_par_jour){
                    document.getElementById("montant_total_jour").innerText = montantJour.toLocaleString('fr-FR') + ' F';
                    document.getElementById("nb_total_jour").innerText = `${lignesJour} pénalités`;
                }

                if(permissions.acteurs_tiers_voir_les_statistiques_graphique_par_paiement_mensuel){
                    // === CHART 1 : GAINS PAR MOIS (barres) ===
                    const moisMap = partenaire.global_par_mois || {};
                    const moisLabels = Object.keys(moisMap).sort(); // ex: ['2025-06', '2025-07']
                    const moisData = moisLabels.map(mois => {
                        let total = 0;
                        for (const rubriqueKey in moisMap[mois]) {
                            const r = moisMap[mois][rubriqueKey];
                            total += parseFloat(r.partner_share || 0);
                        }
                        return total;
                    });

                    if (window.chartPaiementMois instanceof Chart) {
                        window.chartPaiementMois.destroy();
                    }

                    
                    const ctxMois = document.getElementById('chartPaiementMois').getContext('2d');
                    window.chartPaiementMois = new Chart(ctxMois, {
                        type: 'bar',
                        data: {
                            labels: moisLabels,
                            datasets: [{
                                label: 'Part du Partenaire',
                                data: moisData,
                                backgroundColor: '#2196f3' // bleu
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: ctx => `${ctx.dataset.label}: ${ctx.raw.toLocaleString('fr-FR')} F`
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Part du partenaire par mois'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: val => val.toLocaleString('fr-FR') + ' F'
                                    }
                                }
                            }
                        }
                    });
                }


                if(permissions.acteurs_tiers_voir_les_statistiques_graphique_par_paiement_journalier){
                    // === CHART 2 : GAINS PAR JOUR (barres) ===
                    const jourMap = partenaire.global_par_jour || {};
                    const jourLabels = Object.keys(jourMap).sort(); // ex: ['2025-07-28', '2025-07-29']
                    const jourData = jourLabels.map(jour => {
                        let total = 0;
                        for (const rubriqueKey in jourMap[jour]) {
                            const r = jourMap[jour][rubriqueKey];
                            total += parseFloat(r.partner_share || 0);
                        }
                        return total;
                    });

                    if (window.chartPaiementJour instanceof Chart) {
                        window.chartPaiementJour.destroy();
                    }

                    const ctxJour = document.getElementById('chartPaiement').getContext('2d');
                    window.chartPaiementJour = new Chart(ctxJour, {
                        type: 'bar', // <- conversion faite ici
                        data: {
                            labels: jourLabels,
                            datasets: [{
                                label: 'Part journaliere',
                                data: jourData,
                                backgroundColor: '#4caf50' // vert
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: ctx => `${ctx.dataset.label}: ${ctx.raw.toLocaleString('fr-FR')} F`
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Part du partenaire par jour'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: val => val.toLocaleString('fr-FR') + ' F'
                                    }
                                }
                            }
                        }
                    });
                }
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des statistiques :', error);
        });
    }
});
