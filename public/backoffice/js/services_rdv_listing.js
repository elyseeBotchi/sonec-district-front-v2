$(document).ready(function() {
   // findAll();
    findStat();

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
          
          const entete = data.entete;
          const results = data.data;
          const entity = data.entity;
          //console.log(results)

          // Construire dynamiquement les en-têtes du tableau
          let headerHtml = '<tr>';
          entete.forEach(col => {
              headerHtml += `<th>${col.name}</th>`;
          });
         // headerHtml += '<th>Statut</th><th>Statut du véhicule</th><th>Action</th></tr>'; // Ajout des colonnes "Statut" et "Action"
         // $('#datatable-custom thead').html(headerHtml);

          // Vérifier si la DataTable a déjà été initialisée
          if ($.fn.DataTable.isDataTable('#datatable-custom')) {
              // Détruire l'instance existante avant de recréer une nouvelle DataTable
              $('#datatable-custom').DataTable().destroy();
          }

         
          // Initialisation de la DataTable avec les nouvelles données
        /*   $('#datatable-custom').DataTable({
              data: results,
              columns: [
                  ...entete.map(col => ({ 
                      data: slugify(col.name),
                     // data: col.field // Utiliser le champ correspondant pour chaque colonne
                  })),
                  {
                      data: 'paiement_status',
                      render: function(data, type, row) {
                          switch(data) {
                              case 'expire':
                                  return `<span class="badge rounded-pill badge-warning">${row.message}</span>`;
                              case 'success':
                                  return `<span class="badge badge-pill badge-success">${row.message}</span>`;
                              case 'error':
                                  return `<span class="badge rounded-pill badge-danger">${row.message}</span>`;
                             
                          }
                      }
                  },
                  {
                      data: 'state',
                      render: function(data, type, row) {
                          switch(data) {
                              case 'fail':
                                  return `<span class="badge rounded-pill badge-danger">Rejeté</span>`;
                              case 'validate':
                                  return `<span class="badge badge-pill badge-success">Validé</span>`;
                              default:
                                  return `<span class="badge rounded-pill badge-warning">En attente de validation</span>`;
                             
                          }
                      }
                  },
                  {
                      data: 'uuid',
                      render: function(data, type, row) {
                          let permissions = {
                              show: true,
                              edit: true,
                              change: true
                          };

                          let actions = '';

                          if (permissions.show) {
                              actions += `<a href="/panel/services/taxes/detail/${data}/${Entity_uuid}" title="Voir les détails" class="btn btn-outline-primary btn-icon waves-effect waves-light material-shadow-none"><i class="fa fa-eye"></i></a> &nbsp; `;
                          }

                          return actions;
                      }
                  }
              ],
              paging: false, // Désactiver la pagination
              searching: false, // Désactiver le filtre (champ de recherche)
              language: {
                  url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/fr-FR.json' // URL pour le fichier de traduction en français
              }
          }); */


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

     
    function findStat() {
        fetch(`/panel/services/taxes/rdv/statistique/${Entity_uuid}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
               // console.log(data);
                const results = data.data;
                //console.log(data);

                
                
                
                var permissions = {
                    show_rdv_valide: canPermission('rendez_vous_voir_le_nombre_de_rendez_valide'),
                    show_rdv_rejeter: canPermission('rendez_vous_voir_le_nombre_de_rendez_rejete'),
                    show_mes_rdv_valide: canPermission('rendez_vous_voir_mes_rendez_vous_valide'),
                    show_mes_rdv_rejeter: canPermission('rendez_vous_voir_mes_rendez_vous_rejete'),
                    show_rdv_jours: canPermission('rendez_vous_voir_le_nombre_de_rendez_vous_du_jour'),
                };
                
                // Vide le select avant d'ajouter de nouvelles options
                if(permissions.show_rdv_valide){
                    const rdv_valide = document.getElementById('rdv_valide');
                    rdv_valide.innerHTML = results.rdv_valide;                    
                }

                if(permissions.show_rdv_rejeter){
        
                    const rdv_rejete = document.getElementById('rdv_rejete');
                    rdv_rejete.innerHTML = results.rdv_rejete;                            
                }

                // Vide le select avant d'ajouter de nouvelles options
                if(permissions.show_mes_rdv_valide){
                    const mes_rdv_valide = document.getElementById('mes_rdv_valide');
                    mes_rdv_valide.innerHTML = results.mes_rdv_valide;                    
                }

                if(permissions.show_mes_rdv_rejeter){
        
                    const mes_rdv_rejete = document.getElementById('mes_rdv_rejete');
                    mes_rdv_rejete.innerHTML = results.mes_rdv_rejete;                            
                }


                if(permissions.show_rdv_jours){
                    const rdv_jours = document.getElementById('rdv_jours');
                    rdv_jours.innerHTML = results.rdv_jours;                          
                }

            })
            .catch(error => {
                console.error('Erreur:', error);
            });
    }

    function slugify(string) {
        // Remplacer les espaces et les caractères spéciaux par des tirets, et convertir en minuscule
        var data = string.toString().toLowerCase()
            .replace(/\s+/g, '-')           // Remplace les espaces par des tirets
            .replace(/[^\w\-]+/g, '')       // Supprime tous les caractères non alphanumériques
            .replace(/\-\-+/g, '-')         // Remplace les doubles tirets par un seul tiret
            .replace(/^-+/, '')             // Supprime les tirets au début
            .replace(/-+$/, '');   
            
            return convertSlugToName(data) ;
    }

    
    function convertSlugToName(slug) {
        // Remplacer les tirets par des underscores
        return slug.replace(/-/g, '_');
    }

    function translatePeriodicity(periodicity) {
        switch (periodicity) {
            case 'monthly':
                return 'mois';
            case 'quarterly':
                return 'trimestre';
            case 'yearly':
                return 'ans';
            case 'weekly':
                return 'semaine';
            case 'daily':
                return 'jour';
            default:
                return periodicity; // Si la périodicité n'est pas reconnue, on renvoie la valeur telle quelle
        }
    }

     
    $('.searchForm').submit(function (e) {
        e.preventDefault();

        var action = $(this).attr('action');
        var formData = new FormData(this);
        $.ajax({
            url: action,
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                loader();
                // Remove previous error styles and messages
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
            },
            success: function (data) {
                loader('hide');
                if (data.type === "success") {
                    sendSuccess(data.message, data.urlback);
                    //console.log(data.data)
                   // searchData(data);
                }
                else {
                    SendError(data.message);
                }
            },
            error: function (xhr) {
                loader('hide');
                var errors = xhr.responseJSON.errors;
                handleErrors(errors);
                SendError('Veuillez corriger les erreurs ci-dessous.');
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });  

});