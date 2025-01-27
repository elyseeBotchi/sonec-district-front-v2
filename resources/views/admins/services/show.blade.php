@extends('layout.adminApp')

@section('content')
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">
               <span id="TaxeEntity"> <i class="fa fa-spinner fa-spin"></i></span>
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
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Informations sur le véhicule</h4>
    
                        <div class="row">
                            
                       @if(CanPermission('rendez_vous_modifier_les_informations_validees')) 
                            <button data-toggle="modal" data-target="#updateElement-modal" data-uuid="{{ $element_uuid ?? ''}}" data-name="" data-description="" title="Modifier le véhicule" style="justify-content: center" class="btn btn-rounded btn-warning col-sm-3 updateElement" style="position:relative;margin-top:-125px !important;">
                                Modifier les informations validées
                            </button>
                        @endif

                            <table class="table">
                                <tbody id="html_render">
                                    <tr>
                                        <td colspan="2">
                                            <span class="fa fa-spinner fa-spin"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                      
                    <div class="card-footer">{{--  style="display: none" --}}
                        <div id="validation-info">
                                @if(CanPermission('rendez_vous_valider_les_donnees_dun_vehicule')) 
                                    <button data-toggle="modal" data-target="#rejet-modal" class="btn btn-rounded btn-danger col-sm-3">
                                        Rejeter
                                    </button>
                                @endif

                                @if(CanPermission('rendez_vous_modifier_les_informations_du_vehicule')) 
                                    <button data-toggle="modal" data-target="#updateElement-modal" data-uuid="{{ $element_uuid ?? ''}}" data-name="" data-description="" title="Modifier le véhicule" style="justify-content: center" class="btn btn-rounded btn-warning col-sm-3 updateElement">
                                        Modifier
                                    </button>
                                @endif

                                @if(CanPermission('rendez_vous_valider_les_donnees_dun_vehicule')) 
                                    <button url="{{ route('panel.autorisations.services.taxes.validation',['uuid' => $element_uuid ?? '','entity_uuid' => $entity_uuid ?? '','status' => 'validate']) }}" caption="CONFIRMER LA VALIDATION" class="btn btn-rounded btn-success col-sm-3 validate-info float-right">
                                        Valider
                                    </button>
                                @endif
                        </div>

                    </div>  

                    
                   
                </div> 
            </div>

            <div class="modal fade" id="rejet-modal" data-keyboard="false" data-backdrop="static" tabindex="-1" aria-labelledby="rejetModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <!-- Champ caché pour l'UUID -->
                        <input type="hidden" name="entity_uuid" value="{{ $entity_uuid ?? '' }}" required />
            
                        <!-- En-tête du modal -->
                        <div class="modal-header">
                            <h5 class="modal-title mb-0 text-uppercase" id="rejetModalLabel">Rejet de la conformité</h5>
                            <button type="button" class="close btn-link-danger" data-dismiss="modal" aria-label="Fermer">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
            
                        <!-- Corps du modal -->
                        <div class="modal-body card">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="rejet_reason">Raison du rejet</label>
                                    <textarea name="rejet_reason" id="rejet_reason" class="form-control" required></textarea>
                                </div>
                            </div>
                        </div>
            
                        <!-- Pied de page du modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-shadow closeModal" data-dismiss="modal">Fermer</button>
                            <button 
                                id="rejet-button"
                                url="{{ route('panel.autorisations.services.taxes.validation', ['uuid' => $element_uuid ?? '', 'entity_uuid' => $entity_uuid ?? '', 'status' => 'fail', 'motif' => '']) }}" 
                                caption="Confirmer le rejet" 
                                class="btn btn-primary btn-shadow validate-info">
                                Rejeter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
                        
            <div class="modal fade" id="updateElement-modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <form class="modal-content sendForm" action="{{ route('panel.autorisations.services.taxes.element.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="entity_uuid" value="{{ $entity_uuid ?? '' }}" required />
                        <input type="hidden" name="uuid" id="update-uuid" required />
                        
                        <div class="modal-header">
                            <h5 class="mb-0 text-uppercase">Modifier les informations d'un véhicule  </h5>
                            <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal">
                                <i class="ti ti-x f-20"></i>
                            </a>
                        </div>
                        <div class="modal-body">
                            <div class="row" id="form-container-update"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-shadow closeModal" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary btn-shadow">Sauvegarder</button>
                        </div>
                    </form>
                </div>
            </div>


            @if(CanPermission('entites_voir_lhistorique_des_paiements_dune_entite'))  
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Historique des paiements du véhicule</h4>
    
                        <div class="row table-responsive">
                            <table class="table table-striped" id="history_container">
                                <thead>
                                    <tr>
                                        
                                        <td>
                                            <strong>Date de paiement </strong>
                                        </td>
                                        <td>
                                            <strong>TAXE</strong>
                                        </td>
                                        <td>
                                            <strong>référence du paiement</strong>
                                        </td>
                                        <td>
                                            <strong>Montant payé</strong>
                                        </td>
                                        <td>
                                            <strong>Mode de paiement</strong>
                                        </td>
            
                                        <td>
                                            <strong>Statut du paiement</strong>
                                        </td>
                                        {{----}} 
                                        <td>
                                            Action
                                        </td> 
                                    </tr>
                                </thead>
                                <tbody id="history_render">
                                    <tr class="border-0">
                                        <td colspan="8"> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> 

         

                </div> 
            </div> 
            @endif

        </div>
    </div>
@endsection


@push('footer-script')
    <script>
        var Element_uuid = @Json($element_uuid);
        var Entity_uuid = @Json($entity_uuid);
        /* ########################################################## */
            document.addEventListener('DOMContentLoaded', function () {
                const rejetReasonInput = document.getElementById('rejet_reason');
                const rejetButton = document.getElementById('rejet-button');
        
                // Fonction pour mettre à jour l'URL du bouton
                const updateRejetButtonUrl = () => {
                    const motif = encodeURIComponent(rejetReasonInput.value); // Encode la valeur pour URL
                    const baseUrl = rejetButton.getAttribute('url');
                    const updatedUrl = baseUrl.replace(/motif=[^&]*/, `motif=${motif}`); // Remplace le paramètre 'motif'
                    rejetButton.setAttribute('url', updatedUrl);
                };
        
                // Ajouter un événement pour détecter les changements dans le champ texte
                rejetReasonInput.addEventListener('input', updateRejetButtonUrl);
            });

    </script>

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/services_show.js') }}"></script>
@endpush
