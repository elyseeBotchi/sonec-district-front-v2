@extends('layout.adminApp')
 <style>
    .card-header-custom, .card-header-custom:first-child {
    border-radius: 2px;
    }
    .card-header-custom:first-child {
    border-radius: var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius) 0 0;
    }
    .card-header-custom {
    position: relative;
    top: -1rem;
    margin: 0 5px;
    width: -webkit-max-content;
    width: -moz-max-content;
    width: max-content;
    max-width: 100%;
    font-weight: bold;
    padding: 5px 10px;
    border: 1px solid #712626;
    font-size: 1em;
    box-shadow: 3px 3px 15px #bbb;
    }
    .card-header-custom {
    padding: var(--bs-card-cap-padding-y) var(--bs-card-cap-padding-x);
    margin-bottom: 0;
    color: var(--bs-card-cap-color);
    background-color: var(--bs-card-cap-bg);
    border-bottom: var(--bs-card-border-width) solid var(--bs-card-border-color);
    }
    *, ::before, ::after {
    box-sizing: border-box;
    }
    .card-custom, .card-footer-custom {
    text-shadow: 1px 1px 2px #fff;
    }

    .card-custom, .card-footer-custom {
  text-shadow: 1px 1px 2px #fff;
  box-shadow: 1px 1px 2px #fff inset;
}
.card-custom {
  margin-top: 2rem;
  margin-bottom: .5rem;
}
.mb-4 {
  margin-bottom: 1.5rem !important;
}
.card-custom {
  --bs-card-spacer-y: 1rem;
  --bs-card-spacer-x: 1rem;
  --bs-card-title-spacer-y: 0.5rem;
  --bs-card-border-width: 1px;
  --bs-card-border-color: #aaa;
  --bs-card-border-radius: 0.25rem;
  --bs-card-box-shadow: ;
  --bs-card-inner-border-radius: calc(0.25rem - 1px);
  --bs-card-cap-padding-y: 0.5rem;
  --bs-card-cap-padding-x: 1rem;
  --bs-card-cap-bg: #fff;
  --bs-card-cap-color: ;
  --bs-card-height: ;
  --bs-card-color: ;
  --bs-card-bg: #eee;
  --bs-card-img-overlay-padding: 1rem;
  --bs-card-group-margin: 0.75rem;
  position: relative;
  display: flex;
  flex-direction: column;
  min-width: 0;
  height: var(--bs-card-height);
  word-wrap: break-word;
  background-color: var(--bs-card-bg);
  background-clip: border-box;
  border: var(--bs-card-border-width) solid var(--bs-card-border-color);
  border-radius: var(--bs-card-border-radius);
  box-shadow: var(--bs-card-box-shadow);
}
 </style>


@section('content')
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">
               <span class="entity_name"> <i class="fa fa-spinner fa-spin"></i></span>
            </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Entité</li>
                    <li class="breadcrumb-item active">Détail</li>
                </ol>
            </div>

        </div>
    </div>

    <div  id="container">
        <div class="row">
            <div class="card col-md-12">
                <h3>Informations sur l'entité</h3>
                <table class="table no-wrap v-middle mb-0">
                    <tbody>
                        <tr>
                            <td>
                                <strong>Nom</strong>
                            </td>
                            <td class="entity_name"> <i class="fa fa-spinner fa-spin"></i> </td> <!-- Place holder for the entity name -->
                        </tr>
                        <tr>
                            <td>
                                <strong>Version actuelle</strong>
                            </td>
                            <td id="current_version"> <i class="fa fa-spinner fa-spin"></i> </td> <!-- Placeholder for the current version -->
                        </tr>
                        <tr>
                            <td>
                                <strong>Table des attributs</strong>
                            </td>
                            <td id="attribute_table_name"> <i class="fa fa-spinner fa-spin"></i> </td> <!-- Placeholder for the attribute table name -->
                        </tr>
                        <tr>
                            <td>
                                <strong>État</strong>
                            </td>
                            <td id="entity_state"> <i class="fa fa-spinner fa-spin"></i> </td> <!-- Placeholder for the state -->
                        </tr>
                        <tr>
                            <td>
                                <strong>Créé le</strong>
                            </td>
                            <td id="created_at"> <i class="fa fa-spinner fa-spin"></i> </td> <!-- Placeholder for the creation date -->
                        </tr>
                        <tr>
                            <td>
                                <strong>Mis à jour le</strong>
                            </td>
                            <td id="updated_at"> <i class="fa fa-spinner fa-spin"></i> </td> <!-- Placeholder for the update date -->
                        </tr>
                    </tbody>
                </table>

                <h4 style="display:none;">Config Schéma</h4>
                <pre  class='bg-dark' id="config_schema" style="display:none;">
                    <i class="fa fa-spinner fa-spin"></i>
                </pre>
            </div>

            <div class="card col-md-12">
                <h3>Rubriques budgetaires</h3>
                <div class="text-end mb-4">
                    <a href="#" class="btn btn-rounded btn-outline-primary float-right" data-toggle="modal" data-target="#add-modal">
                        <i class="fas fa-plus"></i> Ajouter une rubrique
                    </a>
                </div>
                <div class="modal fade" id="add-modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <form class="modal-content sendRubriqueForm" action="{{ route('panel.autorisations.entite.rubrique.store') }}" method="POST">
                            @csrf
                            <input type="hidden" value="{{ $entity_uuid ?? '' }}" name="entity_uuid" required />
                            <div class="modal-header">
                                <h5 class="mb-0 text-uppercase">Ajouter une rubrique</h5>
                                <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal">
                                    <i class="ti ti-x f-20"></i>
                                </a>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Libellé <code>*</code></label>
                                            <input type="text" class="form-control btn-outline-secondary" name="rubrique" required />
                                        </div>
                                    </div>

                                    <div class="col-md-12" >
                                        <div id="columns"></div>
                                    </div>

                                    <div class="col-md-12">
                                        <br>
                                        <button type="button" class="btn btn-icon waves-effect waves-light material-shadow-none btn-outline-warning"  onclick="addSubRubric()" >Ajouter une sous rubrique</button>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button"  class="btn btn-secondary btn-shadow closeModal" data-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary btn-shadow">Sauvegarder</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="modal fade" id="updateElement-modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <form class="modal-content sendRubriqueForm" action="{{ route('panel.autorisations.entite.rubrique.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="entity_uuid"  value="{{ $entity_uuid ?? '' }}" required />
                            <input type="hidden" name="rubrique_uuid" id="update_rubrique_target" value="" />
                            <input type="hidden" name="rubrique_option_uuid" id="update_rubrique_option_target" value="" />
                            <input type="hidden" name="target_rule" id="update_target_rule" value="" />
        
                            
                            <div class="modal-header">
                                <h5 class="mb-0 text-uppercase">Modification du libellé <span id="title_name"></span> </h5>
                                <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal">
                                    <i class="ti ti-x f-20"></i>
                                </a>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Libellé <code>*</code></label>
                                            <input type="text" class="form-control btn-outline-secondary" id="update_element" name="rubrique" required />
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button"  class="btn btn-secondary btn-shadow closeUpModal" data-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary btn-shadow">Sauvegarder</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr class="border-0">
                                <th class="border-0 font-14 font-weight-medium" style="width: 50% !important">
                                    Libelle
                                </th>
                                <th class="border-0 font-14 font-weight-medium">
                                    Rubrique options
                                </th>
                            </tr>
                        </thead>
                        <tbody id="rubrique_container">
                            <tr>
                                <td colspan="2"> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            
        </div>

        <div class="modal fade" id="addFacturation-modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <form class="modal-content sendRubriqueForm" action="{{ route('panel.autorisations.entite.rubrique.facturation.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="entity_uuid"  value="{{ $entity_uuid ?? '' }}" required />
                    <input type="hidden" name="rubrique_uuid" id="rubrique_target" value="" />
                    <input type="hidden" name="rubrique_option_uuid" id="rubrique_option_target" value="" />
                    <input type="hidden" name="target" id="target_rule" value="" />


                    <div class="modal-header">
                        <h5 class="mb-0 text-uppercase">Ajouter une facturation à <span id="tarification_target"></span> </h5>
                        <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal">
                            <i class="ti ti-x f-20"></i>
                        </a>
                    </div>
                    <div class="modal-body">
                        @isset($lock)
                            <div class="hide js-show" style="display: block;">
                                <div class="card-custom mb-4">
                                    <div class="card-header-custom">
                                        <span id="languageSelectLabel" style="font-size:x-small;">
                                            Facturation
                                        </span>
                                    </div>
                                <div class="card-body-custom">
                                    <br>
                                </div>
                                </div>
                            </div>
                        @endisset 



                        <div id="facturation-container" class="row">
                            <div class="row col-md-12 billing-section">
                                <div class="form-group col-md-3">
                                    <label class="form-label">Montant <code>*</code></label>
                                    <input type="number" step="0.01" class="form-control" id="amount" name="billings[0][amount]" required />
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="form-label">Quantité <code>*</code></label>
                                    <input type="number" value="1" class="form-control" id="quantity" name="billings[0][quantity]" required />
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="form-label">Périodicité <code>*</code></label>
                                    <select class="form-control" name="billings[0][periodicity]" required>
                                        <option value="daily">Quotidienne</option>
                                        <option value="weekly">Hebdomadaire</option>
                                        <option value="monthly">Mensuelle</option>
                                        <option value="yearly">Annuelle</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="form-label">Montant penalité <code>*</code></label>
                                    <input type="number" step="0.01" class="form-control" id="penalties" name="billings[0][penalties]" required />
                                </div>
                                <div class="form-group col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-icon waves-effect waves-light material-shadow-none btn-outline-danger" onclick="removeBilling(this)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-12">
                            <button type="button" class="btn btn-icon waves-effect waves-light material-shadow-none btn-outline-warning" onclick="addBilling()">Ajouter une facturation</button>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-shadow closeModal" id="close-modal" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary btn-shadow">Sauvegarder</button>
                    </div>
                </form>
            </div>
        </div>

        
    
    </div>
@endsection

@push('footer-script')
    <script>
        var Entity_uuid = @Json($entity_uuid);

        let subRubricIndex = 0;

        function addSubRubric() {
            subRubricIndex++;

            const columnsDiv = document.getElementById('columns');
            columnsDiv.style.display = 'block';  // Make sure the columns div is visible

            const newColumn = document.createElement('div');
            newColumn.className = 'column row';
            newColumn.innerHTML = `
                <div class="col-md-10">
                    <label for="column_name">Sous rubrique</label>
                    <input type="text" class="form-control" name="rubrique_options[${subRubricIndex}][name]" required>
                </div>
                <div class="col-md-1">
                    <label for="column_name">&nbsp;&nbsp;&nbsp;</label>
                    <button type="button" class="btn btn-icon waves-effect waves-light material-shadow-none btn-outline-danger" onclick="removeColumn(this)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            `;

            columnsDiv.appendChild(newColumn);
        }

        function removeColumn(element) {
            const columnRow = element.closest('.column.row');
            columnRow.remove();

            // If there are no more columns, hide the columns div
            const columnsDiv = document.getElementById('columns');
            if (columnsDiv.children.length === 0) {
                columnsDiv.style.display = 'none';
            }
        }

        /* ########################################################## */

        let billingIndex = 0;

        function addBilling() {
            billingIndex++;

            const billingSection = document.createElement('div');
            billingSection.className = 'row col-md-12 billing-section';
            billingSection.innerHTML = `
                <div class="form-group col-md-3">
                    <label class="form-label">Montant <code>*</code></label>
                    <input type="number" step="0.01" class="form-control" name="billings[${billingIndex}][amount]" required />
                </div>
                <div class="form-group col-md-2">
                    <label class="form-label">Quantité <code>*</code></label>
                    <input type="number" value="1" class="form-control" name="billings[${billingIndex}][quantity]" required />
                </div>
                <div class="form-group col-md-3">
                    <label class="form-label">Périodicité <code>*</code></label>
                    <select class="form-control" name="billings[${billingIndex}][periodicity]" required>
                        <option value="daily">Quotidienne</option>
                        <option value="weekly">Hebdomadaire</option>
                        <option value="monthly">Mensuelle</option>
                        <option value="yearly">Annuelle</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label class="form-label">Montant penalité <code>*</code></label>
                    <input type="number" step="0.01" class="form-control" id="penalties" name="billings[0][penalties]" required />
                </div>
                <div class="form-group col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-icon waves-effect waves-light material-shadow-none btn-outline-danger" onclick="removeBilling(this)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            `;

            const modalBody = document.querySelector('#facturation-container');
            modalBody.appendChild(billingSection);
        }

        function removeBilling(element) {
            const billingSection = element.closest('.billing-section');
            billingSection.remove();
        }


    </script>

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/entite.show.js') }}"></script>
@endpush
