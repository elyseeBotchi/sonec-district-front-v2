$(document).ready(function() {
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

  
              //  searchData(data.data)
                   // 
            })
            .catch(error => {
                console.error('Erreur:', error);
                // Vous pouvez afficher un message utilisateur ici, comme un toast ou une alerte
                alert('Une erreur est survenue lors de la récupération des données.');
            });
    }


    function searchData__(data){

          const results = data;
          //console.log(data)

          // Vérifier si la DataTable a déjà été initialisée
          if ($.fn.DataTable.isDataTable('#datatable-custom')) {
              // Détruire l'instance existante avant de recréer une nouvelle DataTable
              $('#datatable-custom').DataTable().destroy();
          }

          $('#datatable-custom').DataTable({
            data: results,
            columns: [ 
                {
                    data: 'service',
                    render: function(data, type, row) {
                        return `${data}`;

                    }
                }, 
                {
                    data: 'montant_paye',
                    render: function(data, type, row) {
                        return `${data}`;

                    }
                },
                {
                    data: 'nombre',
                    render: function(data, type, row) {
                        return `${data}`;

                    }
                }
            ],
            paging: false, // Désactiver la pagination
            searching: false, // Désactiver le filtre (champ de recherche)
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/fr-FR.json' // URL pour le fichier de traduction en français
            }
        });
    } 

    
    function searchData(data) {
        if (!Array.isArray(data) || data.length === 0) {
            console.error("Les données fournies ne sont pas valides ou sont vides.");
            return;
        }
    
        //console.log("Données reçues :", data);
    
        // Sélectionner le corps du tableau
        const tableBody = document.querySelector('#datatable-custom tbody');
    
        // Vérifier si le tableau a un `tbody`, sinon en créer un
        if (!tableBody) {
            console.error("Le tableau ne contient pas de corps `<tbody>`.");
            return;
        }
    
        // Effacer les lignes existantes dans le tableau
        tableBody.innerHTML = '';
    
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
            tr.appendChild(tdNombre);
    
            // Ajouter la ligne au tableau
            tableBody.appendChild(tr);
        });
    }
    
    
    
    

});