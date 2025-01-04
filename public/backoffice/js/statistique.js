

$(document).ready(function() {
    findStatus('today','all');
    findStatistique();


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
            par_operateur: canPermission('statistique_voir_les_statistiques_par_operateur'),
            par_rubrique: canPermission('statistique_voir_les_statistiques_par_rubrique'),
            par_periode: canPermission('statistique_voir_les_statistiques_par_periode'),
            par_rdv: canPermission('statistique_voir_les_statistiques_par_rendez_vous'),
        };
        
        if(permissions.par_paiement){
            if(type_stat ==="paiement"){
            
                document.getElementById('titre_liste').innerHTML= " <i class='fa fa-spinner fa-spin'></i> LISTE DES PAIEMENTS"+ libelle_status+ " "+libelle_paymode+" EN COURS DE CHARGEMENT ..."

                fetch(`/panel/statistique/findStatus/data/${status}/${paymode}/${Entity_uuid}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    // 'Authorization':'Bearer '+swagger_API_KEY
                    },
                // body: JSON.stringify({ admin_uuid : admin_uuid,status:status })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Une erreur est survenue');
                    }
                    return response.json();
                })
                .then(data => {
                    const results = data.data;
                    console.log(data);

                    
                    var categories = Object.keys(data.chartsData);
                    var values = Object.values(data.chartsData);

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
                            colors: ['#008FFB'], // Replace with your desired color
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#chartPaiement"), options);
                    chart.render();


                    document.getElementById('titre_liste').innerHTML= "LISTE DES PAIEMENTS " + libelle_status + " "+libelle_paymode

                    // Vérifie si le tableau a déjà été initialisé
                    if ($.fn.DataTable && $.fn.DataTable.isDataTable('#datatable-custom')) {
                        // Détruire l'instance existante
                        $('#datatable-custom').DataTable().destroy();
                    }

                    $('#datatable-custom').DataTable({
                        language: {
                            url: '//cdn.datatables.net/plug-ins/2.0.2/i18n/fr-FR.json',
                        },
                        data: results,
                        columns: [
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
                                data: 'paiement_state',
                                render: function(data, type, row) {
                                    if(data ==="paid"){
                                        return `
                                        <span class="badge bg-success-subtle text-success"> Payé</span>
                                    `;
                                    }
                                    else if(data ==="fail"){
                                        return `
                                        <span class="badge bg-danger-subtle text-danger"> Rejété </span>
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

                    // Ajouter un événement pour le bouton Détail
                    $('#datatable-custom').on('click', '.btn-detail', function() {
                        const uuid = $(this).data('uuid');
                        console.log(uuid)
                        fetchCandidatDetail(uuid);
                    });
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
                //console.log("Résultats reçus :", results);
                
                const rendezVous = results.rdv;
               // console.log("Résultats reçus :", results.rdv);

                const ligne_facturations = results.ligne_facturations || [];
                let par_facturation = ""; // Utilisez let pour permettre la concaténation
    
                const ligne_render_periode = results.ligne_render_periode || [];
                let render_periode ="";
                 
                
               var permissions = {
                montant_total_jour: canPermission('statistique_voir_le_montant_total_par_jour'),
                total_paiement: canPermission('statistique_voir_le_total_des_paiements'),
                par_paiement: canPermission('statistique_voir_les_statistiques_par_paiement'),
                par_operateur: canPermission('statistique_voir_les_statistiques_par_operateur'),
                par_rubrique: canPermission('statistique_voir_les_statistiques_par_rubrique'),
                par_periode: canPermission('statistique_voir_les_statistiques_par_periode'),
                par_rdv: canPermission('statistique_voir_les_statistiques_par_rendez_vous'),
            };
            
            if(permissions.montant_total_jour){
                const montant_total_jour = parseFloat(results.montant_total_jour).toLocaleString('fr-FR', {
                    style: 'currency',
                    currency: 'XOF',
                });

                document.getElementById('montant_total_jour').innerHTML = montant_total_jour || '';
                document.getElementById('nb_total_jour').innerHTML = results.nb_total_jour || 0;
            }  
     
                        
            if(permissions.total_paiement){
                const total_paiement = parseFloat(results.total_paiement).toLocaleString('fr-FR', {
                    style: 'currency',
                    currency: 'XOF',
                });

                document.getElementById('total_paiement').innerHTML = total_paiement || '';
                document.getElementById('nb_total').innerHTML = results.total_paiement_nbre || '';
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
                            
                            var nb_total = results.tresor_nb + results.moov_nb + results.mtn_nb + results.orange_nb;
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
                                        <td>${item.line_count || '0'}</td>
                                        <td>${total_amount || '0'}</td>
                                    </tr>`;
                                
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
                    const totalPrevu = validRendezVous.map(item => item.total_prevu || 0); // Par défaut 0 si non défini
                    const totalRecu = validRendezVous.map(item => item.total_recu || 0); // Par défaut 0 si non défini
                    
                    // Graphique ApexCharts
                    console.log(categoriesrdv);
                    const options = {
                        series: [
                            { name: "Rendez-vous prévus", data: totalPrevu },
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
                        yaxis: { title: { text: 'Nombre de rendez-vous' } },
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
                        },
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
                            const total_amount = parseFloat(item.total_amount).toLocaleString('fr-FR', {
                                style: 'currency',
                                currency: 'XOF',
                            });
                    
                            render_periode += `
                                <tr>
                                    <td>${item.date_paiement || ''}</td>
                                    <td>${item.nombre_paiement || '0'}</td>
                                    <td>${total_amount || '0'}</td>
                                </tr>`;
                            
                            montantTotalPeriode += parseFloat(item.total_amount);
                    
                            // Préparer les données pour le camembert
                            labels.push(`${item.date_paiement || ''}`);
                            dataValues.push(parseFloat(item.total_amount) || 0);
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
