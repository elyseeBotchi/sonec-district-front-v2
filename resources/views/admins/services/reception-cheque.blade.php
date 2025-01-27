@extends('layout.adminApp')

@section('content')
<div class="card-group">
    
</div>



@if(CanPermission('rendez_vous_rechercher_un_vehicule'))@endif 
    <div class="row col-md-12">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <h4 class="card-title mb-0">RECEPTION DES CHEQUES</h4>
                  
                    </div> 
                    
                    <div class="ml-auto">
                       

                        <div class="hide js-show">
                            <br>
                             <a href="#" class="btn btn-rounded btn-outline-primary"  data-toggle="modal" data-target="#add-modal">
                                <i class="fas fa-plus"></i> ENREGISTRER UN CHEQUE
                            </a>

                            <div class="modal fade" id="add-modal" data-keyboard="false" data-backdrop="static" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                    <form class="modal-content sendEntiteForm" action="{{ route('panel.autorisations.services.cheque.reception.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="entity_uuid" value="{{ $entity_uuid ?? '' }}" required />
                            
                                        <div class="modal-header">
                                            <h5 class="mb-0 text-uppercase">Ajouter un chèque</h5>
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
                                                        <label for="check_date">Date d'émission <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" id="check_date" name="check_date" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="check_amount">Montant du chèque <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control" id="check_amount" name="check_amount" placeholder="Entrez le montant" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="account_holder">Titulaire du compte <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="account_holder" name="account_holder" placeholder="Nom du titulaire du compte" required>
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
                            </div>
                            

                            <div class="card-custom mb-4">
                                <div class="card-header-custom">
                                    <span id="languageSelectLabel" style="font-size:x-small;">
                                        <br>
                                    </span>
                                </div>

                                <form class="searchForm" action="{{ route('panel.autorisations.services.taxes.cheque.search') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $Entity_uuid ?? '' }}" name="entity_uuid"  required />
                                    <div class="card-body-custom">
                                        <div class="row col-md-12 billing-section">
                                            <div class="form-group col-md-11">
                                                <label class="form-label">
                                                    RECHERCHER UN CHEQUE
                                                </label>
                                                <input type="text" class="form-control" name="search" placeholder="Entrez la référence du cheque" required />
                                            </div>
                                        
                                            <div class="form-group col-md-1">
                                                <label class="form-label">&nbsp; &nbsp; &nbsp; </label>
                                                <button type="submit" id="submitBtn" class="btn btn-icon waves-effect waves-light material-shadow-none btn-outline-primary" title="Rechercher" >
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            
                            </div>
                        </div>

                    </div>
                    
                </div>
            </div>
        </div>
    </div>


@push('footer-script')
@isset($Entity_uuid)
    <script>
        var Entity_uuid = @Json($Entity_uuid ?? '');

        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>
@endisset

    {{-- <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script> --}}
    {{--<script src="{{ asset('/backoffice/js/services_cheque.js') }}"></script>  --}}
@endpush
@endsection
