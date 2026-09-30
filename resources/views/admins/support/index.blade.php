@extends('layout.adminApp')

@section('content')

@if(CanPermission('entites_voir_la_liste_de_donnee_de_lentite'))@endisset
    <div class="v2-search-shell" style="max-width: 720px;">
        <div class="v2-search-card">
            <div class="v2-search-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            <p class="v2-search-card__title">Rechercher un enregistrement</p>
            <p class="v2-search-card__desc">Sélectionnez un critère puis saisissez votre recherche pour retrouver un paiement ou un véhicule.</p>

            <form class="searchData" action="{{ route('panel.autorisations.entities.support.search') }}" method="POST">
                @csrf
                <input type="hidden" value="{{ $Entity_uuid ?? '' }}" name="entity_uuid" required />

                <div class="v2-search-card__group">
                    <label class="v2-search-card__label" for="searchCriteria">Rechercher par</label>
                    <select class="v2-modal-input" name="status" id="searchCriteria">
                        <option value="transaction_id">ID de transaction</option>
                        <option value="immatriculation">Numero d'immatriculation</option>
                        <option value="numero_de_la_carte_grise">Numero de carte grise</option>
                        <option value="nom_du_proprietaire">Nom du propriétaire</option>
                        <option value="telephone">Numéro de paiement</option>
                        <option value="reference">Réference</option>
                    </select>
                </div>

                <div class="v2-search-card__group">
                    <label class="v2-search-card__label" for="target">Valeur recherchée</label>
                    <div class="v2-search-card__input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="text" id="target" name="target" placeholder="Entrez votre recherche" required />
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="loader" class="v2-table-loading" style="display: none">
        <span class="v2-spinner"></span> Chargement des données...
    </div>

    <div class="v2-card" style="margin-top: 20px;">
        <div class="v2-table-wrap">
            <table class="v2-table" id="datatable-custom">
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

@push('footer-script')
@isset($Entity_uuid)
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
<script>
    let searchForm = document.querySelector(".searchData");
    // Empêcher la soumission native du formulaire (important)
    searchForm.addEventListener("submit", function (e) {
        e.preventDefault();
    });

    var Entity_uuid = @Json($Entity_uuid ?? '');
    document.addEventListener("DOMContentLoaded", function () {
        let searchInput = document.getElementById("target");
        let searchForm = document.querySelector(".searchData");
        let searchCriteria = document.getElementById("searchCriteria");
        let debounceTimer;
        let isSearching = false; // Indicateur pour empêcher les soumissions multiples

        // Fonction debounce pour limiter les appels fréquents
        function debounce(func, delay) {
            return function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(func, delay);
            };
        }

        // Écouteur d'événement pour la saisie dans le champ de recherche
        searchInput.addEventListener("input", debounce(function () {
            if (!isSearching) {
                performSearch();
            }
        }, 2000)); // Délai de 3 secondes (3000 ms)

        // Écouteur d'événement pour le changement de <select>
        searchCriteria.addEventListener("change", function () {
            if (!isSearching) {
                performSearch();
            }
        });

        // Fonction pour exécuter la recherche
        function performSearch() {
            if (isSearching) return; // Si une recherche est déjà en cours, on ne fait rien

            isSearching = true; // Marquer qu'une recherche est en cours
            let formData = new FormData(searchForm);
            let action = searchForm.getAttribute("action");

            // Ajouter le token CSRF
            formData.append('_token', document.querySelector('input[name="_token"]').value);

            // Afficher le loader
            document.getElementById('loader').style.display = "block";

            fetch(action, {
                method: "POST",
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('loader').style.display = "none";
                if (data.type === "success") {
                    searchData(data);
                } else {
                    if ($.fn.DataTable.isDataTable('#datatable-custom')) {
                        // Détruire l'instance existante avant de recréer une nouvelle DataTable
                        $('#datatable-custom').DataTable().clear().draw(); // Réinitialiser la table
                    }
                }
                isSearching = false; // Réinitialiser l'indicateur après la fin de la recherche
            })
            .catch(error => {
                document.getElementById('loader').style.display = "none";
                console.error("Erreur :", error);
                isSearching = false; // Réinitialiser l'indicateur en cas d'erreur
            });
        }

        // Fonction pour afficher les données dans le tableau
        function searchData(data) {

                //console.log(data.data);
            if (!data || !data.entete || !data.data) {
                return; // Si les données sont manquantes, on ne fait rien
            }

            const results = data.data;

            // Construire dynamiquement les en-têtes du tableau
            let headerHtml = '<tr>';
            headerHtml += "<th>Nom du propriétaire</th> <th>Numéro de carte grise</th> <th>Numéro d'immatriculation</th> <th>Transaction ID</th>  <th>Opérateur</th>  <th>Statut du véhicule</th> <th>Action</th></tr>";
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
                    },
                    {
                        data: 'state',
                        render: function(data, type, row) {
                            switch(data) {
                                case 'fail':
                                    return `<span class="v2-status v2-status--danger">Rejeté</span>`;
                                case 'validate':
                                    return `<span class="v2-status v2-status--success">Validé</span>`;
                                case 'paid':
                                    return `<span class="v2-status v2-status--success">Payé</span>`;
                                case 'error':
                                    return `<span class="v2-status v2-status--danger">Annulé</span>`;
                                default:
                                    return ``;
                            }
                        }
                    },
                    {
                        data: 'numero_dimmatriculation',
                        render: function(data, type, row) {
                            let permissions = {
                                show: true,
                                edit: true,
                                change: true
                            };

                            let actions = '';

                            if (permissions.show) {
                                actions += `<a href="/panel/support/show/uuid-pay/${row.transaction_id}/${Entity_uuid}" title="Voir les détails" class="btn btn-outline-primary btn-icon waves-effect waves-light material-shadow-none"><i class="fa fa-eye"></i></a> &nbsp; `;
                            }

                            return actions;
                        }
                    }
                ]
            });
        }
    });
</script>
@endisset
@endpush
@endsection

