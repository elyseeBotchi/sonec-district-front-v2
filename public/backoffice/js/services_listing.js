$(document).ready(function() {
    findRubriques()
    //findAll();
    findStat();

    
    function findAll() {
      //  alert(Entity_uuid)
        fetch(`/panel/services/taxes/findAll/${Entity_uuid}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue lors de la récupération des données');
                }
                return response.json();
            })
            .then(data => { 
                //console.log(data)
                    searchData(data)
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
          console.log(results)

          // Construire dynamiquement les en-têtes du tableau
          let headerHtml = '<tr>';
          entete.forEach(col => {
              headerHtml += `<th>${col.name}</th>`;
          });
          headerHtml += '<th>Statut</th><th>Statut du véhicule</th><th>Action</th></tr>'; // Ajout des colonnes "Statut" et "Action"
          $('#datatable-custom thead').html(headerHtml);

          // Vérifier si la DataTable a déjà été initialisée
          if ($.fn.DataTable.isDataTable('#datatable-custom')) {
              // Détruire l'instance existante avant de recréer une nouvelle DataTable
              $('#datatable-custom').DataTable().destroy();
          }

         
          // Initialisation de la DataTable avec les nouvelles données
          $('#datatable-custom').DataTable({
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
              ]
          });
    }

    function findRubriques() {
        fetch(`/landing/services/rubrique/findOneConfig/${Entity_uuid}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                const results = data.data;
                const rubriqueSelect = document.getElementById('rubrique');
                rubriqueSelect.innerHTML = ''; // Vider le contenu actuel du select

                // Ajouter l'option "Tous"
                let allOption = document.createElement('option');
                allOption.value = '';
                allOption.textContent = 'Tous';
                rubriqueSelect.appendChild(allOption);
                // Vérification que les rubriques existent
                if (results.rubrique && results.rubrique.length > 0) {
                    results.rubrique.forEach(rubrique => {
                        if (rubrique.rubrique_option.length > 0) {
                            // Créer un groupe d'options pour chaque rubrique ayant des options
                            let optgroup = document.createElement('optgroup');
                            optgroup.label = rubrique.name;

                            // Ajouter les options sous chaque rubrique
                            rubrique.rubrique_option.forEach(option => {
                                // Vérifier s'il y a des facturations pour l'option
                                if (option.facturation && option.facturation.length > 0) {
                                    option.facturation.forEach(facturation => {
                                        let optionElement = document.createElement('option');
                                        optionElement.value = facturation.uuid; // Utiliser l'UUID de la facturation
                                        optionElement.textContent = `${option.option_name} - ${facturation.amount} Fr CFA / ${translatePeriodicity(facturation.periodicity)}`;

                                        // Ajouter l'option pour chaque facturation
                                        optgroup.appendChild(optionElement);
                                    });
                                } else {
                                    // Si pas de facturation, désactiver l'option
                                    let optionElement = document.createElement('option');
                                    optionElement.textContent = `${option.option_name} (Pas de facturation disponible)`;
                                    optionElement.disabled = true;

                                    // Ajouter l'option désactivée
                                    optgroup.appendChild(optionElement);
                                }
                            });

                            // Ajouter l'optgroup au select
                            rubriqueSelect.appendChild(optgroup);
                        } else {
                            // Si la rubrique n'a pas d'options, ajouter la rubrique elle-même comme une option sélectionnable
                            let optionElement = document.createElement('option');
                            optionElement.value = rubrique.uuid;

                            // Vérification de la facturation pour la rubrique
                            if (rubrique.facturation && rubrique.facturation.length > 0) {
                                rubrique.facturation.forEach(facturation => {
                                    let facturationOption = document.createElement('option');
                                    facturationOption.value = facturation.uuid; // Utiliser l'UUID de la facturation
                                    facturationOption.textContent = `${rubrique.name} - ${facturation.amount} Fr CFA / ${translatePeriodicity(facturation.periodicity)}`;

                                    // Ajouter l'option pour chaque facturation
                                    rubriqueSelect.appendChild(facturationOption);
                                });
                            } else {
                                // Désactiver la rubrique si pas de montant
                                optionElement.textContent = `${rubrique.name} (Pas de facturation disponible)`;
                                optionElement.disabled = true;

                                // Ajouter directement la rubrique désactivée
                                rubriqueSelect.appendChild(optionElement);
                            }
                        }
                    });
                } 
                else {
                    // Si aucune rubrique n'est trouvée, afficher un message par défaut
                    let defaultOption = document.createElement('option');
                    defaultOption.textContent = 'Aucune rubrique disponible';
                    defaultOption.disabled = true;
                    rubriqueSelect.appendChild(defaultOption);
                }
                document.getElementById('submitBtn').style.display ='block';
                
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
    }

       
     
    function findStat() {
        fetch(`/panel/services/taxes/statistique/${Entity_uuid}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                //console.log(data);
                const results = data.data;
                //console.log(data);

                
                
                
                var permissions = {
                    show_carte_valide: canPermission('entites_voir_les_cartes_valides'),
                    show_carte_expirer: canPermission('entites_voir_les_cartes_expirees'),
                    show_nouveau_contrevenant: canPermission('entites_voir_les_nouveaux_contrevenants'),
                    show_total_contrevenant: canPermission('entites_voir_tous_les_contrevenants'),
                };
                
                // Vide le select avant d'ajouter de nouvelles options
                if(permissions.show_carte_valide){
                    const carte_valide = document.getElementById('carte_valide');
                    carte_valide.innerHTML = Math.ceil(results.carte_valide);                    
                }

                if(permissions.show_carte_expirer){
        
                    const carte_expirer = document.getElementById('carte_expirer');
                    carte_expirer.innerHTML = results.carte_expirer;                            
                }


                if(permissions.show_nouveau_contrevenant){
                    const nouveau_contrevenant = document.getElementById('nouveau_contrevenant');
                    nouveau_contrevenant.innerHTML = results.nouveau_contrevenant;                          
                }


                if(permissions.show_total_contrevenant){
                    const total_contrevenant = document.getElementById('total_contrevenant');
                    total_contrevenant.innerHTML = results.total_contrevenant;              
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

     
    $('.searchData').submit(function (e) {
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
                    console.log(data.data)
                    searchData(data);
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