$(document).ready(function() {
    findAll();

    function findAll() {
      //  alert(Entity_uuid)
        fetch(`/panel/services/taxes/rdv/findAll/${Entity_uuid}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue lors de la récupération des données');
                }
                return response.json();
            })
            .then(data => { 
                //console.log(data)
                if(data.entete){
                    searchData(data)
                }
                   // 
            })
            .catch(error => {
                console.error('Erreur:', error);
                // Vous pouvez afficher un message utilisateur ici, comme un toast ou une alerte
                alert('Une erreur est survenue lors de la récupération des données.');
            });
    }


    function searchData(data){

        if (!data || !data.entete || !data.data || !data.entity) {
            //  throw new Error('Données manquantes ou incorrectes dans la réponse');
          }
          
          const results = data.data;
          //console.log(results)

          // Vérifier si la DataTable a déjà été initialisée
          if ($.fn.DataTable.isDataTable('#datatable-custom')) {
              // Détruire l'instance existante avant de recréer une nouvelle DataTable
              $('#datatable-custom').DataTable().destroy();
          }

          $('#datatable-custom').DataTable({
            data: results,
            columns: [ 
                {
                    data: 'numero_dimmatriculation',
                    render: function(data, type, row) {
                        return `${data}`;

                    }
                }, 
                {
                    data: 'numero_de_la_carte_grise',
                    render: function(data, type, row) {
                        return `${data}`;

                    }
                },
                {
                    data: 'service',
                    render: function(data, type, row) {
                        return `${data}`;

                    }
                },
                {
                    data: 'state',
                    render: function(data, type, row) {
                        switch(data) {
                            case 'fail':
                                return `<span class="badge rounded-pill badge-danger">Non valide</span>`;
                            case 'validate':
                                return `<span class="badge badge-pill badge-success">Validé</span>`;
                            default:
                                return `<span class="badge rounded-pill badge-warning">En attente de validation</span>`;
                           
                        }
                    }
                },
                {
                    data: 'validate_at',
                    render: function(data, type, row) {
                        if (data) {
                            // Extraire uniquement l'heure et les minutes
                            const dateObj = new Date(data);
                            const hours = dateObj.getHours().toString().padStart(2, '0'); // Ajoute un zéro devant si nécessaire
                            const minutes = dateObj.getMinutes().toString().padStart(2, '0'); // Ajoute un zéro devant si nécessaire
                            return `${hours}:${minutes}`; // Formater en HH:MM
                        }
                        return ''; // Retourner une chaîne vide si `data` est null ou undefined
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

});