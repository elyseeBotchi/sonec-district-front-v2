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
                    
                   // renderPaiementParMois(globalParMois)
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
                        <td>${percent} %</td>
                        <td>${pen.toLocaleString('fr-FR')} F</td>
                        <td>${gainPartenaire.toLocaleString('fr-FR')} F</td>
                        <td>${gainDistrict.toLocaleString('fr-FR')} F</td>
                        <td>${montant_cartes.toLocaleString('fr-FR')} F</td>
                        <td>${paiement.toLocaleString('fr-FR')} F</td>
                        <td>${cartes}</td>
                    </tr>
                `);

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
                <tr class="table-active">
                    <td><strong>Cumul</strong></td>
                    <td>-</td>
                    <td><strong>${cumul_penalite.toLocaleString('fr-FR')} F</strong></td>
                    <td><strong>${cumul_gain_partenaire.toLocaleString('fr-FR')} F</strong></td>
                    <td><strong>${cumul_gain_district.toLocaleString('fr-FR')} F</strong></td>
                    <td><strong>${cumul_montant_cartes.toLocaleString('fr-FR')} F</strong></td>
                    <td><strong>${cumul_paiement.toLocaleString('fr-FR')} F</strong></td>
                    <td><strong>${cumul_cartes}</strong></td>
                </tr>
            `);

            // ==== CHART vertical par partenaire & mois ====
            const moisLabels = Array.from(moisSet).sort();
            const colors = ['#4caf50', '#2196f3', '#ff9800', '#9c27b0', '#00bcd4', '#795548', '#f44336', '#607d8b'];
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
                backgroundColor: '#000000',
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
                            text: 'Gains par partenaire et District - par mois'
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