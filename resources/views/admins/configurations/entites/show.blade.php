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
               <span class="entity_name"> <span class="v2-spinner v2-spinner--sm"></span></span>
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
            <div class="v2-card col-md-12">
                <div class="v2-card__header">
                    <p class="v2-card__title">Informations sur l'entité</p>
                </div>
                <div class="v2-table-wrap">
                    <table class="v2-table">
                        <tbody>
                            <tr>
                                <td><strong>Nom</strong></td>
                                <td class="entity_name"><span class="v2-spinner v2-spinner--sm"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Version actuelle</strong></td>
                                <td id="current_version"><span class="v2-spinner v2-spinner--sm"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Table des attributs</strong></td>
                                <td id="attribute_table_name"><span class="v2-spinner v2-spinner--sm"></span></td>
                            </tr>
                            <tr>
                                <td><strong>État</strong></td>
                                <td id="entity_state"><span class="v2-spinner v2-spinner--sm"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Créé le</strong></td>
                                <td id="created_at"><span class="v2-spinner v2-spinner--sm"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Mis à jour le</strong></td>
                                <td id="updated_at"><span class="v2-spinner v2-spinner--sm"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h4 style="display:none;">Config Schéma</h4>
                <pre  class='bg-dark' id="config_schema" style="display:none;">
                    <span class="v2-spinner v2-spinner--sm"></span>
                </pre>
            </div>

            <div class="v2-card col-md-12">
                <div class="v2-card__header">
                    <p class="v2-card__title">Rubriques budgetaires</p>
                </div>
                <div class="text-end mb-4">
                    <a href="#" class="v2-btn v2-btn--primary" data-toggle="modal" data-target="#add-modal">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Ajouter une rubrique
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

                                    <div id="update_columns"></div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button"  class="btn btn-secondary btn-shadow closeUpModal" data-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary btn-shadow">Sauvegarder</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="v2-table-wrap">
                    <table class="v2-table">
                        <thead>
                            <tr>
                                <th style="width: 50%">Libelle</th>
                                <th>Rubrique options</th>
                            </tr>
                        </thead>
                        <tbody id="rubrique_container">
                            <tr class="v2-table-loading">
                                <td colspan="2"><span class="v2-spinner"></span> Chargement des données...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            
        </div>

        <div class="modal fade" id="addFacturation-modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
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
                       
                        <div class="col-12">
                            <label class="form-label">Lieu de rendez-vous <code>*</code></label>

                            <select name="lieu_rdv" id="list_rdv" class="form-control bg-light border-0" style="height: 40px;">
                                @isset($lieuRdv)
                                    @forelse($lieuRdv as $key => $value)
                                        <option value="{{ $value['uuid'] }}"> {{ $value['libelle'] ?? '' }} </option>
                                    @empty
                                    @endforelse
                                @endisset
                            </select> 
                        </div>




                        <div id="facturation-container" class="row">
                            <div class="col-md-12 billing-section row">
                        
                                <!-- Montant -->
                                <div class="form-group col-md-4">
                                    <label class="form-label">Montant <code>*</code></label>
                                    <input type="number" step="0.01" class="form-control" name="billings[0][amount]" required />
                                </div>
                        
                                <!-- Quantité -->
                                <div class="form-group col-md-4">
                                    <label class="form-label">Quantité <code>*</code></label>
                                    <input type="number" value="1" class="form-control" name="billings[0][quantity]" required />
                                </div>
                        
                                <!-- Périodicité -->
                                <div class="form-group col-md-4">
                                    <label class="form-label">Périodicité <code>*</code></label>
                                    <select class="form-control" name="billings[0][periodicity]" required>
                                        <option value="daily">Quotidienne</option>
                                        <option value="weekly">Hebdomadaire</option>
                                        <option value="monthly">Mensuelle</option>
                                        <option value="yearly">Annuelle</option>
                                    </select>
                                </div>
                        
                                <!-- Montant pénalité (Frais d’enlèvement) -->
                                <div class="form-group col-md-4">
                                    <label class="form-label">Montant pénalité <sup>(Frais d'enlèvement)</sup> <code>*</code></label>
                                    <input type="number" step="0.01" class="form-control" name="billings[0][penalties]" required />
                                </div>
                        
                                <!-- Montant & Périodicité pénalité (Frais de fourrière) -->
                                <div class="form-group col-md-7">
                                    <label class="form-label">
                                        Montant & Périodicité pénalité <sup>(Frais de fourrière)</sup> <code>*</code>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Montant</span>
                                        </div>
                                        <input 
                                            type="number" 
                                            step="0.01" 
                                            class="form-control" 
                                            name="billings[0][penalties_pound_amount]" 
                                            required 
                                        />
                        
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Périodicité</span>
                                        </div>
                                        <select 
                                            class="form-control" 
                                            name="billings[0][penalties_pound_periodicity]" 
                                            required>
                                            <option value="daily">Quotidienne</option>
                                            <option value="weekly">Hebdomadaire</option>
                                            <option value="monthly">Mensuelle</option>
                                            <option value="yearly">Annuelle</option>
                                        </select>
                                    </div>
                                </div>
                        
                                <!-- Bouton supprimer -->
                                <div class="form-group col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-danger btn-icon" onclick="removeBilling(this)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                                <hr style="border: 1px solid black;width: 100%;margin: 1rem 0;">

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

        {{-- addPenalty --}}

        <div class="modal fade" id="addPenalty-modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <form class="modal-content sendRubriqueForm" action="{{ route('panel.autorisations.entite.rubrique.facturation.penalty.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="entity_uuid"  value="{{ $entity_uuid ?? '' }}" required />
                    <input type="hidden" name="rubrique_uuid" id="rubrique_target_penalty" value="" />
                    <input type="hidden" name="rubrique_option_uuid" id="rubrique_option_target_penalty" value="" />
                    <input type="hidden" name="facturation_uuid" id="facturation_uuid_penalty" value="" required/>
                    <input type="hidden" name="target" id="target_rule_penalty" value="" />


                    <div class="modal-header">
                        <h5 class="mb-0 text-uppercase">Ajouter une pénalité à <span id="tarification_target_penalty"></span> </h5>
                        <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal">
                            <i class="ti ti-x f-20"></i>
                        </a>
                    </div>
                    <div class="modal-body">
                        <div id="facturation-container" class="row">
                            <div class="col-md-12 billing-section row">
                        
                                <!-- Montant pénalité (Frais d’enlèvement) -->
                                <div class="form-group col-md-4">
                                    <label class="form-label">Montant pénalité <sup>(Frais d'enlèvement)</sup> <code>*</code></label>
                                    <input type="number" step="0.01" class="form-control" value="20000" name="billings[penalties]" required />
                                </div>
                        
                                <!-- Montant & Périodicité pénalité (Frais de fourrière) -->
                                <div class="form-group col-md-7">
                                    <label class="form-label">
                                        Montant & Périodicité pénalité <sup>(Frais de fourrière)</sup> <code>*</code>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Montant</span>
                                        </div>
                                        <input id="amount_penalty"
                                            type="number" 
                                            step="0.01" 
                                            class="form-control" 
                                            name="billings[penalties_pound_amount]" 
                                            required 
                                        />
                        
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Périodicité</span>
                                        </div>
                                        <select 
                                            class="form-control" 
                                            name="billings[penalties_pound_periodicity]" 
                                            required>
                                            <option value="daily">Quotidienne</option>
                                            <option value="weekly">Hebdomadaire</option>
                                            <option value="monthly">Mensuelle</option>
                                            <option value="yearly">Annuelle</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
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
            billingSection.className = 'col-md-12 billing-section row';
            billingSection.innerHTML = `
                <!-- Montant -->
                <div class="form-group col-md-4">
                    <label class="form-label">Montant <code>*</code></label>
                    <input type="number" step="0.01" class="form-control" name="billings[${billingIndex}][amount]" required />
                </div>

                <!-- Quantité -->
                <div class="form-group col-md-4">
                    <label class="form-label">Quantité <code>*</code></label>
                    <input type="number" value="1" class="form-control" name="billings[${billingIndex}][quantity]" required />
                </div>

                <!-- Périodicité -->
                <div class="form-group col-md-4">
                    <label class="form-label">Périodicité <code>*</code></label>
                    <select class="form-control" name="billings[${billingIndex}][periodicity]" required>
                        <option value="daily">Quotidienne</option>
                        <option value="weekly">Hebdomadaire</option>
                        <option value="monthly">Mensuelle</option>
                        <option value="yearly">Annuelle</option>
                    </select>
                </div>

                <!-- Montant pénalité (Frais d’enlèvement) -->
                <div class="form-group col-md-4">
                    <label class="form-label">Montant pénalité <sup>(Frais d'enlèvement)</sup> <code>*</code></label>
                    <input type="number" step="0.01" class="form-control" name="billings[${billingIndex}][penalties]" required />
                </div>

                <!-- Montant & Périodicité pénalité (Frais de fourrière) -->
                <div class="form-group col-md-7">
                    <label class="form-label">
                        Montant & Périodicité pénalité <sup>(Frais de fourrière)</sup> <code>*</code>
                    </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Montant</span>
                        </div>
                        <input 
                            type="number" 
                            step="0.01" 
                            class="form-control" 
                            name="billings[${billingIndex}][penalties_pound_amount]" 
                            required 
                        />
                        <div class="input-group-prepend">
                            <span class="input-group-text">Périodicité</span>
                        </div>
                        <select 
                            class="form-control" 
                            name="billings[${billingIndex}][penalties_pound_periodicity]" 
                            required
                        >
                            <option value="daily">Quotidienne</option>
                            <option value="weekly">Hebdomadaire</option>
                            <option value="monthly">Mensuelle</option>
                            <option value="yearly">Annuelle</option>
                        </select>
                    </div>
                </div>

                <!-- Bouton supprimer -->
                <div class="form-group col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger btn-icon" onclick="removeBilling(this)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>

                <hr style="border: 1px solid black;width: 100%;margin: 1rem 0;">
            `;

            document.querySelector('#facturation-container').appendChild(billingSection);
        }


        function removeBilling(element) {
            const billingSection = element.closest('.billing-section');
            billingSection.remove();
        }


    </script>
{{-- rubrique_container --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/entite.show.js') }}"></script>
@endpush
