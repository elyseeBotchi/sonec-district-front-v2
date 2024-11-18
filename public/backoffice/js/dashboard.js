

$(document).ready(function() {
    findChart();

    function findChart() {
        fetch('/panel/dashboard/statistique')
            .then(response => response.json())
            .then(data => {
                const results = data.data;
                if(canPermission('statistique_total_inscrit')){
                    document.getElementById('total_inscrit').innerHTML = results.total_inscrit;
                    document.getElementById('total_inscrit_evolution').innerHTML = results.total_inscrit_evolution;
                }
                if(canPermission('statistique_inscription_du_jour')){
                    document.getElementById('inscription_jour').innerHTML = results.inscription_jour;
                    document.getElementById('inscription_jour_evolution').innerHTML = results.inscription_jour_evolution;
                }

                if(canPermission('statistique_paiement_du_jour')){
                    document.getElementById('paiement_jour').innerHTML = results.paiement_jour;
                    document.getElementById('paiement_jour_evolution').innerHTML = results.paiement_jour_evolution;
                }

                if(canPermission('statistique_total_paiement')){
                    document.getElementById('total_paiement').innerHTML = results.total_paiement;
                }

                if(canPermission('statistique_inscriptions_validees')){
                    document.getElementById('total_valide').innerHTML = results.total_valide;
                }


                // Supposons que data soit un tableau d'objets avec les propriétés 'categories' et 'values'
                if(canPermission('statistique_graphique_devolution'))
                {
                    var categories = Object.keys(results.chartsData);
                    var values = Object.values(results.chartsData);

                    var options = {
                        series: [{
                            name: "Nombre d'inscrit",
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
                                text: "Nombre d'inscrit",
                            },
                        },
                        fill: {
                            colors: ['#008FFB'], // Replace with your desired color
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();
                }

            })
            .catch(error => {
                console.error('Erreur lors de la récupération des données:', error);
        });
    }

});
