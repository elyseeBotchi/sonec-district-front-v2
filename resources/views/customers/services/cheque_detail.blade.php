@extends('layout.customerApp')

@section('content')
<div id="container">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">DETAIL DE LA DEMANDE DE COTATION <span id="libelle-cheque"></span> </h4>    
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <tr>
                    <td>Libellé</td>
                    <td><b id="libelle-cotation"> <i class="fa fa-spinner fa-spin"></i></b> </td>
                </tr>
                <tr>
                    <td>Nom de l'entreprise</td>
                    <td ><b id="entreprise-cotation"><i class="fa fa-spinner fa-spin"></i></b> </td>
                </tr>
                <tr>
                    <td>Compte contribuable</td>
                    <td > <b id="contribuable-cotation"><i class="fa fa-spinner fa-spin"></i></b> </td>
                </tr>
                <tr>
                    <td>Réference</td>
                    <td  > <b id="reference-cotation"><i class="fa fa-spinner fa-spin"></i></b> </td>
                </tr>
                <tr>
                    <td>Nombre de voiture à déclarer </td>
                    <td> <b id="nbre_vehicule-cotation"><i class="fa fa-spinner fa-spin"></i></b> </td>
                </tr>
                <tr>
                    <td>Contact téléphonique </td>
                    <td> <b id="telephone-cotation"><i class="fa fa-spinner fa-spin"></i></b> </td>
                </tr>
                <tr>
                    <td>Statut de la demande </td>
                    <td> <b id="statut-cotation"><i class="fa fa-spinner fa-spin"></i></b> </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row"> 
        
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
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="render-html">
                    <tr>
                        <td colspan="6"> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ... </td>
                    </tr>
                </tbody>
            </table>
            <center id="submit-cotation"></center>
        </div>
    </div>

    
    <div class="modal fade" id="customer-edit_add-modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form class="modal-content sendEntiteForm" action="{{ route('customer.entities.taxe.store') }}" method="POST">
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Nom du proprietaire </label>
                                <input type="text" class="form-control" value="{{ UserConnect()['firstname'] ?? '' }} {{ UserConnect()['lastname'] ?? '' }}" placeholder="Nom du proprietaire" name="nom_du_proprietaire" required="" minlength="3" maxlength="50" style="text-transform:uppercase;" oninput="this.value = this.value.toUpperCase();">
                            </div>
                        </div>

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

    
    <div class="modal fade" id="updateElement-modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg  modal-dialog-centered modal-dialog-scrollable">
            <form class="modal-content sendEntiteForm" action="{{ route('customer.entities.taxe.update') }}" method="POST">
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
</div>
@endsection

@push('footer-script')
    <script>
        var cheque_uuid = @Json($cheque_uuid ?? '');
        var Entity_uuid = @Json($entity_uuid ?? '');
    </script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script> 
<script src="{{ asset('/backoffice/js/front/detail_cheque.js') }}"></script> 
@endpush
