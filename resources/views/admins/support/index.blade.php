@extends('layout.adminApp')

@section('content')



@if(CanPermission('entites_voir_la_liste_de_donnee_de_lentite'))@endisset 
    <div class="row col-md-12">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <h4 class="card-title mb-0"></h4>
                    </div> 

                    <div class="ml-auto">
                        <div class="hide js-show">
                            <div class="card-custom mb-4">
                             

                                <form class="searchData" action="{{ route('panel.autorisations.entities.support.search') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $Entity_uuid ?? '' }}" name="entity_uuid"  required />
                                    <div class="card-body-custom">
                                        <div class="row col-md-12 billing-section">
                                            <div class="form-group col-md-3">
                                                <label class="form-label">Rechercher par : </label>
                                                <select class="form-control" name="status">
                                                    <option value="transaction_id">ID de transaction</option>
                                                    <option value="immatriculation">Numero d'immatriculation</option>
                                                    <option value="numero_de_la_carte_grise">Numero de carte grise</option>
                                                    
                                                    <option value="nom_du_proprietaire">Nom du propriétaire</option>
                                                    <option value="telephone">Numéro de paiement</option>
                                                    <option value="reference">Réference</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-8">
                                                <label class="form-label"> &nbsp; &nbsp; &nbsp; </label>
                                                <input type="text" class="form-control" id="target" name="target" required />
                                            </div>
                                           

                                            
                                        
                                            <div class="form-group col-md-1">
                                                <label class="form-label">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</label>
                                                <button type="submit" id="submitBtn" class="btn btn-icon waves-effect waves-light material-shadow-none btn-outline-primary" title="Rechercher" >
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            
                            </div>
                        </div>
                        
                        {{-- <div class="col-md-12" id="resultContainer"></div> --}}
                        <div class="pt-5 table-responsive">
                            <div id="loader" style="display: none">
                                <i class="fa fa-spinner fa-spin"></i> Chargement en cours ...
                            </div>
                             
                            <table class="table" id="datatable-custom">
                                <thead>
                                <tr></tr>
                                </thead>
                                <tbody class="render-html">
                                    <tr>
                                        <td> </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    

                    
                </div>
            </div>
        </div>
    </div>




@push('footer-script')
@isset($Entity_uuid)
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script>
        var Entity_uuid = @Json($Entity_uuid ?? '');
        document.addEventListener("DOMContentLoaded", function () {
            let searchInput = document.getElementById("target");
            let searchForm = document.querySelector(".searchData");

            searchInput.addEventListener("input", function () {
                let formData = new FormData(searchForm);
                let action = searchForm.getAttribute("action");

                // Ajouter le token CSRF
                formData.append('_token', document.querySelector('input[name="_token"]').value);

                // Afficher le loader
               // loader();
                document.getElementById('loader').style.display="block";
               
                fetch(action, {
                    method: "POST",
                    body: formData,
                })
                .then(response => response.json())  // Convertir la réponse en JSON
                .then(data => {
                    document.getElementById('loader').style.display="none";
                   // loader("hide");
                   if(data.type ==="success"){
                     searchData(data);
                   }else{ 
                        if ($.fn.DataTable.isDataTable('#datatable-custom')) {
                           
                            // Détruire l'instance existante avant de recréer une nouvelle DataTable
                            $('#datatable-custom').DataTable().clear().draw(); // Réinitialiser la table
                            return;
                        }
                   }
                       
                    
                })
                .catch(error => {
                    document.getElementById('loader').style.display="none";
                    
                    //console.error("Erreur :", error);
                    //SendError("Une erreur s'est produite lors de la recherche.");
                });
            });
        }); 




        
    function searchData(data){

        if (!data || !data.entete || !data.data) {
            //  throw new Error('Données manquantes ou incorrectes dans la réponse');
        }
         
        const entete = data.entete;
        const results = data.data;
       // const entity = data.entity;
        console.log(results)
       

        // Construire dynamiquement les en-têtes du tableau
        let headerHtml = '<tr>';
        /* entete.forEach(col => {
            headerHtml += `<th>${col.name}</th>`;
        }); */
        headerHtml += " <th>Nom du propriétaire</th> <th>Numéro de carte grise</th> <th>Numéro d'immatriculation</th> <th>Transaction ID</th>  <th>Opérateur</th>  <th>Statut du véhicule</th> <th>Action</th></tr>"; // Ajout des colonnes "Statut" et "Action"
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
               
                
                {
                    data: 'nom_du_proprietaire',
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
                    data: 'numero_dimmatriculation',
                    render: function(data, type, row) {
                        return `${data}`;
                    }
                },
                {
                    data: 'transaction_id',
                    render: function(data, type, row) {
                        return `${data}`;
                    }
                }, 
                {
                    data: 'paymode',
                    render: function(data, type, row) {
                        return `${data}`;
                    }
                }, /**/
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
    </script>
@endisset
{{--
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/services_listing.js') }}"></script>  --}}
@endpush
@endsection
