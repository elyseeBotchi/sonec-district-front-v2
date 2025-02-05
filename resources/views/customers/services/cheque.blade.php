@extends('layout.customerApp')

@section('content')
    <div class="row card" id="container">
        

        <div class="col-sm-12 card-body">
            <div class="alert alert-info" role="alert" id="formulaire">
                <i class="fa fa-info-circle me-2" aria-hidden="true"></i>
                <strong class="text-uppercase">Informations importantes :</strong>
                <ul>
                    <li>
                        Ce service est destiné aux entreprises disposant d'une flotte et souhaitant effectuer des paiements par chèque.
                    </li>
                    
                </ul>
            </div>
            <br>

            <div class="text-end mb-4">
               <h4> LISTE DES DEMANDE DE COTATION </h4>
               <br> 
               <a href="#" class="btn btn-rounded btn-outline-primary" data-toggle="modal" data-target="#add-modal">
                    <i class="fas fa-plus"></i> GENERER UNE PROFORMA
                </a>
            </div>

            <div class="modal fade" id="add-modal" data-keyboard="false" data-backdrop="static" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <form class="modal-content sendForm" action="{{ route('customer.entities.taxe.cheque.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="entity_uuid" value="{{ $entity_uuid ?? '' }}" required />
            
                        <div class="modal-header">
                            <h5 class="mb-0 text-uppercase">Faire une demande de cotation</h5>
                            <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal">
                                <i class="ti ti-x f-20"></i>
                            </a>
                        </div>
                        <div class="modal-body">
                            <div class="row" id="form-container">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="libelle">Libellé de la cotation <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="libelle" name="libelle" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="libelle">Nom de l'entreprise <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="libelle" name="nom_du_proprietaire" value="{{ Authconnect()['firstname'] ?? '' }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="libelle">N° Compte contribuable <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="contribuable" name="contribuable" placeholder="" value="{{ Authconnect()['contribuable'] ?? '' }}" @isset(Authconnect()['contribuable']) disabled @endisset required />
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="libelle">Nombre de véhicule à déclarer <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="nombre_vehicule" name="nombre_vehicule" placeholder="" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="libelle">Contact téléphonique <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="telephone" name="telephone" placeholder="" required>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Type de véhicule <code>*</code></label>
                                    <select class="form-select bg-light border-0 FindLieuRDV" name="rubrique_facturation_uuid" id="rubrique" style="height: 40px;"></select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Montant à payer</label>
                                    <input type="text" class="form-control bg-light border-0" placeholder="Montant à payer"  id="montant_pay" readonly disabled  style="height: 40px;">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-shadow closeModal" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary btn-shadow">Sauvegarder</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- <div class="modal fade" id="add-modal" data-keyboard="false" data-backdrop="static" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <form class="modal-content sendEntiteForm" action="{{ route('panel.autorisations.services.cheque.reception.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="entity_uuid" value="{{ $entity_uuid ?? '' }}" required />
            
                        <div class="modal-header">
                            <h5 class="mb-0 text-uppercase">Faire une demande de cotation</h5>
                            <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal">
                                <i class="ti ti-x f-20"></i>
                            </a>
                        </div>
                        <div class="modal-body">
                            <div class="row" id="form-container">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="check_number">Numéro du chèque <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="check_number" name="check_number" placeholder="Entrez le numéro du chèque" required>
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
                            <button type="button" class="btn btn-secondary btn-shadow closeModal" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary btn-shadow">Sauvegarder</button>
                        </div>
                    </form>
                </div>
            </div> --}}



            <div class="pt-5 ">
                <table class="table table-striped table-bordered" id="datatable-custom">
                    <thead>
                    <tr>
                        <th>Désignation de la cotation</th>
                        <th>Réference de la cotation</th>
                        <th>Contribuable</th>
                        <th>Nombre de véhicule</th>
                        <th>Statut</th>
                        <th style="width:150px !important;">Action</th>
                    </tr>
                    </thead>
                    <tbody id="render-html">
                        <tr>
                            <td colspan="6"> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ... </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
@endsection

@push('footer-script')
<script>
    var Entity_uuid = @Json($entity_uuid);
</script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
<script src="{{ asset('/backoffice/js/front/services_cheque.js') }}"></script>
@endpush
