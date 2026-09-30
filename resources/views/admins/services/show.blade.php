@extends('layout.adminApp')

@section('content')
    <span id="TaxeEntity" style="display:none;"></span>

    <div class="v2-toolbar">
        <div style="display:flex; align-items:center; gap:12px;">
            <a href="javascript:history.back()" class="v2-btn v2-btn--ghost" style="border-radius:50%; padding:11px;" title="Retour">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            </a>
            <div>
                <h1 class="v2-toolbar__title">Détails du véhicule</h1>
                <p id="vehicle-detail-subtitle" style="color: var(--v2-primary-dark); font-size: 13px; font-weight: 700; letter-spacing: .04em; margin: 2px 0 0; text-transform: uppercase;">
                    <span class="v2-spinner v2-spinner--sm"></span>
                </p>
            </div>
        </div>
        <div class="v2-toolbar__actions">
            <button type="button" id="vehicle-detail-print" class="v2-btn v2-btn--primary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Imprimer le reçu
            </button>
        </div>
    </div>

    <div  id="container">
        <div class="row">
            <div class="col-xl-12">
                @if(CanPermission('rendez_vous_modifier_les_informations_validees'))
                    <div class="text-end mb-3">
                        <button data-toggle="modal" data-target="#updateElement-modal" data-uuid="{{ $element_uuid ?? ''}}" data-name="" data-description="" title="Modifier le véhicule" class="v2-btn v2-btn--ghost updateElement">
                            Modifier les informations validées
                        </button>
                        <button data-toggle="modal" data-target="#updateElementType-modal" data-uuid="{{ $element_uuid ?? ''}}" data-name="" data-description="" title="Modifier le véhicule" class="v2-btn v2-btn--ghost updateElementType">
                            Modifier le type de véhicule
                        </button>
                    </div>
                @endif

                <div id="html_render" style="margin-bottom: 20px;">
                    <div class="v2-card">
                        <div class="v2-table-loading">
                            <span class="v2-spinner"></span> Chargement des données...
                        </div>
                    </div>
                </div>

                <div id="validation-info" class="v2-card" style="margin-bottom: 20px;">
                    <div style="display: flex; gap: 10px; flex-wrap: wrap; justify-content: flex-end;">
                        @if(CanPermission('rendez_vous_valider_les_donnees_dun_vehicule'))
                            <button data-toggle="modal" data-target="#rejet-modal" class="v2-btn v2-btn--danger">
                                Rejeter
                            </button>
                        @endif

                        @if(CanPermission('rendez_vous_modifier_les_informations_du_vehicule'))
                            <button data-toggle="modal" data-target="#updateElement-modal" data-uuid="{{ $element_uuid ?? ''}}" data-name="" data-description="" title="Modifier le véhicule" class="v2-btn v2-btn--ghost updateElement">
                                Modifier
                            </button>
                        @endif

                        @if(CanPermission('rendez_vous_valider_les_donnees_dun_vehicule'))
                            <button url="{{ route('panel.autorisations.services.taxes.validation',['uuid' => $element_uuid ?? '','entity_uuid' => $entity_uuid ?? '','status' => 'validate']) }}" caption="CONFIRMER LA VALIDATION" class="v2-btn v2-btn--success validate-info">
                                Valider
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="modal fade" id="rejet-modal" data-keyboard="false" data-backdrop="static" tabindex="-1" aria-labelledby="rejetModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <!-- Champ caché pour l'UUID -->
                        <input type="hidden" name="entity_uuid" value="{{ $entity_uuid ?? '' }}" required />
            
                        <!-- En-tête du modal -->
                        <div class="v2-modal-header v2-modal-header--danger">
                            <span class="v2-modal-header__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            </span>
                            <p class="v2-modal-header__title" id="rejetModalLabel">Rejet de la conformité</p>
                            <button type="button" class="close btn-link-danger" data-dismiss="modal" aria-label="Fermer" style="margin-left:auto;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <!-- Corps du modal -->
                        <div class="v2-modal-body">
                            <label class="v2-modal-label" for="rejet_reason">Raison du rejet</label>
                            <textarea name="rejet_reason" id="rejet_reason" class="v2-modal-input" rows="4" placeholder="Indiquez la raison du rejet de la conformité..." required></textarea>
                        </div>

                        <!-- Pied de page du modal -->
                        <div class="v2-modal-footer">
                            <button type="button" class="v2-modal-footer__close closeModal" data-dismiss="modal">Fermer</button>
                            <button
                                id="rejet-button"
                                url="{{ route('panel.autorisations.services.taxes.validation', ['uuid' => $element_uuid ?? '', 'entity_uuid' => $entity_uuid ?? '', 'status' => 'fail', 'motif' => '']) }}"
                                caption="Confirmer le rejet"
                                class="v2-btn v2-btn--danger validate-info">
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
                        
                        <div class="v2-modal-header v2-modal-header--primary">
                            <span class="v2-modal-header__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </span>
                            <div>
                                <p class="v2-modal-header__title">Modifier les informations d'un véhicule</p>
                                <p class="v2-modal-header__sub">Mise à jour des champs d'identification</p>
                            </div>
                            <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal" style="margin-left:auto;">
                                <i class="ti ti-x f-20"></i>
                            </a>
                        </div>
                        <div class="v2-modal-body">
                            <div class="row" id="form-container-update"></div>
                        </div>
                        <div class="v2-modal-footer">
                            <button type="button" class="v2-modal-footer__close closeModal" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="v2-btn v2-btn--primary">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Sauvegarder les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>


                                    
            <div class="modal fade" id="updateElementType-modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <form class="modal-content sendForm" action="{{ route('panel.autorisations.services.taxes.element.update_type_vehicule') }}" method="POST">
                        @csrf
                        <input type="hidden" name="entity_uuid" value="{{ $entity_uuid ?? '' }}" required />
                        <input type="hidden" name="uuid" id="update-type-uuid" required />
                        
                        <div class="v2-modal-header v2-modal-header--primary">
                            <span class="v2-modal-header__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17h-2v-6l2-5h9l4 5h1a2 2 0 0 1 2 2v4h-2"></path><circle cx="7" cy="17" r="2"></circle><circle cx="17" cy="17" r="2"></circle></svg>
                            </span>
                            <p class="v2-modal-header__title">Modifier le type de véhicule</p>
                            <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal" style="margin-left:auto;">
                                <i class="ti ti-x f-20"></i>
                            </a>
                        </div>
                        <div class="v2-modal-body">
                            <div class="row" id="form-container-update-type"></div>

                            <div style="margin-bottom: 16px;">
                                <label class="v2-modal-label">Type de véhicule <code>*</code></label>
                                <select class="v2-modal-input FindLieuRDV" name="rubrique_facturation_uuid" id="rubrique"></select>
                            </div>

                            <div>
                                <label class="v2-modal-label">Montant à payer</label>
                                <input type="text" class="v2-modal-input" placeholder="Montant à payer" id="montant_pay" readonly disabled>
                            </div>
                        </div>
                        <div class="v2-modal-footer">
                            <button type="button" class="v2-modal-footer__close closeModal" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="v2-btn v2-btn--navy">Sauvegarder</button>
                        </div>
                    </form>
                </div>
            </div>

            @if(CanPermission('entites_voir_lhistorique_des_paiements_dune_entite'))
            <div class="col-xl-12">
                <div class="v2-card">
                    <div class="v2-card__header">
                        <p class="v2-card__title">Historique des paiements du véhicule</p>
                    </div>
                    <div class="v2-table-wrap">
                        <table class="v2-table" id="history_container">
                            <thead>
                                <tr>
                                    <th>Date de paiement</th>
                                    <th>Taxe</th>
                                    <th>Référence du paiement</th>
                                    @if(CanPermission('support_annuler_un_paiement'))
                                        <th>Transaction Id</th>
                                    @endif
                                    <th class="is-numeric">Montant payé</th>
                                    <th>Mode de paiement</th>
                                    <th>Statut du paiement</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="history_render">
                                <tr class="v2-table-loading">
                                    <td colspan="8"><span class="v2-spinner"></span> Chargement des données...</td>
                                </tr>
                            </tbody>
                        </table>
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
