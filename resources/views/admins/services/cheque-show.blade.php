@extends('layout.adminApp')

@section('content')
<div id="container">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h1 class="mb-sm-0">DETAIL DE LA DEMANDE DE COTATION <span id="libelle-cheque"></span> </h1>    
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <tbody>
                    <tr>
                        <td> 
                            <h3>Libellé</h3>
                        </td>
                        <td>
                            <h3><b id="libelle-cotation"> <i class="fa fa-spinner fa-spin"></i></b> </h3>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>
                                Nom de l'entreprise   
                            </h3>
                        </td>
                        <td>
                            <h3>
                                <b id="entreprise-cotation"><i class="fa fa-spinner fa-spin"></i></b>
                            </h3> 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>
                                Compte contribuable
                            </h3>
                        </td>
                        <td >
                            <h3>
                                <b id="contribuable-cotation"><i class="fa fa-spinner fa-spin"></i></b>
                            </h3>  
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>
                                Réference
                            </h3>
                        </td>
                        <td> 
                            <h3>
                                <b id="reference-cotation"><i class="fa fa-spinner fa-spin"></i></b>
                            </h3> 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>
                                Nombre de voiture à déclarer 
                            </h3>
                        </td>
                        <td> 
                            <h3>
                                <b id="nbre_vehicule-cotation"><i class="fa fa-spinner fa-spin"></i></b>
                            </h3> 
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <h3>
                                Nombre de voiture enregistré 
                            </h3>
                        </td>
                        <td> 
                            <h3>
                                <b id="nbre_vehicule_enregistrer-cotation"><i class="fa fa-spinner fa-spin"></i></b>
                            </h3> 
                        </td>
                    </tr>
                    {{-- <tr>
                        <td>Contact téléphonique </td>
                        <td> <b id="telephone-cotation"><i class="fa fa-spinner fa-spin"></i></b> </td>
                    </tr> --}}
                    <tr>
                        <td>
                            <h3>
                                Statut de la demande
                            </h3> 
                        </td>
                        <td>
                            <h3>
                                <b id="statut-cotation"><i class="fa fa-spinner fa-spin"></i></b>
                            </h3>  
                        </td>
                    </tr>
                       
                </tbody>
                
                


            </table>

            <div  class="info-cheque" style="display: none">
<br>
<br>
                <table class="table" >
                    <thead>
                        <tr>
                            <th colspan="2">
                                <h1>INFORMATIONS DU CHEQUE</h1>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <h3>
                                Numéro du chèque
                                </h3> 
                            </td>
                            <td>
                                <h3>
                                    <b id="numero-cheque"><i class="fa fa-spinner fa-spin"></i></b>
                                </h3>  
                            </td>
                        </tr>

                        
                        <tr  >
                            <td>
                                <h3>
                                Banque émettrice
                                </h3> 
                            </td>
                            <td>
                                <h3>
                                    <b id="banque-cheque"><i class="fa fa-spinner fa-spin"></i></b>
                                </h3>  
                            </td>
                        </tr>

                        
                        <tr>
                            <td>
                                <h3>
                                Date d'émission
                                </h3> 
                            </td>
                            <td>
                                <h3>
                                    <b id="date-emission"><i class="fa fa-spinner fa-spin"></i></b>
                                </h3>  
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <h3>
                                Date d'encaissement
                                </h3> 
                            </td>
                            <td>
                                <h3>
                                    <b id="date-encaissement"><i class="fa fa-spinner fa-spin"></i></b>
                                </h3>  
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <h3>
                                Montant chèque
                                </h3> 
                            </td>
                            <td>
                                <h3>
                                    <b id="montant-cheque"><i class="fa fa-spinner fa-spin"></i></b>
                                </h3>  
                            </td>
                        </tr>
                        
                        <tr>
                            <td>
                                <h3>
                                Titulaire du compte
                                </h3> 
                            </td>
                            <td>
                                <h3>
                                    <b id="titulaire-compte"><i class="fa fa-spinner fa-spin"></i></b>
                                </h3>  
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="pay-cotation"  class="text-center"></div>

    <div class="row"> 
        

        
        
        <div id="rejeter-cotation" class="float-left"></div>

        <hr>
        
        <div id="confirm-cotation" class="float-right"></div>
        
    

        
        <div class="col-md-12">
            <br>
            <span id="add-cotation"></span>
            <span class="col-md-12" style="margin-bottom: 10px">
                <br>
                <center class="col-md-12"><span  id="alert-message"></span> </center>
            </span>
        
            
            <table class="table table-striped table-bordered" id="datatable-custom">
                <thead>
                <tr>
                    <th>Nom du propriétaire</th>
                    <th>Numéro de la carte grise</th>
                    <th>Numéro d'immatriculation</th>
                    <th>Type de véhicule</th>
                    <th>Montant</th>
                    <th>État</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="render-html">
                    <tr>
                        <td colspan="9"> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ... </td>
                    </tr>
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="4" style="text-align:right">Total :</th>
                        <th id="totalAmount"></th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
            </table>
            <br>
       
        </div>

        
    </div>

        <center>
            <span id="submit-cotation"></span>
        </center>
    
    <div class="modal fade" id="customer-edit_add-modal" data-keyboard="false" data-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form class="modal-content sendEntiteForm" action="{{ route('panel.autorisations.services.taxes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="entity_uuid" value="{{ $entity_uuid ?? '' }}" required />
                <input type="hidden" value="cotation" name="target" required/>
                <input type="hidden" value="{{ $cheque_uuid ?? '' }}" name="cheque_uuid" required />
                <div class="modal-header">
                    <h5 class="mb-0 text-uppercase">Ajouter un véhicule  </h5>{{--  à <span class="services"></span> --}}
                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal">
                        <i class="ti ti-x f-20"></i>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row" id="form-container"></div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Type de véhicule <code>*</code></label>
                            <select class="form-select bg-light border-0 FindLieuRDV form-control" name="rubrique_facturation_uuid" id="rubrique" style="height: 40px;"></select>
                        </div>
    
                        <div class="col-md-6">
                            <label class="form-label">Montant à payer</label>
                            <input type="text" class="form-control border-0" placeholder="Montant à payer"  id="montant_pay" readonly disabled  style="height: 40px;">
                        </div>

                        <div class="col-md-12">
                            <input type="hidden" class="form-control bg-light border-0" placeholder="Lieu de rendez vous"  id="lieu_rdv" readonly disabled  style="height: 40px;">
                            <input type="hidden"  name="lieu_rdv" id="list_rdv" required style="height: 40px;">
                            <input type="hidden" name="rdv" value="{{ date('Y-m-d') }}" />
                        </div>

                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-shadow closeModal" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary btn-shadow" id="submitBtn">Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="modal fade" id="updateElement-modal" data-keyboard="false" data-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg  modal-dialog-centered modal-dialog-scrollable">
            <form class="modal-content sendEntiteForm" action="{{ route('panel.autorisations.services.taxes.element.update') }}" method="POST">
                @csrf
                <input type="hidden" name="entity_uuid" value="{{ $entity_uuid ?? '' }}" required />
                <input type="hidden" name="uuid" id="update-uuid" required />
                <input type="hidden" value="cotation" name="target" required/>
                <input type="hidden" value="{{ $cheque_uuid ?? '' }}" name="cheque_uuid" required />
               
                <div class="modal-header">
                    <h5 class="mb-0 text-uppercase">Modifier un véhicule  </h5>
                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal">
                        <i class="ti ti-x f-20"></i>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row" id="form-container-update"><i class="fa fa-spinner fa-spin text-center"></i> </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Type de véhicule <code>*</code></label>
                            <select class="form-select bg-light border-0 FindLieuRDVUpdate form-control" name="rubrique_facturation_uuid" id="rubriqueUpdate" style="height: 40px;"></select>
                        </div>
    
                        <div class="col-md-6">
                            <label class="form-label">Montant à payer</label>
                            <input type="text" class="form-control border-0" placeholder="Montant à payer"  id="montant_payUpdate" readonly disabled  style="height: 40px;">
                        </div>

                        <div class="col-md-12">
                            <input type="hidden" class="form-control bg-light border-0" placeholder="Lieu de rendez vous"  id="lieu_rdvUpdate" readonly disabled  style="height: 40px;">
                            <input type="hidden"  name="lieu_rdv" id="list_rdvUpdate" required style="height: 40px;">
                            <input type="hidden" name="rdv" value="{{ date('Y-m-d') }}" />
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-shadow closeUpModal" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary btn-shadow" id="submitBtnUpdate">Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>


    @if(canPermission('cheques_proceder_au_paiement_par_cheque'))
        <div class="modal fade" id="payElement-modal" data-keyboard="false" data-backdrop="static" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <form class="modal-content sendEntiteForm" action="{{ route('panel.autorisations.services.cheque.reception.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="entity_uuid" value="{{ $entity_uuid ?? '' }}" required />
                    <input type="hidden" name="cheque_uuid" value="{{ $cheque_uuid ?? '' }}" required />
        
                    <div class="modal-header">
                        <h5 class="mb-0 text-uppercase">PROCEDER AU PAIEMENT PAR CHEQUE</h5>
                        <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal">
                            <i class="ti ti-x f-20"></i>
                        </a>
                    </div>
                    <div class="modal-body">
                        <div class="row" id="form-container">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="check_number">Numéro du chèque <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="numero_cheque" name="numero_cheque" placeholder="Entrez le numéro du chèque" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bank_name w-100">Banque émettrice <span class="text-danger">*</span></label>
                                    <select class="form-control bg-light border-0 js-example-basic-single" name="banque_emettrice" style="height: 50px !important;width: 100%;padding: 0.375rem 0.75rem;" required>
                                        <option value="" disabled selected>Sélectionnez une banque</option>
                                        @forelse(liste_banques() as $bank)
                                            <option value="{{ $bank['sigle'] ?? "" }}">
                                                {{ $bank['sigle'] ?? '' }} | {{ $bank['nom'] ?? '' }}
                                            </option>
                                            @empty
                                            <option value="" disabled>Aucune banque disponible</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_emission">Date d'émission <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_emission" name="date_emission" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="montant_cheque">Montant du chèque <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="montant_cheque" name="montant_cheque" placeholder="Entrez le montant" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="titulaire_compte">Titulaire du compte <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="titulaire_compte" name="titulaire_compte" placeholder="Nom du titulaire du compte" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-shadow closePayModal" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary btn-shadow">Sauvegarder</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

     @if(canPermission('cheques_valider_un_cheque'))
        <div class="modal fade" id="confirmElement-modal" data-keyboard="false" data-backdrop="static" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <form class="modal-content sendEntiteForm" action="{{ route('panel.autorisations.services.taxes.cheque.valider.cheque') }}" method="POST">
                    @csrf
                    <input type="hidden" name="entity_uuid" value="{{ $entity_uuid ?? '' }}" required />
                    <input type="hidden" name="uuid"  value="{{ $cheque_uuid ?? '' }}" required />
                    <input type="hidden" value="{{ $cheque_uuid ?? '' }}" name="cheque_uuid" required />
                
                    <div class="modal-header">
                        <h5 class="mb-0 text-uppercase">VALIDER LE PAIEMENT DU CHEQUE </h5>
                        <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal">
                            <i class="ti ti-x f-20"></i>
                        </a>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Date d'encaissement <code>*</code></label>
                                <input type="date" class="form-control" placeholder="Date d'encaissement" id="dateEncaissementUpdate" name="date_encaissement" required style="height: 40px;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-shadow closeConfirmModal" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary btn-shadow" id="submitdateEncaissementUpdate">Sauvegarder</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
@endsection

@push('footer-script')
    <script>
        var cheque_uuid = @Json($cheque_uuid ?? '');
        var Entity_uuid = @Json($entity_uuid ?? '');
    </script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script> 
<script src="{{ asset('/backoffice/js/cheque-show.js') }}"></script> 
@endpush
