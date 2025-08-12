

$(document).ready(function() {
  //  findStatus('today','all');
    findStatistique();

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

               var permissions = {
                validation_pending: canPermission('tableau_de_bord_voir_les_validations_en_attentes'),
                nb_total_jour: canPermission('tableau_de_bord_voir_les_paiements_du_jour'),
                graphe_devolution: canPermission('tableau_de_bord_voir_le_graphe_devolution'),
                total_rdv_jour: canPermission('tableau_de_bord_voir_mes_statistiques_de_validation'),
            };
            
            if(permissions.validation_pending){
                document.getElementById('validation_pending').innerHTML = results.validation_pending || 0;
            }  

            if(permissions.nb_total_jour){
                document.getElementById('nb_total_jour').innerHTML = results.nb_total_jour || 0
            }

            if(permissions.total_rdv_jour){
                document.getElementById('rdv_recu_jour').innerHTML = results.mes_rdv_jour || 0
            }

            if(permissions.total_rdv_jour){
                document.getElementById('total_rdv_jour').innerHTML = results.total_rdv_jour || 0
            }


        })
            .catch(error => {
                console.error('Erreur lors de la récupération des statistiques :', error);
                /* Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Impossible de récupérer les statistiques. Veuillez réessayer.',
                }); */
            });
    }
    
    findAll();

    function findAll() {
      //  alert(Entity_uuid)
        fetch(`/panel/services/taxes/rdv/today/activite/${Entity_uuid}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue lors de la récupération des données');
                }
                return response.json();
            })
            .then(data => { 
                //console.log(data)

                
               var permissions = {
                activite_du_jour: canPermission('tableau_de_bord_recap_des_activites_du_jour'),
            };

            if(permissions.activite_du_jour){
                 const sans_rdv_rejete = document.getElementById('sans_rdv_rejete');
                sans_rdv_rejete.innerHTML = data.sans_rdv_rejete || 0 ;

                
                const sans_rdv_valide = document.getElementById('sans_rdv_valide');
                sans_rdv_valide.innerHTML = data.sans_rdv_valide || 0 ;

                const sans_rdv_total = document.getElementById('sans_rdv_total');
                sans_rdv_total.innerHTML = (data.sans_rdv_valide || 0) + (data.sans_rdv_rejete || 0) ;

                
                const avec_rdv_valide = document.getElementById('avec_rdv_valide');
                avec_rdv_valide.innerHTML = data.avec_rdv_valide || 0 ;

                const avec_rdv_rejete = document.getElementById('avec_rdv_rejete');
                avec_rdv_rejete.innerHTML = data.avec_rdv_rejete || 0 ;

                
                const avec_rdv_total = document.getElementById('avec_rdv_total');
                avec_rdv_total.innerHTML = (data.avec_rdv_valide || 0) + (data.avec_rdv_rejete || 0) ;

  
                searchData(data.data)
            }
               
                   // 
            })
            .catch(error => {
                console.error('Erreur:', error);
                // Vous pouvez afficher un message utilisateur ici, comme un toast ou une alerte
                alert('Une erreur est survenue lors de la récupération des données.');
            });
    }

        
    function searchData(data) {
        if (!Array.isArray(data) || data.length === 0) {
            console.error("Les données fournies ne sont pas valides ou sont vides.");
            return;
        }
    
        console.log("Données reçues :", data);
    
        // Sélectionner le corps du tableau
        const tableBody = document.querySelector('#datatable-traitement tbody');
    
        // Vérifier si le tableau a un `tbody`, sinon en créer un
        if (!tableBody) {
            console.error("Le tableau ne contient pas de corps `<tbody>`.");
            return;
        }
    
        // Effacer les lignes existantes dans le tableau
        tableBody.innerHTML = '';
        let nombre_total = 0;
        // Boucler sur les données et créer les lignes
        data.forEach(row => {
            const tr = document.createElement('tr'); // Créer une ligne de tableau
    
            // Créer une cellule pour le service
            const tdService = document.createElement('td');
            tdService.textContent = row.service || 'N/A'; // Valeur par défaut
            tr.appendChild(tdService);
    
            // Créer une cellule pour le montant payé
            const tdMontant = document.createElement('td');
            tdMontant.textContent = row.montant_paye !== undefined ? `${row.montant_paye} F` : '0 F'; // Valeur par défaut
            tr.appendChild(tdMontant);
    
            // Créer une cellule pour le nombre
            const tdNombre = document.createElement('td');
            tdNombre.textContent = row.nombre !== undefined ? row.nombre : '0'; // Valeur par défaut
            nombre_total += row.nombre !== undefined ? row.nombre : 0;
            tr.appendChild(tdNombre);
    
            // Ajouter la ligne au tableau
            tableBody.appendChild(tr);
        }
    );

        document.getElementById('traitement_total').innerHTML = nombre_total;
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
